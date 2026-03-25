<?php
// app/Http/Controllers/EventRequestController.php (FULL FIXED VERSION)

namespace App\Http\Controllers;

use App\Models\EventRequest;
use App\Models\Event;
use App\Models\User;
use App\Models\Campus;
use App\Models\Building;
use App\Models\Venue;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventRequestController extends Controller
{
    /**
     * Display a listing of event requests - FOR ADMIN ONLY
     */
    public function index(Request $request)
    {
        // Check if user has permission to manage event requests (admin only)
        if (
            !$this->isApprover(Auth::user())
        ) {
            // If not admin, redirect to my-requests
            return redirect()->route('event-requests.my-requests');
        }

        $query = EventRequest::with(['user', 'reviewer', 'event'])
            ->whereNotIn('status', ['manager_review', 'manager_rejected']);
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%")
                  ->orWhere('organizer_name', 'like', "%{$request->search}%");
            });
        }
        
        $eventRequests = $query->latest()->paginate(20);
        
        // Statistics
        $totalCount = EventRequest::whereNotIn('status', ['manager_review', 'manager_rejected'])->count();
        $pendingCount = EventRequest::pending()->count();
        $approvedCount = EventRequest::approved()->count();
        $rejectedCount = EventRequest::rejected()->count();
        $cancelledCount = EventRequest::where('status', 'cancelled')->count();
        $myRequestsCount = EventRequest::where('user_id', Auth::id())->count();
        
        return view('event-requests.index', compact(
            'eventRequests', 
            'totalCount',
            'pendingCount', 
            'approvedCount', 
            'rejectedCount',
            'cancelledCount',
            'myRequestsCount'
        ));
    }

    /**
     * Display a listing of the authenticated user's event requests
     */
    public function myRequests(Request $request)
    {
        $isEventManager = $this->isEventManager(Auth::user());

        $query = EventRequest::with(['user', 'reviewer', 'event']);
        if (!$isEventManager) {
            $query->where('user_id', Auth::id());
        }
        
        // Filter by status
        if ($request->filled('status')) {
            if ($isEventManager && $request->status === 'pending') {
                $query->whereIn('status', ['manager_review', 'pending']);
            } elseif ($request->status === 'rejected') {
                $query->whereIn('status', ['rejected', 'manager_rejected']);
            } else {
                $query->where('status', $request->status);
            }
        }
        
        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%")
                  ->orWhere('organizer_name', 'like', "%{$request->search}%");
            });
        }
        
        $eventRequests = $query->latest()->paginate(20);
        
        // Statistics
        if ($isEventManager) {
            $totalCount = EventRequest::count();
            $pendingCount = EventRequest::whereIn('status', ['manager_review', 'pending'])->count();
            $approvedCount = EventRequest::approved()->count();
            $rejectedCount = EventRequest::whereIn('status', ['rejected', 'manager_rejected'])->count();
            $cancelledCount = EventRequest::where('status', 'cancelled')->count();
            $managerReviewCount = EventRequest::managerReview()->count();
        } else {
            $totalCount = EventRequest::where('user_id', Auth::id())->count();
            $pendingCount = EventRequest::where('user_id', Auth::id())
                ->whereIn('status', ['manager_review', 'pending'])
                ->count();
            $approvedCount = EventRequest::where('user_id', Auth::id())->approved()->count();
            $rejectedCount = EventRequest::where('user_id', Auth::id())
                ->whereIn('status', ['rejected', 'manager_rejected'])
                ->count();
            $cancelledCount = EventRequest::where('user_id', Auth::id())->where('status', 'cancelled')->count();
            $managerReviewCount = EventRequest::where('user_id', Auth::id())->managerReview()->count();
        }
        
        return view('event-requests.my-requests', compact(
            'eventRequests',
            'totalCount',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'cancelledCount',
            'managerReviewCount'
        ));
    }

    /**
     * Show the form for creating a new event request
     */
    public function create()
    {
        // Get all active campuses
        $campuses = Campus::active()->get();
        
        // Get all available venues
        $venues = Venue::with(['building.campus'])
            ->where('is_available', true)
            ->orderBy('name')
            ->get();
        
        $isEventManager = $this->isEventManager(Auth::user());

        return view('event-requests.create', compact('campuses', 'venues', 'isEventManager'));
    }

    /**
     * Store a newly created event request
     */
    public function store(Request $request)
    {
        $isEventManager = $this->isEventManager(Auth::user());

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'proposed_start_date' => $isEventManager ? 'required|date' : 'nullable|date',
            'proposed_end_date' => $isEventManager ? 'required|date|after_or_equal:proposed_start_date' : 'nullable|date|after_or_equal:proposed_start_date',
            'venue_id' => 'nullable|exists:venues,id',
            'alternative_venue' => 'nullable|string|max:255',
            'campus_id' => 'nullable|exists:campuses,id',
            'building_id' => 'nullable|exists:buildings,id',
            'event_type' => 'required|string|in:academic,cultural,sports,conference,workshop,seminar,exhibition,outreach',
            'organizer_name' => 'nullable|string|max:255',
            'organizer_email' => 'nullable|email',
            'organizer_phone' => 'nullable|string|max:20',
            'expected_attendees' => 'nullable|integer|min:1',
            'additional_requirements' => 'nullable|string',
            'event_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        if (!$isEventManager) {
            $validated['proposed_start_date'] = $validated['proposed_start_date'] ?? now()->addDays(7);
            $validated['proposed_end_date'] = $validated['proposed_end_date'] ?? now()->addDays(7)->addHours(2);
        }

        // Determine the venue name
        $venueName = '';
        if (!empty($validated['venue_id'])) {
            $venue = Venue::with('building.campus')->find($validated['venue_id']);
            if ($venue) {
                $venueName = $venue->name . ' - ' . ($venue->building->name ?? 'N/A');
            }
        } elseif (!empty($validated['alternative_venue'])) {
            $venueName = $validated['alternative_venue'];
        }
        if (empty($venueName)) {
            $venueName = 'To be scheduled by Event Manager';
        }

        // Get campus name
        $campusName = '';
        if (!empty($validated['campus_id'])) {
            $campus = Campus::find($validated['campus_id']);
            $campusName = $campus->name ?? '';
        }

        $imagePath = null;
        if ($request->hasFile('event_image')) {
            $imagePath = $request->file('event_image')->store('events', 'public');
        }

        $additionalRequirementsPayload = $this->buildAdditionalRequirementsPayload(
            $validated['additional_requirements'] ?? null,
            $imagePath
        );

        // Create event request
        $eventRequest = EventRequest::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'proposed_start_date' => $validated['proposed_start_date'],
            'proposed_end_date' => $validated['proposed_end_date'],
            'proposed_venue' => $venueName,
            'proposed_campus' => $campusName,
            'venue_id' => $validated['venue_id'] ?? null,
            'campus_id' => $validated['campus_id'] ?? null,
            'building_id' => $validated['building_id'] ?? null,
            'event_type' => $validated['event_type'],
            'organizer_name' => $validated['organizer_name'] ?? Auth::user()->name,
            'organizer_email' => $validated['organizer_email'] ?? Auth::user()->email,
            'organizer_phone' => $validated['organizer_phone'] ?? null,
            'expected_attendees' => $validated['expected_attendees'] ?? null,
            'additional_requirements' => $additionalRequirementsPayload,
            'user_id' => Auth::id(),
            'status' => $isEventManager ? 'pending' : 'manager_review',
        ]);

        if ($isEventManager) {
            $this->sendEventRequestReviewSentNotification($eventRequest);
        }

        return redirect()->route('event-requests.show', $eventRequest)
            ->with('success', $isEventManager
                ? 'Event request submitted for administrator approval.'
                : 'Event request submitted successfully. It is now waiting for Event Manager scheduling.');
    }

    /**
     * Display the specified event request
     */
    public function show(EventRequest $eventRequest)
    {
        // Authorization check - allow if user is owner or has permission
        if (
            Auth::id() !== $eventRequest->user_id &&
            !$this->isApprover(Auth::user()) &&
            !$this->isEventManager(Auth::user())
        ) {
            abort(403, 'You are not authorized to view this event request.');
        }
        
        $eventRequest->load(['user', 'reviewer', 'event', 'venueRelation', 'campusRelation', 'buildingRelation']);
        
        return view('event-requests.show', compact('eventRequest'));
    }

    /**
     * Serve an event-request image directly from storage.
     * This avoids dependency on the public/storage symlink.
     */
    public function image(EventRequest $eventRequest)
    {
        if (
            Auth::id() !== $eventRequest->user_id &&
            !$this->isApprover(Auth::user()) &&
            !$this->isEventManager(Auth::user())
        ) {
            abort(403, 'You are not authorized to view this event request image.');
        }

        $additionalRequirements = $this->parseAdditionalRequirementsPayload($eventRequest->additional_requirements);
        $rawPath = $additionalRequirements['event_image'] ?? null;

        if (!$rawPath) {
            if ($eventRequest->event && $eventRequest->event->image) {
                return redirect()->route('events.image', ['event' => $eventRequest->event->id]);
            }

            $defaultPath = public_path('images/default-event.jpg');
            if (file_exists($defaultPath)) {
                return response()->file($defaultPath);
            }

            abort(404);
        }

        $normalized = ltrim(str_replace('\\', '/', (string) $rawPath), '/');
        if (filter_var($normalized, FILTER_VALIDATE_URL)) {
            return redirect()->away($normalized);
        }

        if (str_starts_with($normalized, 'storage/')) {
            $normalized = substr($normalized, strlen('storage/'));
        } elseif (str_starts_with($normalized, '/storage/')) {
            $normalized = substr($normalized, strlen('/storage/'));
        }

        if (str_starts_with($normalized, 'public/')) {
            $normalized = substr($normalized, strlen('public/'));
        }

        $candidates = array_values(array_unique(array_filter([
            $normalized,
            'events/' . basename($normalized),
            basename($normalized),
        ])));

        foreach ($candidates as $path) {
            if (Storage::disk('public')->exists($path)) {
                return Storage::disk('public')->response($path);
            }
        }

        $publicCandidates = array_values(array_unique(array_filter([
            public_path($normalized),
            public_path('events/' . basename($normalized)),
            public_path('uploads/events/' . basename($normalized)),
            public_path('storage/' . $normalized),
            public_path('storage/events/' . basename($normalized)),
            storage_path('app/public/' . $normalized),
            storage_path('app/public/events/' . basename($normalized)),
        ])));

        foreach ($publicCandidates as $path) {
            if (file_exists($path)) {
                return response()->file($path);
            }
        }

        if ($eventRequest->event && $eventRequest->event->image) {
            return redirect()->route('events.image', ['event' => $eventRequest->event->id]);
        }

        $defaultPath = public_path('images/default-event.jpg');
        if (file_exists($defaultPath)) {
            return response()->file($defaultPath);
        }

        abort(404);
    }

    /**
     * Show the form for editing the specified event request
     */
    public function edit(EventRequest $eventRequest)
    {
        $isEventManager = $this->isEventManager(Auth::user());

        if ($isEventManager) {
            if ($eventRequest->status !== 'manager_review') {
                return redirect()->route('event-requests.show', $eventRequest)
                    ->with('error', 'This request is no longer in event-manager scheduling stage.');
            }
        } else {
            if (Auth::id() !== $eventRequest->user_id || $eventRequest->status !== 'manager_review') {
                abort(403, 'You are not authorized to edit this event request.');
            }
        }
        
        $campuses = Campus::active()->get();
        $venues = Venue::with(['building.campus'])
            ->where('is_available', true)
            ->orderBy('name')
            ->get();
        
        return view('event-requests.edit', compact('eventRequest', 'campuses', 'venues'));
    }

    /**
     * Update the specified event request
     */
    public function update(Request $request, EventRequest $eventRequest)
    {
        $isEventManager = $this->isEventManager(Auth::user());

        if ($isEventManager) {
            if ($eventRequest->status !== 'manager_review') {
                return redirect()->route('event-requests.show', $eventRequest)
                    ->with('error', 'This request is no longer in event-manager scheduling stage.');
            }
        } else {
            if (Auth::id() !== $eventRequest->user_id || $eventRequest->status !== 'manager_review') {
                abort(403, 'You are not authorized to update this event request.');
            }
        }

        // Backward compatibility: old requests may have empty organizer fields.
        // Auto-fill them from request owner profile so scheduling/approval can proceed.
        $request->merge([
            'organizer_name' => $request->input('organizer_name')
                ?: $eventRequest->organizer_name
                ?: optional($eventRequest->user)->name
                ?: Auth::user()->name,
            'organizer_email' => $request->input('organizer_email')
                ?: $eventRequest->organizer_email
                ?: optional($eventRequest->user)->email
                ?: Auth::user()->email,
        ]);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'proposed_start_date' => 'required|date',
            'proposed_end_date' => 'required|date|after_or_equal:proposed_start_date',
            'proposed_venue' => 'required|string|max:255',
            'proposed_campus' => 'nullable|string|max:255',
            'event_type' => 'required|string|in:academic,cultural,sports,conference,workshop,seminar,exhibition,outreach',
            'organizer_name' => 'required|string|max:255',
            'organizer_email' => 'required|email',
            'organizer_phone' => 'nullable|string|max:20',
            'expected_attendees' => 'nullable|integer|min:1',
            'additional_requirements' => 'nullable|string',
            'event_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'submit_for_approval' => 'nullable|boolean',
        ]);

        $existingPayload = $this->parseAdditionalRequirementsPayload($eventRequest->additional_requirements);
        $eventImagePath = $existingPayload['event_image'] ?? null;

        if ($request->hasFile('event_image')) {
            $newImagePath = $request->file('event_image')->store('events', 'public');

            if (!empty($eventImagePath) && Storage::disk('public')->exists($eventImagePath)) {
                Storage::disk('public')->delete($eventImagePath);
            }

            $eventImagePath = $newImagePath;
        }

        $additionalRequirementsPayload = $this->buildAdditionalRequirementsPayload(
            $validated['additional_requirements'] ?? null,
            $eventImagePath
        );

        $isSubmittingForApproval = $isEventManager && $request->boolean('submit_for_approval');
        $wasManagerReview = $eventRequest->status === 'manager_review';
        $newStatus = $isSubmittingForApproval ? 'pending' : $eventRequest->status;

        // Update event request
        $eventRequest->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'proposed_start_date' => $validated['proposed_start_date'],
            'proposed_end_date' => $validated['proposed_end_date'],
            'proposed_venue' => $validated['proposed_venue'],
            'proposed_campus' => $validated['proposed_campus'] ?? null,
            'event_type' => $validated['event_type'],
            'organizer_name' => $validated['organizer_name'],
            'organizer_email' => $validated['organizer_email'],
            'organizer_phone' => $validated['organizer_phone'] ?? null,
            'expected_attendees' => $validated['expected_attendees'] ?? null,
            'additional_requirements' => $additionalRequirementsPayload,
            'status' => $newStatus,
        ]);

        if ($isSubmittingForApproval && $wasManagerReview) {
            $this->sendEventRequestReviewSentNotification($eventRequest);
        }

        return redirect()->route('event-requests.show', $eventRequest)
            ->with('success', ($isSubmittingForApproval)
                ? 'Event request scheduled and sent to administrators for approval.'
                : 'Event request updated successfully.');
    }

    /**
     * Remove the specified event request
     */
    public function destroy(EventRequest $eventRequest)
    {
        // Only allow deleting if pending
        if ($eventRequest->status !== 'pending') {
            return redirect()->route('event-requests.show', $eventRequest)
                ->with('error', 'Cannot delete event request that is already reviewed.');
        }
        
        // Authorization check - only owner or admin can delete
        if (Auth::id() !== $eventRequest->user_id && !Auth::user()->hasPermission('delete_event_request')) {
            abort(403, 'You are not authorized to delete this event request.');
        }
        
        $eventRequest->delete();
        
        return redirect()->route('event-requests.my-requests')
            ->with('success', 'Event request deleted successfully.');
    }

    /**
     * Cancel an event request
     */
    public function cancel(Request $request, EventRequest $eventRequest)
    {
        $isOwner = Auth::id() === $eventRequest->user_id;

        // Cancellation is requester-only.
        if (!$isOwner) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to cancel this request.'
                ], 403);
            }
            abort(403, 'You are not authorized to cancel this request.');
        }

        if ($eventRequest->status === 'approved') {
            return redirect()->route('event-requests.show', $eventRequest)
                ->with('error', 'This request is already approved. Use event cancellation instead.');
        }
        
        $eventRequest->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Event request cancelled successfully!',
                'redirect' => route('event-requests.my-requests')
            ]);
        }
        
        return redirect()->route('event-requests.my-requests')
            ->with('success', 'Event request cancelled successfully.');
    }

    /**
     * Event manager rejection at manager review stage.
     */
    public function managerReject(Request $request, EventRequest $eventRequest)
    {
        if (!$this->isEventManager(Auth::user())) {
            abort(403, 'Only event manager can reject at this stage.');
        }

        if ($eventRequest->status !== 'manager_review') {
            return redirect()->route('event-requests.show', $eventRequest)
                ->with('error', 'Only manager-review requests can be rejected by event manager.');
        }

        $validated = $request->validate([
            'review_notes' => 'nullable|string|max:1000',
        ]);

        $eventRequest->update([
            'status' => 'manager_rejected',
            'review_notes' => $validated['review_notes'] ?? 'Rejected by Event Manager',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->route('event-requests.my-requests')
            ->with('success', 'Request rejected by Event Manager and removed from admin approval queue.');
    }

    /**
     * Approve an event request
     */
    public function approve(Request $request, EventRequest $eventRequest)
    {
        if (!$this->isApprover(Auth::user())) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to approve event requests.'
                ], 403);
            }
            abort(403, 'You are not authorized to approve event requests.');
        }

        if ($eventRequest->status !== 'pending') {
            return redirect()->route('event-requests.show', $eventRequest)
                ->with('error', 'Only manager-scheduled requests can be approved.');
        }
        
        $validated = $request->validate([
            'review_notes' => 'nullable|string',
            'create_event' => 'boolean',
        ]);
        
        $event = $eventRequest->event;
        if (!$event) {
            $event = $this->createEventFromRequest($eventRequest);
            $this->sendNewEventCreatedNotification($event, $eventRequest->user_id);
        }
        
        $eventRequest->update([
            'status' => 'approved',
            'review_notes' => $validated['review_notes'] ?? null,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'event_id' => $event ? $event->id : null,
        ]);
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Event request approved successfully!',
                'redirect' => route('event-requests.index')
            ]);
        }
        
        return redirect()->route('event-requests.show', $eventRequest)
            ->with('success', 'Event request approved successfully.');
    }

    /**
     * Reject an event request
     */
    public function reject(Request $request, EventRequest $eventRequest)
    {
        if (!$this->isApprover(Auth::user())) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to reject event requests.'
                ], 403);
            }
            abort(403, 'You are not authorized to reject event requests.');
        }

        if ($eventRequest->status !== 'pending') {
            return redirect()->route('event-requests.show', $eventRequest)
                ->with('error', 'Only manager-scheduled requests can be rejected.');
        }
        
        $validated = $request->validate([
            'review_notes' => 'required|string',
        ]);
        
        $eventRequest->update([
            'status' => 'rejected',
            'review_notes' => $validated['review_notes'],
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Event request rejected successfully!',
                'redirect' => route('event-requests.index')
            ]);
        }
        
        return redirect()->route('event-requests.show', $eventRequest)
            ->with('success', 'Event request rejected successfully.');
    }

    /**
     * Create an event from an approved request
     */
    private function createEventFromRequest(EventRequest $eventRequest)
    {
        $slug = Str::slug($eventRequest->title);
        $counter = 1;
        while (Event::where('slug', $slug)->exists()) {
            $slug = Str::slug($eventRequest->title) . '-' . $counter;
            $counter++;
        }

        $additionalRequirements = $this->parseAdditionalRequirementsPayload($eventRequest->additional_requirements);
        $imagePath = $additionalRequirements['event_image'] ?? null;
        
        return Event::create([
            'title' => $eventRequest->title,
            'slug' => $slug,
            'description' => $eventRequest->description,
            'short_description' => Str::limit($eventRequest->description, 200),
            'start_date' => $eventRequest->proposed_start_date,
            'end_date' => $eventRequest->proposed_end_date,
            'campus_id' => $eventRequest->campus_id,
            'building_id' => $eventRequest->building_id,
            'venue_id' => $eventRequest->venue_id,
            'campus' => $eventRequest->proposed_campus,
            'venue' => $eventRequest->proposed_venue,
            'event_type' => $eventRequest->event_type,
            'organizer' => $eventRequest->organizer_name,
            'contact_email' => $eventRequest->organizer_email,
            'contact_phone' => $eventRequest->organizer_phone,
            'max_attendees' => $eventRequest->expected_attendees,
            'registered_attendees' => 0,
            'is_featured' => false,
            'is_public' => true,
            'requires_registration' => true,
            'image' => $imagePath,
        ]);
    }

    private function parseAdditionalRequirementsPayload(?string $value): array
    {
        if (empty($value)) {
            return [];
        }

        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        return ['notes' => $value];
    }

    private function buildAdditionalRequirementsPayload(?string $notes, ?string $eventImagePath): ?string
    {
        $cleanNotes = is_string($notes) ? trim($notes) : '';

        if ($eventImagePath) {
            return json_encode([
                'notes' => $cleanNotes !== '' ? $cleanNotes : null,
                'event_image' => $eventImagePath,
            ]);
        }

        return $cleanNotes !== '' ? $cleanNotes : null;
    }

    /**
     * Quick approve without modal (for AJAX)
     */
    public function quickApprove(Request $request, EventRequest $eventRequest)
    {
        if (!$this->isApprover(Auth::user())) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to approve event requests.'
            ], 403);
        }

        if ($eventRequest->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only manager-scheduled requests can be approved.'
            ], 422);
        }

        $event = $eventRequest->event;
        if (!$event) {
            $event = $this->createEventFromRequest($eventRequest);
            $this->sendNewEventCreatedNotification($event, $eventRequest->user_id);
        }
        
        $eventRequest->update([
            'status' => 'approved',
            'event_id' => $event?->id,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Event request approved successfully!'
        ]);
    }

    /**
     * Quick reject without modal (for AJAX)
     */
    public function quickReject(Request $request, EventRequest $eventRequest)
    {
        if (!$this->isApprover(Auth::user())) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to reject event requests.'
            ], 403);
        }

        if ($eventRequest->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only manager-scheduled requests can be rejected.'
            ], 422);
        }
        
        $eventRequest->update([
            'status' => 'rejected',
            'review_notes' => 'Rejected via quick action',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Event request rejected successfully!'
        ]);
    }

    /**
     * Quick cancel without modal (for AJAX)
     */
    public function quickCancel(Request $request, EventRequest $eventRequest)
    {
        if (Auth::id() !== $eventRequest->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to cancel this request.'
            ], 403);
        }

        if ($eventRequest->status === 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Approved requests must be cancelled from event cancellation flow.'
            ], 422);
        }
        
        $eventRequest->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Event request cancelled successfully!'
        ]);
    }

    private function isApprover(User $user): bool
    {
        return $user->hasRole('super-admin')
            || $user->hasRole('super-administrator')
            || $user->hasRole('administrator')
            || $user->hasRole('admin')
            || $user->isAdmin();
    }

    private function isEventManager(User $user): bool
    {
        return $user->hasRole('event-manager');
    }

    private function sendNewEventCreatedNotification(Event $event, int $creatorUserId): void
    {
        $notification = Notification::create([
            'title' => "New Event Is Created: {$event->title}",
            'message' => "A new event has been approved and published.",
            'type' => 'event',
            'priority' => 1,
            'action_url' => route('events.guest.show', $event->slug),
            'action_text' => 'View Event',
            'data' => [
                'source' => 'event',
                'event_action' => 'created',
                'event_id' => $event->id,
                'event_slug' => $event->slug,
            ],
            'created_by' => Auth::id(),
        ]);

        $users = User::query()
            ->where('id', '!=', $creatorUserId)
            ->where(function ($q) {
                // Always include faculty users (old and newly assigned).
                $q->whereHas('role', function ($roleQuery) {
                    $roleQuery->whereIn('slug', ['faculty']);
                })
                ->orWhere('expertise', 'faculty')
                // Include everyone else that is active/usable.
                ->orWhere(function ($activeQuery) {
                    $activeQuery->where('status', 'active')
                        ->orWhereNull('status')
                        ->orWhere('is_active', true)
                        ->orWhereNull('is_active');
                });
            })
            ->pluck('id');

        if ($users->isEmpty()) {
            return;
        }

        $attach = [];
        foreach ($users as $userId) {
            $attach[$userId] = [
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $notification->users()->attach($attach);
    }

    private function sendEventRequestReviewSentNotification(EventRequest $eventRequest): void
    {
        $notification = Notification::create([
            'title' => "Event Request Review Sent: {$eventRequest->title}",
            'message' => 'An event request has been scheduled by Event Manager and is ready for approval review.',
            'type' => 'event',
            'priority' => 1,
            'action_url' => route('event-requests.show', $eventRequest->id),
            'action_text' => 'Review Request',
            'data' => [
                'source' => 'event_request',
                'event_request_action' => 'review_sent',
                'event_request_id' => $eventRequest->id,
                'event_request_title' => $eventRequest->title,
            ],
            'created_by' => Auth::id(),
        ]);

        $approverRoleSlugs = ['super-admin', 'super-administrator', 'administrator', 'admin'];

        $targetUserIds = $this->activeUsersQuery()
            ->where('id', '!=', Auth::id())
            ->whereHas('role', function ($q) use ($approverRoleSlugs) {
                $q->whereIn('slug', $approverRoleSlugs);
            })
            ->pluck('id');

        if ($targetUserIds->isEmpty()) {
            return;
        }

        $attach = [];
        foreach ($targetUserIds as $userId) {
            $attach[$userId] = [
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $notification->users()->attach($attach);
    }

    private function activeUsersQuery()
    {
        return User::query()->where(function ($q) {
            $q->where('status', 'active')
              ->orWhereNull('status')
              ->orWhere('is_active', true)
              ->orWhereNull('is_active');
        });
    }
}
