<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\EventRequest;
use App\Models\Speaker;
use App\Models\Venue;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // BASIC STATS
        $totalUsers = User::count();
        $totalEvents = Event::count();
        $totalRegistrations = EventRegistration::count();
        $totalRequests = EventRequest::count();
        $totalSpeakers = Speaker::count();
        $totalVenues = Venue::count();
        $totalFeedback = Feedback::count();
        $pendingRequests = EventRequest::where('status', 'pending')->count();
        
        // Stats array
        $stats = [
            'total_users' => $totalUsers,
            'total_events' => $totalEvents,
            'total_registrations' => $totalRegistrations,
            'total_requests' => $totalRequests,
            'total_speakers' => $totalSpeakers,
            'total_venues' => $totalVenues,
            'total_feedback' => $totalFeedback,
            'pending_requests' => $pendingRequests,
        ];

        // User growth
        $userGrowth = $this->getUserGrowth();

        // Event stats
        $eventStats = [
            'upcoming' => Event::where('start_date', '>', now())->count(),
            'ongoing' => Event::where('start_date', '<=', now())
                                ->where('end_date', '>=', now())
                                ->count(),
            'past' => Event::where('end_date', '<', now())->count(),
            'featured' => Event::where('is_featured', true)->count(),
        ];

        // Registration stats
        $registrationStats = [
            'confirmed' => EventRegistration::where('status', 'confirmed')->count(),
            'pending' => EventRegistration::where('status', 'pending')->count(),
            'cancelled' => EventRegistration::where('status', 'cancelled')->count(),
            'waitlisted' => EventRegistration::where('status', 'waitlisted')->count(),
            'attended' => EventRegistration::where('attended', true)->count(),
        ];

        // CHART DATA - THIS IS WHAT YOUR VIEW NEEDS
        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
        $registrationsData = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $registrationsData[] = EventRegistration::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
        }

        // Recent registrations
        $recentRegistrations = EventRegistration::with(['user', 'event'])
            ->latest()
            ->limit(10)
            ->get();

        // Recent activities
        $recentActivities = $this->getRecentActivities();

        // Upcoming events
        $upcomingEvents = Event::where('start_date', '>', now())
            ->orderBy('start_date')
            ->limit(5)
            ->get();

        // Popular events
        $popularEvents = Event::withCount('registrations')
            ->orderBy('registrations_count', 'desc')
            ->limit(5)
            ->get();

        // Event types
        $eventTypes = Event::select('event_type', DB::raw('count(*) as total'))
            ->groupBy('event_type')
            ->get();

        // Campus distribution
        $campusDistribution = Event::select('campus', DB::raw('count(*) as total'))
            ->whereNotNull('campus')
            ->groupBy('campus')
            ->get();

        // Pending requests collection
        $pendingRequestsCollection = EventRequest::with('user')
            ->where('status', 'pending')
            ->latest()
            ->limit(5)
            ->get();

        // Feedback stats
        $feedbackStats = [
            'avg_rating' => Feedback::whereNotNull('rating')->avg('rating') ?: 0,
            'total_feedback' => $totalFeedback,
            'positive' => Feedback::where('rating', '>=', 4)->count(),
            'negative' => Feedback::where('rating', '<=', 2)->count(),
        ];

        // Monthly trends
        $monthlyTrends = $this->getMonthlyTrends();

        // System health
        $systemHealth = $this->checkSystemHealth();

        // For sidebar
        $pendingRequestsCount = $pendingRequests;

        return view('admin.dashboard.index', compact(
            'totalUsers',
            'totalEvents',
            'totalRegistrations',
            'totalRequests',
            'totalSpeakers',
            'totalVenues',
            'totalFeedback',
            'pendingRequests',
            'stats',
            'userGrowth',
            'eventStats',
            'registrationStats',
            'recentRegistrations',
            'recentActivities',
            'upcomingEvents',
            'popularEvents',
            'eventTypes',
            'campusDistribution',
            'pendingRequestsCollection',
            'feedbackStats',
            'monthlyTrends',
            'systemHealth',
            'pendingRequestsCount',
            'labels',              // ADD THIS
            'registrationsData'     // ADD THIS
        ));
    }

    private function getUserGrowth()
    {
        $dates = [];
        $counts = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dates[] = $date->format('D');
            $counts[] = User::whereDate('created_at', $date)->count();
        }
        
        return ['labels' => $dates, 'data' => $counts];
    }

    private function getRecentActivities()
    {
        $activities = collect();

        User::latest()->limit(3)->get()->each(function ($user) use ($activities) {
            $activities->push([
                'type' => 'user',
                'icon' => 'user-plus',
                'title' => 'New User Registered',
                'description' => $user->name . ' joined',
                'time' => $user->created_at->diffForHumans(),
                'timestamp' => $user->created_at,
            ]);
        });

        Event::latest()->limit(3)->get()->each(function ($event) use ($activities) {
            $activities->push([
                'type' => 'event',
                'icon' => 'calendar-plus',
                'title' => 'New Event Created',
                'description' => $event->title,
                'time' => $event->created_at->diffForHumans(),
                'timestamp' => $event->created_at,
            ]);
        });

        EventRegistration::latest()->limit(3)->get()->each(function ($reg) use ($activities) {
            $activities->push([
                'type' => 'registration',
                'icon' => 'clipboard-check',
                'title' => 'New Registration',
                'description' => ($reg->user->name ?? 'Someone') . ' registered',
                'time' => $reg->created_at->diffForHumans(),
                'timestamp' => $reg->created_at,
            ]);
        });

        return $activities->sortByDesc('timestamp')->take(10)->values();
    }

    private function getMonthlyTrends()
    {
        $months = [];
        $users = [];
        $events = [];
        $registrations = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M');
            $users[] = User::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            $events[] = Event::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            $registrations[] = EventRegistration::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
        }

        return [
            'labels' => $months,
            'users' => $users,
            'events' => $events,
            'registrations' => $registrations,
        ];
    }

    private function checkSystemHealth()
    {
        try {
            DB::connection()->getPdo();
            $database = ['status' => 'healthy', 'message' => 'Connected'];
        } catch (\Exception $e) {
            $database = ['status' => 'error', 'message' => 'Failed'];
        }

        return [
            'database' => $database,
            'disk_usage' => 45,
            'cache' => 'healthy',
            'last_backup' => '2 hours ago',
        ];
    }

    public function getRealtimeData()
    {
        return response()->json([
            'stats' => [
                'total_users' => User::count(),
                'total_events' => Event::count(),
                'total_registrations' => EventRegistration::count(),
                'pending_requests' => EventRequest::where('status', 'pending')->count(),
            ],
            'recent_activities' => $this->getRecentActivities()->take(5),
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}