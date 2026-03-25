<?php
// app/Http/Controllers/EventController.php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Campus;
use App\Models\Building;
use App\Models\Venue;
use App\Models\Speaker;
use App\Models\Announcement;
use App\Models\Notification;
use App\Models\User;
use App\Models\EventRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Event::query()->with(['campusRelation', 'buildingRelation', 'venueRelation', 'speakers']);
        
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        
        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }
        
        if ($request->filled('campus_id')) {
            $query->where('campus_id', $request->campus_id);
        }
        
        if ($request->filled('status')) {
            if ($request->status == 'upcoming') {
                $query->upcoming();
            } elseif ($request->status == 'past') {
                $query->past();
            } elseif ($request->status == 'ongoing') {
                $query->ongoing();
            } elseif ($request->status == 'cancelled') {
                $query->cancelled();
            }
        }
        
        if ($request->filled('featured') && $request->featured == '1') {
            $query->featured();
        }
        
        if ($request->filled('speaker_id')) {
            $query->whereHas('speakers', function ($q) use ($request) {
                $q->where('speakers.id', $request->speaker_id);
            });
        }
        
        if (!$request->filled('status')) {
            $query->where('end_date', '>=', now()->subDays(1));
        }
        
        $sort = $request->get('sort', 'start_date');
        $order = $request->get('order', 'asc');
        $query->orderBy($sort, $order);
        
        $events = $query->paginate(20)->withQueryString();
        
        $totalCount = Event::count();
        $upcomingCount = Event::upcoming()->count();
        $ongoingCount = Event::ongoing()->count();
        $completedCount = Event::past()->count();
        $cancelledCount = Event::cancelled()->count();
        $featuredCount = Event::featured()->count();
        
        $campuses = Campus::active()->get();
        $buildings = Building::active()->get();
        $venues = Venue::available()->get();
        $speakers = Speaker::active()->orderBy('name')->get();
        
        return view('admin.events.index', compact(
            'events', 
            'totalCount', 
            'upcomingCount', 
            'ongoingCount', 
            'completedCount',
            'cancelledCount',
            'featuredCount',
            'campuses',
            'buildings',
            'venues',
            'speakers'
        ));
    }

    public function create()
    {
        $campuses = Campus::active()->get();
        $buildings = Building::active()->get();
        $venues = Venue::available()->get();
        $speakers = Speaker::active()->orderBy('name')->get();
        
        $eventTypes = [
            'academic' => 'Academic',
            'cultural' => 'Cultural',
            'sports' => 'Sports',
            'conference' => 'Conference',
            'workshop' => 'Workshop',
            'seminar' => 'Seminar',
            'exhibition' => 'Exhibition',
            'outreach' => 'Outreach',
        ];
        
        return view('admin.events.create', compact('campuses', 'buildings', 'venues', 'speakers', 'eventTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            //'short_description' => 'nullable|string|max:200',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'campus_id' => 'nullable|exists:campuses,id',
            'building_id' => 'nullable|exists:buildings,id',
            'venue_id' => 'nullable|exists:venues,id',
            'campus' => 'nullable|string|max:255',
            'building' => 'nullable|string|max:255',
            'venue' => 'nullable|string|max:255',
            'event_type' => 'required|string|in:academic,cultural,sports,conference,workshop,seminar,exhibition,outreach',
            'organizer' => 'required|string|max:255',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
            'max_attendees' => 'nullable|integer|min:1',
            'is_featured' => 'boolean',
            'is_public' => 'boolean',
            'requires_registration' => 'boolean',
            'registration_link' => 'nullable|url|required_if:requires_registration,1',
            'tags' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'additional_venue_info' => 'nullable|array',
            'speakers' => 'nullable|array',
            'speakers.*.speaker_id' => 'nullable|exists:speakers,id',
            'speakers.*.session_title' => 'nullable|string|max:255',
            'speakers.*.session_time' => 'nullable|date',
            'speakers.*.session_duration' => 'nullable|integer|min:1',
            'speakers.*.session_description' => 'nullable|string',
            'speakers.*.is_keynote' => 'nullable|boolean',
        ]);

        // Generate slug
        $slug = Str::slug($validated['title']);
        $counter = 1;
        while (Event::where('slug', $slug)->exists()) {
            $slug = Str::slug($validated['title']) . '-' . $counter;
            $counter++;
        }
        $validated['slug'] = $slug;

        // Process tags
        if ($request->filled('tags')) {
            $tags = array_map('trim', explode(',', $request->tags));
            $validated['tags'] = array_slice($tags, 0, 10);
        }

        // Set text fields from relations if needed
        if ($request->filled('campus_id') && empty($validated['campus'])) {
            $campus = Campus::find($request->campus_id);
            $validated['campus'] = $campus ? $campus->name : null;
        }
        
        if ($request->filled('building_id') && empty($validated['building'])) {
            $building = Building::find($request->building_id);
            $validated['building'] = $building ? $building->name : null;
        }
        
        if ($request->filled('venue_id') && empty($validated['venue'])) {
            $venue = Venue::find($request->venue_id);
            $validated['venue'] = $venue ? $venue->name : null;
        }

        $validated['is_cancelled'] = false;

        // Event managers submit events for approval instead of publishing directly.
        if (auth()->user()->hasRole('event-manager')) {
            $imagePath = null;

            if ($request->hasFile('image')) {
                try {
                    $imagePath = $request->file('image')->store('events', 'public');
                } catch (\Exception $e) {
                    Log::error('Event request image upload failed: ' . $e->getMessage());
                }
            }

            $this->createApprovalRequestFromEventData($validated, $imagePath);

            return redirect()->route('event-requests.my-requests')
                ->with('success', 'Event submitted for approval successfully. It is now pending administrator review.');
        }
        
        // Create event WITHOUT image first
        $event = Event::create($validated);

        // Handle image upload AFTER event is created
        if ($request->hasFile('image')) {
            try {
                $event->uploadImage($request->file('image'));
                Log::info('Image uploaded for new event: ' . $event->id);
            } catch (\Exception $e) {
                Log::error('Image upload failed: ' . $e->getMessage());
            }
        }

        // Handle speakers
        if ($request->has('speakers') && is_array($request->speakers)) {
            $speakerData = array_filter($request->speakers, function ($speaker) {
                return !empty($speaker['speaker_id']);
            });
            
            if (!empty($speakerData)) {
                $event->syncSpeakers($speakerData);
            }
        }

        $this->sendEventLifecycleNotification($event, 'created');

        return redirect()->route('admin.events.show', $event)
            ->with('success', 'Event created successfully!');
    }

    public function show(Event $event)
    {
        $event->load(['campusRelation', 'buildingRelation', 'venueRelation', 'speakers', 'cancelledBy']);
        
        // Log image info for debugging
        if ($event->image) {
            Log::info('Showing event ' . $event->id . ' with image: ' . $event->image);
        }
        
        return view('admin.events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        $event->load(['campusRelation', 'buildingRelation', 'venueRelation', 'speakers']);
        $campuses = Campus::active()->get();
        $buildings = Building::active()->where('campus_id', $event->campus_id)->get();
        $venues = Venue::available()->where('building_id', $event->building_id)->get();
        $speakers = Speaker::active()->orderBy('name')->get();
        
        $assignedSpeakerIds = $event->speakers->pluck('id')->toArray();
        
        $eventTypes = [
            'academic' => 'Academic',
            'cultural' => 'Cultural',
            'sports' => 'Sports',
            'conference' => 'Conference',
            'workshop' => 'Workshop',
            'seminar' => 'Seminar',
            'exhibition' => 'Exhibition',
            'outreach' => 'Outreach',
        ];
        
        return view('admin.events.edit', compact(
            'event', 
            'campuses', 
            'buildings', 
            'venues', 
            'speakers',
            'assignedSpeakerIds',
            'eventTypes'
        ));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:200',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'campus_id' => 'nullable|exists:campuses,id',
            'building_id' => 'nullable|exists:buildings,id',
            'venue_id' => 'nullable|exists:venues,id',
            'campus' => 'nullable|string|max:255',
            'building' => 'nullable|string|max:255',
            'venue' => 'nullable|string|max:255',
            'event_type' => 'required|string|in:academic,cultural,sports,conference,workshop,seminar,exhibition,outreach',
            'organizer' => 'required|string|max:255',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
            'max_attendees' => 'nullable|integer|min:1',
            'is_featured' => 'boolean',
            'is_public' => 'boolean',
            'requires_registration' => 'boolean',
            'registration_link' => 'nullable|url|required_if:requires_registration,1',
            'tags' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'additional_venue_info' => 'nullable|array',
            'remove_image' => 'nullable|boolean',
            'speakers' => 'nullable|array',
            'speakers.*.speaker_id' => 'nullable|exists:speakers,id',
            'speakers.*.session_title' => 'nullable|string|max:255',
            'speakers.*.session_time' => 'nullable|date',
            'speakers.*.session_duration' => 'nullable|integer|min:1',
            'speakers.*.session_description' => 'nullable|string',
            'speakers.*.is_keynote' => 'nullable|boolean',
        ]);

        // Handle slug update if title changed
        if ($event->title !== $validated['title']) {
            $slug = Str::slug($validated['title']);
            $counter = 1;
            while (Event::where('slug', $slug)->where('id', '!=', $event->id)->exists()) {
                $slug = Str::slug($validated['title']) . '-' . $counter;
                $counter++;
            }
            $validated['slug'] = $slug;
        }

        // Process tags
        if ($request->filled('tags')) {
            $tags = array_map('trim', explode(',', $request->tags));
            $validated['tags'] = array_slice($tags, 0, 10);
        } else {
            $validated['tags'] = null;
        }

        // Handle image removal
        if ($request->has('remove_image') && $request->remove_image) {
            $event->deleteImage();
            // Don't set image in validated data, it will be null after deleteImage()
        }

        // Handle new image upload
        if ($request->hasFile('image')) {
            try {
                $event->uploadImage($request->file('image'));
                Log::info('Image updated for event: ' . $event->id);
            } catch (\Exception $e) {
                Log::error('Image update failed: ' . $e->getMessage());
            }
        }

        // Set text fields from relations if needed
        if ($request->filled('campus_id') && empty($validated['campus'])) {
            $campus = Campus::find($request->campus_id);
            $validated['campus'] = $campus ? $campus->name : null;
        }
        
        if ($request->filled('building_id') && empty($validated['building'])) {
            $building = Building::find($request->building_id);
            $validated['building'] = $building ? $building->name : null;
        }
        
        if ($request->filled('venue_id') && empty($validated['venue'])) {
            $venue = Venue::find($request->venue_id);
            $validated['venue'] = $venue ? $venue->name : null;
        }

        // Update the event
        $event->update($validated);

        // Handle speakers
        if ($request->has('speakers') && is_array($request->speakers)) {
            $speakerData = array_filter($request->speakers, function ($speaker) {
                return !empty($speaker['speaker_id']);
            });
            
            if (!empty($speakerData)) {
                $event->syncSpeakers($speakerData);
            } else {
                $event->speakers()->detach();
            }
        } else {
            $event->speakers()->detach();
        }

        $this->sendEventLifecycleNotification($event, 'updated');

        return redirect()->route('admin.events.show', $event)
            ->with('success', 'Event updated successfully!');
    }

    public function destroy(Event $event)
    {
        $event->deleteImage();
        $event->speakers()->detach();
        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event deleted successfully.');
    }

    /**
     * Cancellation Methods
     */
    public function showCancellationForm(Event $event)
    {
        if ($event->is_cancelled) {
            return redirect()->route('admin.events.show', $event)
                ->with('error', 'This event is already cancelled.');
        }
        
        return view('admin.events.cancel', compact('event'));
    }

    public function cancel(Request $request, Event $event)
    {
        if ($event->is_cancelled) {
            return redirect()->route('admin.events.show', $event)
                ->with('error', 'This event is already cancelled.');
        }

        $request->validate([
            'cancellation_reason' => 'required|string|max:1000',
            'additional_message' => 'nullable|string|max:500',
            'send_announcement' => 'boolean',
            'audience' => 'required_if:send_announcement,1|in:all,registered_only',
        ]);

        // Cancel the event
        $event->cancel($request->cancellation_reason);

        // Always notify joined participants after cancellation.
        $this->sendEventLifecycleNotification($event, 'cancelled', $request->cancellation_reason);

        $redirectUrl = auth()->user()->hasRole('event-manager')
            ? route('event-requests.my-requests')
            : route('admin.events.show', $event);

        // Create cancellation announcement if requested
        if ($request->boolean('send_announcement', true)) {
            $this->createCancellationAnnouncement($event, $request);

            return redirect()->to($redirectUrl)
                ->with('success', 'Event cancelled successfully. Cancellation announcement created and sent to attendees.');
        }

        return redirect()->to($redirectUrl)
            ->with('success', 'Event cancelled successfully.');
    }

    public function uncancel(Event $event)
    {
        if (!$event->is_cancelled) {
            return redirect()->route('admin.events.show', $event)
                ->with('error', 'This event is not cancelled.');
        }

        $event->uncancel();

        return redirect()->route('admin.events.show', $event)
            ->with('success', 'Event restored successfully.');
    }

    protected function createCancellationAnnouncement($event, $request)
    {
        $title = "CANCELLED: {$event->title}";
        
        $content = "<div class='cancellation-notice' style='padding: 20px; background: #fff3f3; border-left: 4px solid #dc3545; margin-bottom: 20px;'>";
        $content .= "<h2 style='color: #dc3545;'><i class='fas fa-exclamation-triangle'></i> Event Cancellation Notice</h2>";
        $content .= "<p>We regret to inform you that the following event has been cancelled:</p>";
        $content .= "<h3>{$event->title}</h3>";
        $content .= "<table style='width: 100%; margin: 15px 0; border-collapse: collapse;'>";
        $content .= "<tr><td style='padding: 8px; background: #f8f9fa;'><strong>Original Date:</strong></td><td style='padding: 8px;'>{$event->start_date->format('l, F j, Y \a\t g:i A')}</td></tr>";
        $content .= "<tr><td style='padding: 8px; background: #f8f9fa;'><strong>Location:</strong></td><td style='padding: 8px;'>{$event->venue_name}, {$event->campus_name}</td></tr>";
        $content .= "<tr><td style='padding: 8px; background: #f8f9fa;'><strong>Organizer:</strong></td><td style='padding: 8px;'>{$event->organizer}</td></tr>";
        $content .= "</table>";
        
        $content .= "<div class='alert alert-warning' style='padding: 15px; background: #fff3cd; border: 1px solid #ffeeba; border-radius: 5px; margin: 15px 0;'>";
        $content .= "<strong>Reason for cancellation:</strong><br>";
        $content .= "<p style='margin-top: 10px;'>{$request->cancellation_reason}</p>";
        $content .= "</div>";
        
        if ($request->filled('additional_message')) {
            $content .= "<div style='margin: 15px 0; padding: 15px; background: #e7f3ff; border-left: 4px solid #0a2c6e;'>";
            $content .= "<strong>Additional Information:</strong><br>";
            $content .= "<p style='margin-top: 10px;'>{$request->additional_message}</p>";
            $content .= "</div>";
        }
        
        $content .= "<p>We apologize for any inconvenience this cancellation may cause. If you have any questions, please contact the organizer:</p>";
        $content .= "<p><strong>Email:</strong> <a href='mailto:{$event->contact_email}'>{$event->contact_email}</a><br>";
        if ($event->contact_phone) {
            $content .= "<strong>Phone:</strong> <a href='tel:{$event->contact_phone}'>{$event->contact_phone}</a>";
        }
        $content .= "</p>";
        $content .= "</div>";

        $announcement = Announcement::create([
            'title' => $title,
            'content' => $content,
            'type' => 'urgent',
            'audience' => $request->audience == 'registered_only' ? 'specific' : 'all',
            'target_ids' => $request->audience == 'registered_only' 
                ? $event->registrations()->whereIn('status', ['confirmed', 'pending'])->pluck('user_id')->toArray()
                : null,
            'created_by' => auth()->id(),
            'is_published' => true,
            'published_at' => now(),
        ]);

        return $announcement;
    }

    protected function sendCancellationNotifications($event, $announcement, $request)
    {
        $users = [];
        
        if ($request->audience == 'registered_only') {
            $users = $event->registrations()
                ->whereIn('status', ['confirmed', 'pending'])
                ->with('user')
                ->get()
                ->pluck('user');
        } else {
            $users = User::where('is_active', true)->get();
        }

        if ($users->isEmpty()) {
            return;
        }

        $notification = Notification::create([
            'title' => "🚫 EVENT CANCELLED: {$event->title}",
            'message' => "The event '{$event->title}' has been cancelled. " . ($request->cancellation_reason ? "Reason: {$request->cancellation_reason}" : ""),
            'type' => 'alert',
            'priority' => 2,
            'action_url' => route('announcements.show', $announcement->id),
            'action_text' => 'View Cancellation Details',
            'data' => [
                'event_id' => $event->id,
                'event_title' => $event->title,
                'cancellation_reason' => $request->cancellation_reason,
                'announcement_id' => $announcement->id
            ],
            'created_by' => auth()->id(),
        ]);

        foreach ($users as $user) {
            $notification->users()->attach($user->id, [
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    protected function sendEventLifecycleNotification(Event $event, string $action, ?string $reason = null): void
    {
        $eventUrl = route('events.guest.show', $event->slug);
        $title = match ($action) {
            'created' => "New Event Is Created: {$event->title}",
            'cancelled' => "Event Is Canceled: {$event->title}",
            default => "Event Is Updated: {$event->title}",
        };

        $message = match ($action) {
            'created' => "A new event has been approved and published.",
            'cancelled' => "An event you joined was canceled." . ($reason ? " Reason: {$reason}" : ''),
            default => "An event you joined has been updated.",
        };

        $notification = Notification::create([
            'title' => $title,
            'message' => $message,
            'type' => 'event',
            'priority' => 1,
            'action_url' => $eventUrl,
            'action_text' => 'View Event',
            'data' => [
                'source' => 'event',
                'event_action' => $action,
                'event_id' => $event->id,
                'event_slug' => $event->slug,
                'reason' => $reason,
            ],
            'created_by' => auth()->id(),
        ]);

        if ($action === 'created') {
            $users = User::where(function ($query) {
                    $query->whereHas('role', function ($roleQuery) {
                        $roleQuery->whereIn('slug', ['faculty']);
                    })
                    ->orWhere('expertise', 'faculty')
                    ->orWhere(function ($activeQuery) {
                        $activeQuery->where('status', 'active')
                            ->orWhereNull('status')
                            ->orWhere('is_active', true)
                            ->orWhereNull('is_active');
                    });
                })
                ->where('id', '!=', auth()->id())
                ->get(['id']);
        } else {
            $users = User::whereIn(
                    'id',
                    $event->registrations()
                        ->whereIn('status', ['confirmed', 'pending'])
                        ->pluck('user_id')
                )
                ->where('id', '!=', auth()->id())
                ->get(['id']);
        }

        if ($users->isEmpty()) {
            return;
        }

        $attachData = [];
        foreach ($users as $user) {
            $attachData[$user->id] = [
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $notification->users()->attach($attachData);
    }

    protected function createApprovalRequestFromEventData(array $validated, ?string $imagePath = null): EventRequest
    {
        $additionalRequirements = [];

        if (!empty($validated['additional_venue_info'])) {
            $additionalRequirements['additional_venue_info'] = $validated['additional_venue_info'];
        }

        if (!empty($imagePath)) {
            $additionalRequirements['event_image'] = $imagePath;
        }

        return EventRequest::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'proposed_start_date' => $validated['start_date'],
            'proposed_end_date' => $validated['end_date'],
            'proposed_venue' => $validated['venue'] ?? '',
            'proposed_campus' => $validated['campus'] ?? null,
            'venue_id' => $validated['venue_id'] ?? null,
            'campus_id' => $validated['campus_id'] ?? null,
            'building_id' => $validated['building_id'] ?? null,
            'event_type' => $validated['event_type'],
            'organizer_name' => $validated['organizer'],
            'organizer_email' => $validated['contact_email'] ?? auth()->user()->email,
            'organizer_phone' => $validated['contact_phone'] ?? null,
            'expected_attendees' => $validated['max_attendees'] ?? null,
            'additional_requirements' => !empty($additionalRequirements)
                ? json_encode($additionalRequirements)
                : null,
            'status' => 'pending',
        ]);
    }

    /**
     * Toggle Methods
     */
    public function toggleFeatured(Event $event)
    {
        $event->toggleFeatured();
        
        return redirect()->back()
            ->with('success', 'Featured status updated successfully.');
    }

    public function togglePublic(Event $event)
    {
        $event->togglePublic();
        
        return redirect()->back()
            ->with('success', 'Visibility status updated successfully.');
    }

    /**
     * AJAX Methods
     */
    public function getBuildings($campusId)
    {
        $buildings = Building::where('campus_id', $campusId)
                            ->where('is_active', true)
                            ->get(['id', 'name', 'code']);
        
        return response()->json($buildings);
    }

    public function getVenues($buildingId)
    {
        $venues = Venue::where('building_id', $buildingId)
                      ->where('is_available', true)
                      ->get(['id', 'name', 'type', 'capacity']);
        
        return response()->json($venues);
    }

    public function getVenueDetails($venueId)
    {
        $venue = Venue::find($venueId);
        
        if (!$venue) {
            return response()->json(['error' => 'Venue not found'], 404);
        }
        
        return response()->json([
            'name' => $venue->name,
            'type' => $venue->type,
            'capacity' => $venue->capacity,
            'description' => $venue->description,
            'amenities' => $venue->amenities ?? []
        ]);
    }

    public function getSpeakers()
    {
        $speakers = Speaker::active()->orderBy('name')->get(['id', 'name', 'title', 'organization']);
        return response()->json($speakers);
    }

    public function getSpeakerDetails($speakerId)
    {
        $speaker = Speaker::find($speakerId);
        
        if (!$speaker) {
            return response()->json(['error' => 'Speaker not found'], 404);
        }
        
        return response()->json($speaker);
    }

    /**
     * Duplicate and Export
     */
    public function duplicate(Event $event)
    {
        $newEvent = $event->replicate();
        $newEvent->title = $event->title . ' (Copy)';
        $newEvent->slug = Str::slug($newEvent->title);
        $newEvent->is_featured = false;
        $newEvent->is_cancelled = false;
        $newEvent->cancelled_at = null;
        $newEvent->cancellation_reason = null;
        $newEvent->cancelled_by = null;
        $newEvent->registered_attendees = 0;
        $newEvent->image = null;
        
        $newEvent->save();

        $speakerData = [];
        foreach ($event->speakers as $speaker) {
            $speakerData[] = [
                'speaker_id' => $speaker->id,
                'session_title' => $speaker->pivot->session_title,
                'session_time' => $speaker->pivot->session_time,
                'session_duration' => $speaker->pivot->session_duration,
                'session_description' => $speaker->pivot->session_description,
                'order' => $speaker->pivot->order,
                'is_keynote' => $speaker->pivot->is_keynote,
                'is_moderator' => $speaker->pivot->is_moderator,
                'is_panelist' => $speaker->pivot->is_panelist,
            ];
        }
        
        if (!empty($speakerData)) {
            $newEvent->syncSpeakers($speakerData);
        }

        return redirect()->route('admin.events.edit', $newEvent)
            ->with('success', 'Event duplicated successfully. You can now edit the copy.');
    }

    public function export(Request $request)
    {
        $events = Event::with('speakers')->get();
        
        $csvData = "Title,Description,Start Date,End Date,Campus,Building,Venue,Type,Organizer,Speakers,Max Attendees,Status,Is Cancelled\n";
        
        foreach ($events as $event) {
            $speakerNames = $event->speakers->pluck('name')->implode('; ');
            
            $csvData .= '"' . str_replace('"', '""', $event->title) . '",';
            $csvData .= '"' . str_replace('"', '""', $event->short_description ?: $event->description) . '",';
            $csvData .= $event->start_date->format('Y-m-d H:i') . ',';
            $csvData .= $event->end_date->format('Y-m-d H:i') . ',';
            $csvData .= $event->campus_name . ',';
            $csvData .= $event->building_name . ',';
            $csvData .= $event->venue_name . ',';
            $csvData .= ucfirst($event->event_type) . ',';
            $csvData .= $event->organizer . ',';
            $csvData .= '"' . $speakerNames . '",';
            $csvData .= ($event->max_attendees ?: 'Unlimited') . ',';
            $csvData .= ucfirst($event->cancellation_status) . ',';
            $csvData .= ($event->is_cancelled ? 'Yes' : 'No') . "\n";
        }
        
        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="events_' . date('Y-m-d') . '.csv"');
    }
}
