<?php
// app/Http/Controllers/DashboardController.php - ENHANCE WITH REAL DATA
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\EventRequest;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:view_dashboard');
    }

    public function index()
    {
        // REAL STATS FROM YOUR DATABASE
        $stats = [
            'total_users' => User::count(),
            'total_roles' => \App\Models\Role::count(),
            'total_permissions' => \App\Models\Permission::count(),
            'active_sessions' => User::where('updated_at', '>=', now()->subMinutes(15))->count(),
            
            // ADD THESE REAL STATS
            'total_events' => Event::count(),
            'upcoming_events' => Event::where('start_date', '>', now())->count(),
            'total_registrations' => EventRegistration::count(),
            'pending_requests' => EventRequest::where('status', 'pending')->count(),
            'feedback_count' => Feedback::count(),
        ];

        // REAL RECENT USERS
        $recentUsers = User::with('role')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // REAL USER ROLE DISTRIBUTION
        $userRoleDistribution = \App\Models\Role::withCount('users')
            ->having('users_count', '>', 0)
            ->orderBy('users_count', 'desc')
            ->get();

        // REAL USER GROWTH DATA (Last 6 months)
        $userGrowthData = $this->getUserGrowthData();
        
        // REAL UPCOMING EVENTS
        $upcomingEvents = Event::where('start_date', '>', now())
            ->orderBy('start_date')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'stats', 
            'recentUsers', 
            'userRoleDistribution',
            'userGrowthData',
            'upcomingEvents'
        ));
    }

    private function getUserGrowthData()
    {
        $months = [];
        $monthlyUsers = [];
        $cumulative = [];
        $total = 0;
        
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months[] = $month->format('M');
            
            $count = User::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            
            $monthlyUsers[] = $count;
            $total += $count;
            $cumulative[] = $total;
        }
        
        return [
            'months' => $months,
            'monthly' => $monthlyUsers,
            'cumulative' => $cumulative
        ];
    }
}