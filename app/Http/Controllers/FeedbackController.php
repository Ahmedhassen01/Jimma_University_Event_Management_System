<?php
// app/Http/Controllers/FeedbackController.php
namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\FeedbackCategory;
use App\Models\Event;
use App\Models\User;
use App\Services\FeedbackService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class FeedbackController extends Controller
{
    protected $feedbackService;

    public function __construct(FeedbackService $feedbackService)
    {
        $this->feedbackService = $feedbackService;
        // Apply auth middleware to admin methods only
        $this->middleware('auth')->only([
            'index', 'show', 'updateStatus', 'addResponse', 
            'togglePublic', 'toggleFeatured', 'analytics', 'export'
        ]);
    }

    // ========== PUBLIC ROUTES ==========
    public function create(Request $request)
    {
        $event = null;
        if ($request->filled('event_id')) {
            $event = Event::findOrFail($request->event_id);
        }

        return view('feedback.create', compact('event'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'allow_contact' => $request->boolean('allow_contact'),
            'is_public' => $request->boolean('is_public'),
        ]);

        $validated = $request->validate([
            'event_id' => 'nullable|exists:events,id',
            'type' => 'required|in:event,system,general,suggestion,complaint',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|min:10|max:2000',
            'rating' => 'nullable|integer|min:1|max:5',
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'allow_contact' => 'boolean',
            'is_public' => 'boolean',
        ]);
        
        if (Auth::check()) {
            $validated['user_id'] = Auth::id();
        }
        
        $feedback = $this->feedbackService->submitFeedback($validated);
        
        return redirect()->route('feedback.thankyou')
            ->with('success', 'Thank you for your feedback!');
    }

    public function thankyou()
    {
        return view('feedback.thankyou');
    }

    public function testimonials(Request $request)
    {
        $query = Feedback::public()->with('event', 'user');
        
        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }
        
        if ($request->filled('rating')) {
            $query->where('rating', '>=', $request->rating);
        }
        
        if ($request->filled('featured')) {
            $query->featured();
        }
        
        $testimonials = $query->orderBy('created_at', 'desc')->paginate(12);
        
        $averageRating = Feedback::public()->withRating()->avg('rating');
        $totalTestimonials = Feedback::public()->count();
        
        return view('feedback.testimonials', compact('testimonials', 'averageRating', 'totalTestimonials'));
    }

    // ========== ADMIN ROUTES ==========
    public function index(Request $request)
    {
        $user = auth()->user();

        if (!$user->hasPermission('view_feedback')) {
            abort(403, 'This action is unauthorized.');
        }

        $canManageAll = $this->canManageAllFeedback($user);
        $query = Feedback::with(['event', 'user', 'assignee', 'categories']);

        // Non-admin processors should only work on items assigned to them.
        if (!$canManageAll) {
            $query->where('assigned_to', $user->id);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }
        
        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }
        
        if ($request->filled('rating')) {
            $query->where('rating', '>=', $request->rating);
        }
        
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('subject', 'like', "%{$request->search}%")
                  ->orWhere('message', 'like', "%{$request->search}%")
                  ->orWhereHas('user', function ($q) use ($request) {
                      $q->where('name', 'like', "%{$request->search}%");
                  });
            });
        }
        
        $statsQuery = clone $query;
        $statistics = [
            'total' => (clone $statsQuery)->count(),
            'pending' => (clone $statsQuery)->where('status', 'pending')->count(),
            'reviewed' => (clone $statsQuery)->where('status', 'reviewed')->count(),
            'resolved' => (clone $statsQuery)->where('status', 'resolved')->count(),
        ];

        $feedbacks = $query->orderBy('created_at', 'desc')->paginate(20);
        
        $categories = FeedbackCategory::active()->ordered()->get();
        
        return view('feedback.index', compact('feedbacks', 'statistics', 'categories', 'canManageAll'));
    }

    public function show(Feedback $feedback)
    {
        $user = auth()->user();
        if (!$user->hasPermission('view_feedback')) {
            abort(403, 'This action is unauthorized.');
        }

        if (!$this->canManageAllFeedback($user) && (int) $feedback->assigned_to !== (int) $user->id) {
            abort(403, 'You are not assigned to this feedback.');
        }
        
        $feedback->load(['event', 'user', 'assignee', 'categories', 'responses.responder']);

        $canAssignFeedback = $this->canAssignFeedback($user);
        $assignableUsers = $canAssignFeedback ? $this->getAssignableUsers()->get() : collect();

        return view('feedback.show', compact('feedback', 'canAssignFeedback', 'assignableUsers'));
    }

    public function updateStatus(Request $request, Feedback $feedback)
    {
        $user = auth()->user();

        if (!$user->hasPermission('update_feedback')) {
            abort(403, 'This action is unauthorized.');
        }

        if (!$this->canManageAllFeedback($user) && (int) $feedback->assigned_to !== (int) $user->id) {
            abort(403, 'You are not assigned to update this feedback.');
        }

        $canAssignFeedback = $this->canAssignFeedback($user);
        
        $validated = $request->validate([
            'status' => 'required|in:pending,reviewed,resolved,closed',
            'priority' => 'nullable|in:low,medium,high,urgent',
            'admin_notes' => 'nullable|string',
            'resolution_notes' => 'nullable|string',
            'assigned_to' => $canAssignFeedback ? 'nullable|exists:users,id' : 'prohibited',
        ]);

        $priority = $validated['priority'] ?? null;
        unset($validated['priority']);

        $feedback->update($validated);

        $metadata = (array) ($feedback->metadata ?? []);
        if ($priority !== null) {
            $metadata['priority'] = $priority;
        } else {
            unset($metadata['priority']);
        }
        $feedback->metadata = $metadata;
        $feedback->save();
        
        if ($validated['status'] === 'reviewed' && !$feedback->reviewed_at) {
            $feedback->update(['reviewed_at' => now()]);
        }
        
        if ($validated['status'] === 'resolved' && !$feedback->resolved_at) {
            $feedback->update(['resolved_at' => now()]);
        }
        
        return redirect()->route('feedback.show', $feedback)
            ->with('success', 'Feedback status updated');
    }

    public function addResponse(Request $request, Feedback $feedback)
    {
        $user = auth()->user();

        if (!$user->hasPermission('respond_feedback')) {
            abort(403, 'This action is unauthorized.');
        }

        if (!$this->canManageAllFeedback($user) && (int) $feedback->assigned_to !== (int) $user->id) {
            abort(403, 'You are not assigned to respond to this feedback.');
        }

        $request->merge([
            'send_email' => $request->boolean('send_email'),
        ]);
        
        $validated = $request->validate([
            'message' => 'required|string|min:10|max:2000',
            'send_email' => 'sometimes|boolean',
        ]);
        
        $response = $this->feedbackService->sendResponse(
            $feedback,
            $validated['message'],
            $validated['send_email'] ?? false
        );
        
        return redirect()->route('feedback.show', $feedback)
            ->with('success', 'Response added successfully');
    }

    public function togglePublic(Request $request, Feedback $feedback)
    {
        if (!$this->canManageAllFeedback(auth()->user())) {
            abort(403, 'This action is unauthorized.');
        }
        
        $feedback->update(['is_public' => !$feedback->is_public]);
        
        return response()->json([
            'success' => true,
            'is_public' => $feedback->is_public,
        ]);
    }

    public function toggleFeatured(Request $request, Feedback $feedback)
    {
        if (!$this->canManageAllFeedback(auth()->user())) {
            abort(403, 'This action is unauthorized.');
        }
        
        if (!$feedback->is_public) {
            return response()->json([
                'success' => false,
                'message' => 'Only public feedback can be featured',
            ], 422);
        }
        
        $feedback->update(['featured' => !$feedback->featured]);
        
        return response()->json([
            'success' => true,
            'featured' => $feedback->featured,
        ]);
    }

    public function analytics()
    {
        if (!auth()->user()->hasPermission('view_feedback_analytics')) {
            abort(403, 'This action is unauthorized.');
        }
        
        // Calculate statistics
        $total = Feedback::count();
        $withRating = Feedback::whereNotNull('rating')->count();
        $averageRating = Feedback::whereNotNull('rating')->avg('rating');
        $averageRating = $averageRating ? round($averageRating, 2) : 0;
        
        // Status breakdown
        $statusBreakdown = [
            'pending' => Feedback::pending()->count(),
            'reviewed' => Feedback::reviewed()->count(),
            'resolved' => Feedback::resolved()->count(),
            'closed' => Feedback::where('status', 'closed')->count(),
        ];
        
        // Type breakdown
        $typeBreakdown = [
            'event' => Feedback::where('type', 'event')->count(),
            'system' => Feedback::where('type', 'system')->count(),
            'general' => Feedback::where('type', 'general')->count(),
            'suggestion' => Feedback::where('type', 'suggestion')->count(),
            'complaint' => Feedback::where('type', 'complaint')->count(),
        ];
        
        // Monthly trend (last 6 months)
        $monthlyTrend = Feedback::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw("COUNT(*) as count")
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        // Top rated events
        $topRatedEvents = Feedback::select('event_id', DB::raw('AVG(rating) as avg_rating'))
            ->whereNotNull('event_id')
            ->whereNotNull('rating')
            ->groupBy('event_id')
            ->orderBy('avg_rating', 'desc')
            ->with('event')
            ->limit(5)
            ->get();
        
        // Recent feedback
        $recentFeedbacks = Feedback::with('event', 'user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('feedback.analytics', compact(
            'total',
            'withRating',
            'averageRating',
            'statusBreakdown',
            'typeBreakdown',
            'monthlyTrend',
            'topRatedEvents',
            'recentFeedbacks'
        ));
    }

    public function export(Request $request)
    {
        if (!auth()->user()->hasPermission('export_feedback')) {
            abort(403, 'This action is unauthorized.');
        }
        
        return $this->feedbackService->exportFeedback($request->all(), $request->get('format', 'excel'));
    }

    public function destroy(Request $request, Feedback $feedback)
    {
        $user = auth()->user();

        if (!$user->hasPermission('delete_feedback') && !$this->canManageAllFeedback($user)) {
            abort(403, 'This action is unauthorized.');
        }

        $feedback->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Feedback deleted successfully.',
            ]);
        }

        return redirect()->route('feedback.index')
            ->with('success', 'Feedback deleted successfully.');
    }

    private function canManageAllFeedback(User $user): bool
    {
        return $user->hasPermission('manage_feedback')
            || $user->isAdmin()
            || $user->hasRole('super-administrator')
            || $user->hasRole('administrator');
    }

    private function canAssignFeedback(User $user): bool
    {
        return $this->canManageAllFeedback($user);
    }

    private function getAssignableUsers(): Builder
    {
        return User::query()
            ->whereHas('role', function ($roleQuery) {
                $roleQuery->whereIn('slug', [
                    'super-admin',
                    'super-administrator',
                    'admin',
                    'administrator',
                    'event-manager',
                    'faculty',
                ]);
            })
            ->orderBy('name');
    }
}
