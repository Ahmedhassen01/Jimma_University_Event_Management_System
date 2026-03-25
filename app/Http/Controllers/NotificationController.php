<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Auth::user()->notifications()
            ->with('creator')
            ->orderBy('user_notifications.created_at', 'desc');
        
        // Apply filters
        if ($request->filled('status')) {
            if ($request->status == 'unread') {
                $query->whereNull('user_notifications.read_at');
            } elseif ($request->status == 'read') {
                $query->whereNotNull('user_notifications.read_at');
            }
        }
        
        if ($request->filled('type') && $request->type != 'all') {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('period')) {
            $date = now();
            switch ($request->period) {
                case 'today':
                    $date = $date->subDay();
                    break;
                case 'week':
                    $date = $date->subWeek();
                    break;
                case 'month':
                    $date = $date->subMonth();
                    break;
                case 'year':
                    $date = $date->subYear();
                    break;
            }
            $query->where('user_notifications.created_at', '>=', $date);
        }
        
        $notifications = $query->paginate(20);
        $unreadCount = Auth::user()->unread_notifications_count;
        
        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    public function markAsRead($id)
    {
        $userNotification = UserNotification::where('user_id', Auth::id())
            ->where('notification_id', $id)
            ->firstOrFail();
        
        $userNotification->markAsRead();
        
        return response()->json([
            'success' => true,
            'unread_count' => Auth::user()->unread_notifications_count
        ]);
    }

    public function markAllAsRead()
    {
        UserNotification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'unread_count' => Auth::user()->fresh()->unread_notifications_count,
            ]);
        }

        return back()->with('success', 'All notifications marked as read.');
    }

    public function dismiss($id)
    {
        $userNotification = UserNotification::where('user_id', Auth::id())
            ->where('notification_id', $id)
            ->firstOrFail();
        
        $userNotification->dismiss();
        
        return response()->json(['success' => true]);
    }

    public function clearAll()
    {
        UserNotification::where('user_id', Auth::id())->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'unread_count' => 0,
            ]);
        }

        return back()->with('success', 'All notifications cleared.');
    }
    
    public function recent(Request $request): JsonResponse
    {
        $showAll = $request->boolean('all', false);

        $baseQuery = UserNotification::query()
            ->with('notification.creator')
            ->where('user_id', Auth::id())
            ->whereNull('dismissed_at')
            ->orderByDesc('created_at');

        $totalCount = (clone $baseQuery)->count();

        $userNotifications = $baseQuery
            ->when(!$showAll, fn($q) => $q->limit(3))
            ->get();

        $notifications = $userNotifications
            ->map(function ($userNotification) {
                $notification = $userNotification->notification;
                if (!$notification) {
                    return null;
                }

                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'excerpt' => Str::limit($notification->message, 100),
                    'type' => $notification->type,
                    'type_color' => $notification->type_color,
                    'type_icon' => $notification->type_icon,
                    'action_url' => $notification->action_url,
                    'open_url' => route('notifications.open', $notification->id),
                    'priority' => $notification->priority,
                    'pivot' => [
                        'read_at' => $userNotification->read_at,
                        'created_at' => optional($userNotification->created_at)->diffForHumans(),
                    ],
                ];
            })
            ->filter()
            ->values();
        
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => Auth::user()->unread_notifications_count,
            'total_count' => $totalCount,
            'showing_all' => $showAll,
            'has_more' => !$showAll && $totalCount > 3,
        ]);
    }

    public function open($id)
    {
        $userNotification = UserNotification::where('user_id', Auth::id())
            ->where('notification_id', $id)
            ->whereNull('dismissed_at')
            ->with('notification')
            ->first();

        if (!$userNotification) {
            return redirect()->route('home');
        }

        if (is_null($userNotification->read_at)) {
            $userNotification->update(['read_at' => now()]);
        }

        $targetUrl = $userNotification->notification?->action_url;

        // Consume notification after opening so it disappears for this user.
        $userNotification->delete();

        if (!empty($targetUrl)) {
            return redirect()->to($targetUrl);
        }

        return redirect()->route('home');
    }

    // Admin methods
    public function sent()
    {
        $this->authorize('manage_notifications');
        
        $notifications = Notification::with(['creator', 'users'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('notifications.sent', compact('notifications'));
    }

    public function sendCustom()
    {
        $this->authorize('manage_notifications');
        
        $users = User::where('is_active', true)->orWhereNull('is_active')->get();
        $roles = Role::all();
        
        return view('notifications.send-custom', compact('users', 'roles'));
    }

    public function sendCustomStore(Request $request)
    {
        $this->authorize('manage_notifications');
        
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:announcement,event,system,alert,info,warning,success',
            'target_type' => 'required|in:all_users,specific_users,role_based',
            'target_users' => 'required_if:target_type,specific_users|array',
            'target_users.*' => 'exists:users,id',
            'target_role' => 'required_if:target_type,role_based|exists:roles,id',
            'send_email' => 'boolean',
        ]);
        
        // Create notification
        $notification = Notification::create([
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type,
            'created_by' => Auth::id(),
            'action_url' => $request->action_url,
            'action_text' => $request->action_text,
            'priority' => $request->priority ?? 0,
            'data' => [
                'target_type' => $request->target_type,
                'custom_notification' => true,
            ],
        ]);
        
        // Determine target users
        $users = collect();
        
        if ($request->target_type === 'all_users') {
            $users = User::where('is_active', true)->orWhereNull('is_active')->get();
        } elseif ($request->target_type === 'specific_users') {
            $users = User::whereIn('id', $request->target_users)->get();
        } elseif ($request->target_type === 'role_based') {
            $users = User::where('role_id', $request->target_role)->get();
        }
        
        // Attach notification to users
        $userNotificationsData = [];
        foreach ($users as $user) {
            $userNotificationsData[$user->id] = [
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        $notification->users()->attach($userNotificationsData);
        
        // Send email if requested
        if ($request->send_email) {
            Log::info('Custom email notification sent to ' . $users->count() . ' users');
        }
        
        return redirect()->route('notifications.sent')
            ->with('success', 'Notification sent to ' . $users->count() . ' users successfully.');
    }

    public function statistics()
    {
        $this->authorize('manage_notifications');
        
        $totalNotifications = Notification::count();
        $totalSent = UserNotification::count();
        $totalRead = UserNotification::whereNotNull('read_at')->count();
        $totalUnread = UserNotification::whereNull('read_at')->count();
        
        // Notification types distribution
        $typeDistribution = Notification::select('type', DB::raw('COUNT(*) as count'))
            ->groupBy('type')
            ->get();
        
        // Monthly statistics
        $monthlyStats = UserNotification::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw("COUNT(*) as total"),
                DB::raw("SUM(CASE WHEN read_at IS NULL THEN 1 ELSE 0 END) as unread"),
                DB::raw("SUM(CASE WHEN read_at IS NOT NULL THEN 1 ELSE 0 END) as read")
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        // Top 10 most notified users
        $topUsers = UserNotification::select(
                'user_id',
                DB::raw('COUNT(*) as notification_count'),
                DB::raw('SUM(CASE WHEN read_at IS NULL THEN 1 ELSE 0 END) as unread_count')
            )
            ->with('user')
            ->groupBy('user_id')
            ->orderBy('notification_count', 'desc')
            ->limit(10)
            ->get();
        
        return view('notifications.statistics', compact(
            'totalNotifications',
            'totalSent',
            'totalRead',
            'totalUnread',
            'typeDistribution',
            'monthlyStats',
            'topUsers'
        ));
    }

    public function destroy($id)
    {
        $this->authorize('manage_notifications');
        
        $notification = Notification::findOrFail($id);
        $notification->delete();
        
        return back()->with('success', 'Notification deleted successfully.');
    }

    public function getUnreadCount()
{
    $unreadCount = Auth::user()->unread_notifications_count;
    
    return response()->json([
        'count' => $unreadCount,
        'message' => $unreadCount > 0 ? "You have {$unreadCount} unread notification(s)" : "No new notifications"
    ]);
}

}
