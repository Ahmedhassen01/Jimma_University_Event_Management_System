<?php
// app/Http/Controllers/AnnouncementController.php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Event;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AnnouncementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
        Log::info('AnnouncementController initialized');
    }

    /**
     * Display a listing of announcements
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Announcement::with('creator');
        $isAnnouncementAdmin = $this->isAnnouncementAdmin($user);
        
        // Only super-admin/administrator can see everything.
        // Everyone else only sees published announcements intended for them.
        if (!$isAnnouncementAdmin) {
            $query->where('is_published', true);

            if ($user) {
                $audienceKeys = $this->getUserAudienceKeys($user);
                $query->where(function($q) use ($user, $audienceKeys) {
                    $q->where('audience', 'all')
                        ->orWhereIn('audience', $audienceKeys)
                        ->orWhere(function($q2) use ($user) {
                            $q2->where('audience', 'specific')
                                ->whereJsonContains('target_ids', $user->id);
                        });
                });
            } else {
                // Guests only see public-for-all announcements.
                $query->where('audience', 'all');
            }
        }
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('content', 'like', '%' . $search . '%');
            });
        }
        
        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        // Filter by audience
        if ($request->filled('audience')) {
            $query->where('audience', $request->audience);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'published') {
                $query->where('is_published', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false);
            }
        }
        
        // Filter expired announcements
        if ($request->filled('expired')) {
            if ($request->expired === 'yes') {
                $query->whereNotNull('expires_at')->where('expires_at', '<', now());
            } elseif ($request->expired === 'no') {
                $query->where(function($q) {
                    $q->whereNull('expires_at')
                      ->orWhere('expires_at', '>=', now());
                });
            }
        }
        
        // Order by priority: urgent first, then by date
        $query->orderByRaw("CASE WHEN type = 'urgent' OR title LIKE 'CANCELLED:%' THEN 0 ELSE 1 END")
              ->orderBy('created_at', 'desc');
        
        $announcements = $query->paginate(12);
        
        // Get stats
        $stats = $this->getAnnouncementStats();
        
        return view('announcements.index', compact('announcements', 'stats'));
    }

    /**
     * Show form to create announcement with optional event cancellation
     */
    public function create()
    {
        Log::info('AnnouncementController@create called');
        
        $users = User::where('is_active', true)
                     ->orWhereNull('is_active')
                     ->with('role')
                     ->orderBy('name')
                     ->get(['id', 'name', 'email', 'role_id']);
        
        // Get events that can be cancelled (upcoming/ongoing, not cancelled)
        $cancellableEvents = Event::with(['campusRelation', 'venueRelation'])
            ->where(function($q) {
                $q->where('is_cancelled', false)
                  ->orWhereNull('is_cancelled');
            })
            ->where('end_date', '>=', now())
            ->orderBy('start_date')
            ->get();
        
        return view('announcements.create', compact('users', 'cancellableEvents'));
    }

    /**
     * Store a new announcement (with optional event cancellation)
     */
    public function store(Request $request)
{
    // Base validation rules for all announcements
    $rules = [
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'type' => 'required|in:event,campus,general,urgent',
        'audience' => 'required|in:all,students,faculty,staff,event_managers,specific',
        'target_ids' => 'nullable|array',
        'target_ids.*' => 'exists:users,id',
        'expires_at' => 'nullable|date|after:now',
        'publish_now' => 'boolean',
        'send_notification' => 'boolean',
        'notification_priority' => 'nullable|in:normal,high,urgent',
        'notification_type' => 'nullable|in:announcement,alert,info,warning',
    ];
    
    // Add cancellation validation rules ONLY if is_event_cancellation is checked AND present
    if ($request->has('is_event_cancellation') && $request->boolean('is_event_cancellation')) {
        $rules['cancelled_event_id'] = 'required|exists:events,id';
        $rules['cancellation_reason'] = 'required|string|max:1000';
    }
    
    // Validate the request
    $validated = $request->validate($rules);
    
    // If this is an event cancellation, handle the event cancellation first
    if ($request->has('is_event_cancellation') && $request->boolean('is_event_cancellation')) {
        $event = Event::findOrFail($request->cancelled_event_id);
        
        // Check if event is already cancelled
        if ($event->is_cancelled) {
            return back()->with('error', 'This event is already cancelled.')->withInput();
        }
        
        // Cancel the event
        $event->cancel($request->cancellation_reason);
        
        // Auto-set announcement type to urgent for cancellations
        $request->merge(['type' => 'urgent']);
    }
    
    // Create the announcement
    $announcement = Announcement::create([
        'title' => $request->title,
        'content' => $request->content,
        'type' => $request->type,
        'audience' => $request->audience,
        'target_ids' => $request->audience == 'specific' ? $request->target_ids : null,
        'created_by' => Auth::id(),
        'is_published' => $request->has('publish_now') && $request->boolean('publish_now'),
        'published_at' => ($request->has('publish_now') && $request->boolean('publish_now')) ? now() : null,
        'expires_at' => $request->expires_at,
    ]);
    
    // Send notification if requested
    if ($request->has('publish_now') && $request->boolean('publish_now') && $request->boolean('send_notification', true)) {
        $this->sendAnnouncementNotification($announcement, $request);
        
        // If this was an event cancellation, also notify registered attendees specifically
        if ($request->has('is_event_cancellation') && $request->boolean('is_event_cancellation') && isset($event)) {
            $this->notifyEventAttendees($event, $announcement, $request);
        }
        
        return redirect()->route('announcements.show', $announcement->id)
            ->with('success', 'Announcement created and notification sent successfully!');
    }
    
    return redirect()->route('announcements.show', $announcement->id)
        ->with('success', 'Announcement created successfully!')
        ->with('info', 'Notification was not sent. You can send it later when publishing.');
}

    /**
     * Display the specified announcement
     */
    public function show($id)
    {
        Log::info('AnnouncementController@show called', ['id' => $id]);
        
        if ($id === 'create' || $id === 'statistics') {
            abort(404, 'Announcement not found');
        }
        
        $announcement = Announcement::with('creator')->findOrFail($id);
        
        $user = Auth::user();
        
        // Check if user can view the announcement
        if (!$announcement->is_published) {
            if (!$this->isAnnouncementAdmin($user)) {
                abort(404);
            }
        }

        // Guests can only read audience=all.
        if (!$user && $announcement->audience !== 'all') {
            abort(403, 'You are not authorized to view this announcement.');
        }

        // Check audience restrictions for non-admin users
        if ($user && !$this->isAnnouncementAdmin($user)) {
            $canView = $this->canUserViewAnnouncement($announcement, $user);

            if (!$canView) {
                abort(403, 'You are not authorized to view this announcement.');
            }
        }
        
        // Record view for authenticated users
        if ($user) {
            DB::table('announcement_views')->updateOrInsert(
                ['announcement_id' => $announcement->id, 'user_id' => $user->id],
                ['viewed_at' => now()]
            );
            $announcement->increment('views');
        }
        
        // Get related announcements
        $relatedAnnouncementsQuery = Announcement::where('id', '!=', $announcement->id)
            ->where('is_published', true)
            ->where(function($q) use ($announcement) {
                $q->where('type', $announcement->type)
                  ->orWhere('audience', $announcement->audience);
            })
            ->orderBy('created_at', 'desc');

        if ($user && !$this->isAnnouncementAdmin($user)) {
            $audienceKeys = $this->getUserAudienceKeys($user);
            $relatedAnnouncementsQuery->where(function ($q) use ($user, $audienceKeys) {
                $q->where('audience', 'all')
                    ->orWhereIn('audience', $audienceKeys)
                    ->orWhere(function ($q2) use ($user) {
                        $q2->where('audience', 'specific')
                            ->whereJsonContains('target_ids', $user->id);
                    });
            });
        } elseif (!$user) {
            $relatedAnnouncementsQuery->where('audience', 'all');
        }

        $relatedAnnouncements = $relatedAnnouncementsQuery->limit(4)->get();
        
        // Get users for display if needed
        $users = collect();
        if ($announcement->audience === 'specific' && $announcement->target_ids) {
            $users = User::whereIn('id', $announcement->target_ids)->get();
        }
        
        return view('announcements.show', compact('announcement', 'relatedAnnouncements', 'users'));
    }

    public function edit($id)
    {
        Log::info('AnnouncementController@edit called', ['id' => $id]);
        
        $announcement = Announcement::findOrFail($id);
        
        $users = User::where('is_active', true)
                     ->orWhereNull('is_active')
                     ->with('role')
                     ->orderBy('name')
                     ->get(['id', 'name', 'email', 'role_id']);
        
        return view('announcements.edit', compact('announcement', 'users'));
    }

    public function update(Request $request, $id)
    {
        Log::info('AnnouncementController@update called', ['id' => $id]);
        
        $announcement = Announcement::findOrFail($id);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:event,campus,general,urgent',
            'audience' => 'required|in:all,students,faculty,staff,event_managers,specific',
            'target_ids' => 'nullable|array',
            'target_ids.*' => 'exists:users,id',
            'expires_at' => 'nullable|date',
            'is_published' => 'boolean',
            'send_notification' => 'boolean',
            'notification_priority' => 'nullable|in:normal,high,urgent',
            'notification_type' => 'nullable|in:announcement,alert,info,warning',
        ]);
        
        $wasPublished = $announcement->is_published;
        
        $data = [
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'audience' => $request->audience,
            'target_ids' => $request->audience == 'specific' ? $request->target_ids : null,
            'expires_at' => $request->expires_at,
            'is_published' => $request->is_published ?? $announcement->is_published,
        ];
        
        if ($request->is_published && !$announcement->published_at) {
            $data['published_at'] = now();
        }
        
        $announcement->update($data);
        
        if (($request->is_published && !$wasPublished) || $request->boolean('send_notification', false)) {
            $this->sendAnnouncementNotification($announcement, $request);
            
            $message = 'Announcement updated successfully!';
            if ($request->is_published && !$wasPublished) {
                $message .= ' Notification sent to all target users.';
            } elseif ($request->boolean('send_notification', false)) {
                $message .= ' Notification re-sent to all target users.';
            }
            
            return redirect()->route('announcements.show', $announcement->id)
                ->with('success', $message);
        }
        
        return redirect()->route('announcements.show', $announcement->id)
            ->with('success', 'Announcement updated successfully!');
    }

    public function destroy($id)
    {
        Log::info('AnnouncementController@destroy called', ['id' => $id]);
        
        $announcement = Announcement::findOrFail($id);
        $announcement->delete();
        
        return redirect()->route('announcements.index')
            ->with('success', 'Announcement deleted successfully!');
    }

    public function togglePublish($id)
    {
        Log::info('AnnouncementController@togglePublish called', ['id' => $id]);
        
        $announcement = Announcement::findOrFail($id);
        $wasPublished = $announcement->is_published;
        
        $announcement->update([
            'is_published' => !$announcement->is_published,
            'published_at' => !$announcement->is_published ? now() : $announcement->published_at,
        ]);
        
        $message = $announcement->is_published ? 'published' : 'unpublished';
        
        if ($announcement->is_published && !$wasPublished) {
            $this->sendAnnouncementNotification($announcement);
            
            return back()->with('success', "Announcement published and notification sent successfully!");
        }
        
        return back()->with('success', "Announcement {$message} successfully!");
    }
    
    public function sendNotification($id)
    {
        Log::info('AnnouncementController@sendNotification called', ['id' => $id]);
        
        $announcement = Announcement::findOrFail($id);
        
        if (!$announcement->is_published) {
            return back()->with('error', 'Cannot send notification for unpublished announcement.');
        }
        
        $this->sendAnnouncementNotification($announcement);
        
        return back()->with('success', 'Notification sent successfully to all target users!');
    }

    /**
     * Notify event attendees specifically about cancellation
     */
    protected function notifyEventAttendees($event, $announcement, $request)
    {
        // Check if registrations method exists
        if (!method_exists($event, 'registrations')) {
            return;
        }
        
        $attendees = $event->registrations()
            ->whereIn('status', ['confirmed', 'pending'])
            ->with('user')
            ->get()
            ->pluck('user')
            ->filter();
        
        if ($attendees->isEmpty()) {
            return;
        }
        
        $notification = Notification::create([
            'title' => "🚫 EVENT CANCELLED: {$event->title}",
            'message' => "The event you registered for has been cancelled. Reason: {$request->cancellation_reason}",
            'type' => 'alert',
            'priority' => 2,
            'action_url' => route('announcements.show', $announcement->id),
            'action_text' => 'View Details',
            'data' => [
                'event_id' => $event->id,
                'event_title' => $event->title,
                'cancellation_reason' => $request->cancellation_reason,
                'announcement_id' => $announcement->id
            ],
            'created_by' => Auth::id(),
        ]);
        
        foreach ($attendees as $user) {
            $notification->users()->attach($user->id, [
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    public function statistics()
    {
        Log::info('AnnouncementController@statistics called');
        
        $totalAnnouncements = Announcement::count();
        $publishedAnnouncements = Announcement::where('is_published', true)->count();
        
        $activeAnnouncements = Announcement::where('is_published', true)
            ->where(function($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })->count();
        
        $totalViews = Announcement::sum('views');
        
        $byType = Announcement::select('type', DB::raw('count(*) as count'), DB::raw('sum(views) as total_views'))
            ->groupBy('type')
            ->orderBy('count', 'desc')
            ->get();
        
        $byAudience = Announcement::select('audience', DB::raw('count(*) as count'))
            ->groupBy('audience')
            ->orderBy('count', 'desc')
            ->get();
        
        $mostViewed = Announcement::where('is_published', true)
            ->orderBy('views', 'desc')
            ->limit(10)
            ->get();
        
        $recentAnnouncements = Announcement::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        $monthlyTrend = Announcement::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw("COUNT(*) as count"),
                DB::raw("SUM(views) as total_views")
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        return view('announcements.statistics', compact(
            'totalAnnouncements',
            'publishedAnnouncements',
            'activeAnnouncements',
            'totalViews',
            'byType',
            'byAudience',
            'mostViewed',
            'recentAnnouncements',
            'monthlyTrend'
        ));
    }

    private function sendAnnouncementNotification($announcement, $request = null)
    {
        try {
            $users = $this->getTargetUsersForAnnouncement($announcement);
            
            if ($users->isEmpty()) {
                Log::warning('No target users found for announcement notification', [
                    'announcement_id' => $announcement->id,
                    'audience' => $announcement->audience
                ]);
                
                $this->createNotificationRecord($announcement, collect([]), $request);
                return;
            }
            
            $priority = $request->notification_priority ?? ($announcement->type == 'urgent' ? 'urgent' : 'normal');
            $notificationType = $request->notification_type ?? 'announcement';
            
            $priorityMap = [
                'normal' => 0,
                'high' => 1,
                'urgent' => 2
            ];
            $priorityValue = $priorityMap[$priority] ?? 0;
            
            $this->createNotificationRecord($announcement, $users, $request, $priorityValue, $notificationType);
            
            Log::info('Announcement notification sent', [
                'announcement_id' => $announcement->id,
                'users_count' => $users->count(),
                'priority' => $priority,
                'type' => $notificationType,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to send announcement notification', [
                'announcement_id' => $announcement->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e;
        }
    }
    
    private function createNotificationRecord($announcement, $users, $request = null, $priority = 0, $notificationType = 'announcement')
    {
        $notificationData = [
            'title' => "📢 " . ($announcement->type == 'urgent' ? 'URGENT: ' : '') . $announcement->title,
            'message' => strip_tags(Str::limit($announcement->content, 200)),
            'type' => $notificationType,
            'action_url' => route('announcements.show', $announcement->id),
            'action_text' => 'View Announcement',
            'priority' => $priority,
            'data' => [
                'announcement_id' => $announcement->id,
                'announcement_type' => $announcement->type,
                'audience' => $announcement->audience,
                'source' => 'announcement',
                'published_at' => $announcement->published_at ? $announcement->published_at->toIso8601String() : null,
            ],
        ];
        
        if (\Schema::hasColumn('notifications', 'created_by')) {
            $notificationData['created_by'] = Auth::id();
        }
        
        $notification = Notification::create($notificationData);
        
        if ($users->isNotEmpty()) {
            $userNotificationsData = [];
            foreach ($users as $user) {
                $userNotificationsData[$user->id] = [
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            $notification->users()->attach($userNotificationsData);
            
            Log::info('Notification attached to users', [
                'notification_id' => $notification->id,
                'users_count' => $users->count()
            ]);
        }
        
        return $notification;
    }

    private function getTargetUsersForAnnouncement($announcement)
    {
        $query = User::where(function($q) {
            $q->where('is_active', true)
              ->orWhereNull('is_active');
        });
        
        switch ($announcement->audience) {
            case 'all':
                break;
                
            case 'students':
                $query->whereHas('role', function($q) {
                    $q->where('slug', 'student');
                });
                break;
                
            case 'faculty':
                $query->whereHas('role', function($q) {
                    $q->where('slug', 'faculty');
                });
                break;
                
            case 'staff':
                $query->whereHas('role', function($q) {
                    $q->where('slug', 'staff');
                });
                break;

            case 'event_managers':
                $query->whereHas('role', function($q) {
                    $q->whereIn('slug', ['event-manager', 'event_manager', 'eventmanager']);
                });
                break;
                
            case 'specific':
                if ($announcement->target_ids && is_array($announcement->target_ids)) {
                    $query->whereIn('id', $announcement->target_ids);
                } else {
                    return collect();
                }
                break;
                
            default:
                return collect();
        }
        
        return $query->get();
    }

    private function canUserViewAnnouncement(Announcement $announcement, User $user): bool
    {
        if ($announcement->audience === 'all') {
            return true;
        }

        if ($announcement->audience === 'specific') {
            return is_array($announcement->target_ids) && in_array($user->id, $announcement->target_ids);
        }

        $userAudienceKeys = $this->getUserAudienceKeys($user);
        return in_array($announcement->audience, $userAudienceKeys, true);
    }

    private function getUserAudienceKeys(User $user): array
    {
        $roleSlug = $user->role?->slug;
        $expertise = $user->expertise ?? null;

        $keys = array_filter([
            $roleSlug,
            $expertise,
        ]);

        $normalized = [];
        foreach ($keys as $key) {
            $normalized[] = $key;
            if ($key === 'student') {
                $normalized[] = 'students';
            }
            if ($key === 'students') {
                $normalized[] = 'student';
            }
            if (in_array($key, ['event-manager', 'event_manager', 'eventmanager'], true)) {
                $normalized[] = 'event_managers';
            }
            if ($key === 'event_managers') {
                $normalized[] = 'event-manager';
            }
        }

        return array_values(array_unique($normalized));
    }

    private function isAnnouncementAdmin(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        $roleSlug = $user->role?->slug;

        return in_array($roleSlug, ['super-admin', 'super-administrator', 'administrator', 'admin'], true);
    }

    private function getAnnouncementStats()
    {
        $totalAnnouncements = Announcement::count();
        $publishedAnnouncements = Announcement::where('is_published', true)->count();
        
        $activeAnnouncements = Announcement::where('is_published', true)
            ->where(function($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })->count();
        
        $totalViews = Announcement::sum('views');
        
        return [
            'totalAnnouncements' => $totalAnnouncements,
            'publishedAnnouncements' => $publishedAnnouncements,
            'activeAnnouncements' => $activeAnnouncements,
            'totalViews' => $totalViews,
        ];
    }
}
