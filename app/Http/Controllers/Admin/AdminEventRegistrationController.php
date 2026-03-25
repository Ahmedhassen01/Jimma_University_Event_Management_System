<?php
// app/Http/Controllers/Admin/AdminEventRegistrationController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\User;
use App\Models\Waitlist;
use App\Models\Notification;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationConfirmedMail;
use App\Exports\EventRegistrationsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminEventRegistrationController extends Controller
{
    /**
     * Constructor with middleware
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->hasPermission('manage_event_registrations') && 
                !auth()->user()->hasPermission('view_registrations')) {
                abort(403, 'Unauthorized access. You need registration management permissions.');
            }
            return $next($request);
        });
    }

    /**
     * Display all registrations with filtering
     */
    public function index(Request $request)
    {
        $query = EventRegistration::with(['event', 'user'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('attended')) {
            $query->where('attended', $request->attended);
        }

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $registrations = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => EventRegistration::count(),
            'pending' => EventRegistration::where('status', 'pending')->count(),
            'confirmed' => EventRegistration::where('status', 'confirmed')->count(),
            'cancelled' => EventRegistration::where('status', 'cancelled')->count(),
            'waitlisted' => EventRegistration::where('status', 'waitlisted')->count(),
            'attended' => EventRegistration::where('attended', true)->count(),
        ];

        $events = Event::where('requires_registration', true)
            ->orderBy('start_date', 'desc')
            ->get(['id', 'title', 'start_date']);

        return view('admin.registrations.index', compact('registrations', 'stats', 'events'));
    }

    /**
     * Show pending registrations that need confirmation
     */
    public function pending(Request $request)
    {
        $query = EventRegistration::with(['event', 'user'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc');

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        $registrations = $query->paginate(20);

        $pendingCount = EventRegistration::where('status', 'pending')->count();
        $events = Event::where('requires_registration', true)->get();

        return view('admin.registrations.pending', compact('registrations', 'pendingCount', 'events'));
    }

    /**
     * Show single registration details
     */
    public function show($id)
    {
        $registration = EventRegistration::with(['event', 'user', 'event.campusRelation', 'event.buildingRelation', 'event.venueRelation'])
            ->findOrFail($id);

        $waitlistPosition = null;
        if ($registration->status === 'waitlisted') {
            $waitlist = Waitlist::where('event_id', $registration->event_id)
                ->where('user_id', $registration->user_id)
                ->whereNull('converted_at')
                ->first();
            $waitlistPosition = $waitlist ? $waitlist->position : null;
        }

        $history = $this->getRegistrationHistory($registration);

        return view('admin.registrations.show', compact('registration', 'waitlistPosition', 'history'));
    }

    /**
     * Show form to confirm registration
     */
    public function confirmForm($id)
    {
        $registration = EventRegistration::with(['event', 'user'])->findOrFail($id);

        if ($registration->status === 'confirmed') {
            return redirect()->route('admin.registrations.show', $registration->id)
                ->with('info', 'This registration is already confirmed.');
        }

        if ($registration->event->is_full && $registration->status !== 'waitlisted') {
            return redirect()->route('admin.registrations.show', $registration->id)
                ->with('warning', 'Warning: This event is at full capacity. Confirming will exceed the limit.');
        }

        return view('admin.registrations.confirm', compact('registration'));
    }

    /**
     * Process registration confirmation
     */
    public function confirm(Request $request, $id)
    {
        $request->validate([
            'notes' => 'nullable|string|max:500',
            'send_email' => 'boolean',
            'guest_count_adjustment' => 'nullable|integer|min:1|max:10',
        ]);

        $registration = EventRegistration::with(['event', 'user'])->findOrFail($id);

        DB::beginTransaction();

        try {
            $oldStatus = $registration->status;
            $guestCount = $request->guest_count_adjustment ?? $registration->guest_count;

            $registration->update([
                'status' => 'confirmed',
                'confirmed_at' => now(),
                'guest_count' => $guestCount,
                'notes' => $request->notes ? ($registration->notes ? $registration->notes . "\n\n" . now()->format('Y-m-d H:i') . " - Admin confirmed: " . $request->notes : now()->format('Y-m-d H:i') . " - Admin confirmed: " . $request->notes) : $registration->notes,
            ]);

            if (in_array($oldStatus, ['pending', 'waitlisted'])) {
                $registration->event->increment('registered_attendees', $guestCount);
            }

            if ($request->boolean('send_email')) {
                try {
                    Mail::to($registration->user->email)->send(new RegistrationConfirmedMail($registration));
                } catch (\Exception $e) {
                    \Log::error('Failed to send confirmation email: ' . $e->getMessage());
                }
            }

            $this->createConfirmationNotification($registration);

            if ($oldStatus === 'waitlisted') {
                Waitlist::where('event_id', $registration->event_id)
                    ->where('user_id', $registration->user_id)
                    ->whereNull('converted_at')
                    ->update(['converted_at' => now()]);
            }

            DB::commit();

            return redirect()->route('admin.registrations.show', $registration->id)
                ->with('success', 'Registration confirmed successfully. Confirmation email ' . 
                    ($request->boolean('send_email') ? 'sent to user.' : 'was not sent.'));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to confirm registration: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show form to edit registration
     */
    public function edit($id)
    {
        $registration = EventRegistration::with(['event', 'user'])->findOrFail($id);
        $events = Event::where('requires_registration', true)->orderBy('start_date', 'desc')->get();
        $users = User::orderBy('name')->limit(100)->get();

        return view('admin.registrations.edit', compact('registration', 'events', 'users'));
    }

    /**
     * Update registration
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'user_id' => 'required|exists:users,id',
            'guest_count' => 'required|integer|min:1|max:10',
            'status' => 'required|in:pending,confirmed,cancelled,waitlisted',
            'additional_info' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000',
            'attended' => 'boolean',
            'check_in_time' => 'nullable|date',
        ]);

        $registration = EventRegistration::findOrFail($id);

        DB::beginTransaction();

        try {
            $oldEventId = $registration->event_id;
            $oldStatus = $registration->status;
            $oldGuestCount = $registration->guest_count;

            $registration->update([
                'event_id' => $request->event_id,
                'user_id' => $request->user_id,
                'guest_count' => $request->guest_count,
                'status' => $request->status,
                'additional_info' => $request->additional_info,
                'notes' => $request->notes,
                'attended' => $request->boolean('attended'),
                'check_in_time' => $request->check_in_time,
            ]);

            if ($request->status === 'confirmed' && $oldStatus !== 'confirmed') {
                $registration->update(['confirmed_at' => now()]);
            }

            $this->handleEventCountChanges($registration, $oldEventId, $oldStatus, $oldGuestCount);

            DB::commit();

            return redirect()->route('admin.registrations.show', $registration->id)
                ->with('success', 'Registration updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update registration: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Cancel registration
     */
    /**
 * Show cancel page
 */
public function cancelPage($id)
{
    $registration = EventRegistration::with(['event', 'user'])->findOrFail($id);
    
    if ($registration->status === 'cancelled') {
        return redirect()->route('admin.registrations.show', $registration->id)
            ->with('error', 'This registration is already cancelled.');
    }
    
    return view('admin.registrations.cancel', compact('registration'));
}

/**
 * Cancel registration
 */
public function cancel(Request $request, $id)
{
    $request->validate([
        'cancellation_reason' => 'required|string|max:1000',
    ]);

    $registration = EventRegistration::with('event')->findOrFail($id);

    if ($registration->status === 'cancelled') {
        return redirect()->route('admin.registrations.show', $registration->id)
            ->with('error', 'Registration is already cancelled.');
    }

    DB::beginTransaction();

    try {
        $oldStatus = $registration->status;
        $guestCount = $registration->guest_count;

        // Update registration
        $registration->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $request->cancellation_reason,
        ]);

        // Decrease event attendee count if it was confirmed
        if ($oldStatus === 'confirmed') {
            $registration->event->decrement('registered_attendees', $guestCount);
        }

        // Create notification for user
        $this->createCancellationNotification($registration, $request->cancellation_reason);

        DB::commit();

        return redirect()->route('admin.registrations.show', $registration->id)
            ->with('success', 'Registration cancelled successfully. The user has been notified.');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()
            ->with('error', 'Failed to cancel registration: ' . $e->getMessage())
            ->withInput();
    }
}

    /**
     * Check-in participant
     */
    public function checkIn($id)
    {
        $registration = EventRegistration::findOrFail($id);

        if ($registration->attended) {
            return redirect()->back()->with('info', 'Participant already checked in.');
        }

        if ($registration->status !== 'confirmed') {
            return redirect()->back()->with('error', 'Only confirmed registrations can check in.');
        }

        $registration->update([
            'attended' => true,
            'check_in_time' => now(),
        ]);

        return redirect()->back()->with('success', 'Participant checked in successfully.');
    }

    /**
     * Bulk confirm multiple registrations
     */
    public function bulkConfirm(Request $request)
    {
        $request->validate([
            'registration_ids' => 'required|array',
            'registration_ids.*' => 'exists:event_registrations,id',
            'send_email' => 'boolean',
        ]);

        $ids = json_decode($request->registration_ids, true);
        if (!is_array($ids)) {
            $ids = $request->registration_ids;
        }

        $confirmed = 0;

        DB::beginTransaction();

        try {
            foreach ($ids as $id) {
                $registration = EventRegistration::with('event')->find($id);

                if (!$registration || $registration->status === 'confirmed') {
                    continue;
                }

                $oldStatus = $registration->status;

                $registration->update([
                    'status' => 'confirmed',
                    'confirmed_at' => now(),
                ]);

                if (in_array($oldStatus, ['pending', 'waitlisted'])) {
                    $registration->event->increment('registered_attendees', $registration->guest_count);
                }

                if ($request->boolean('send_email')) {
                    try {
                        Mail::to($registration->user->email)->send(new RegistrationConfirmedMail($registration));
                    } catch (\Exception $e) {
                        \Log::error('Failed to send bulk confirmation email: ' . $e->getMessage());
                    }
                }

                $this->createConfirmationNotification($registration);

                if ($oldStatus === 'waitlisted') {
                    Waitlist::where('event_id', $registration->event_id)
                        ->where('user_id', $registration->user_id)
                        ->whereNull('converted_at')
                        ->update(['converted_at' => now()]);
                }

                $confirmed++;
            }

            DB::commit();

            return redirect()->back()
                ->with('success', "Successfully confirmed {$confirmed} registration(s).");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Bulk confirmation failed: ' . $e->getMessage());
        }
    }

    /**
     * Bulk check-in
     */
    public function bulkCheckIn(Request $request)
    {
        $request->validate([
            'registration_ids' => 'required|array',
            'registration_ids.*' => 'exists:event_registrations,id',
        ]);

        $ids = json_decode($request->registration_ids, true);
        if (!is_array($ids)) {
            $ids = $request->registration_ids;
        }

        $checkedIn = 0;

        foreach ($ids as $id) {
            $registration = EventRegistration::find($id);
            if ($registration && !$registration->attended && $registration->status === 'confirmed') {
                $registration->update([
                    'attended' => true,
                    'check_in_time' => now(),
                ]);
                $checkedIn++;
            }
        }

        return redirect()->back()
            ->with('success', "Successfully checked in {$checkedIn} participant(s).");
    }

    /**
     * Export registrations
     */
    public function export(Request $request)
    {
        $query = EventRegistration::with(['event', 'user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $registrations = $query->orderBy('created_at', 'desc')->get();

        if ($request->format === 'pdf') {
            $pdf = Pdf::loadView('exports.registrations-pdf', compact('registrations'));
            return $pdf->download('registrations-' . now()->format('Y-m-d') . '.pdf');
        }

        $filename = 'registrations-' . now()->format('Y-m-d-His') . '.xlsx';
        return Excel::download(new EventRegistrationsExport($registrations), $filename);
    }

    /**
     * Export single registration as PDF
     */
    public function exportPdf($id)
    {
        $registration = EventRegistration::with(['event', 'user', 'event.campusRelation', 'event.buildingRelation', 'event.venueRelation'])
            ->findOrFail($id);

        $pdf = Pdf::loadView('exports.registration-pdf', compact('registration'));
        return $pdf->download('registration-' . $registration->registration_number . '.pdf');
    }

    /**
     * Statistics dashboard
     */
    public function statistics()
    {
        $totalRegistrations = EventRegistration::count();
        $confirmedRegistrations = EventRegistration::where('status', 'confirmed')->count();
        $pendingRegistrations = EventRegistration::where('status', 'pending')->count();
        $cancelledRegistrations = EventRegistration::where('status', 'cancelled')->count();
        $waitlistedRegistrations = EventRegistration::where('status', 'waitlisted')->count();
        $attendedCount = EventRegistration::where('attended', true)->count();

        $registrationsByEvent = Event::withCount(['registrations' => function ($query) {
                $query->where('status', 'confirmed');
            }])
            ->where('requires_registration', true)
            ->orderBy('registrations_count', 'desc')
            ->limit(10)
            ->get();

        $dailyRegistrations = EventRegistration::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "confirmed" THEN 1 ELSE 0 END) as confirmed'),
                DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending'),
                DB::raw('SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled'),
                DB::raw('SUM(CASE WHEN status = "waitlisted" THEN 1 ELSE 0 END) as waitlisted')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.registrations.statistics', compact(
            'totalRegistrations',
            'confirmedRegistrations',
            'pendingRegistrations',
            'cancelledRegistrations',
            'waitlistedRegistrations',
            'attendedCount',
            'registrationsByEvent',
            'dailyRegistrations'
        ));
    }

    /**
     * Fill seats from waitlist
     */
    private function fillFromWaitlist(Event $event, $numberOfSpots = 1)
    {
        $movedUsers = [];
        
        $waitlistEntries = Waitlist::where('event_id', $event->id)
            ->whereNull('converted_at')
            ->with('user')
            ->orderBy('position')
            ->limit($numberOfSpots)
            ->get();
        
        foreach ($waitlistEntries as $waitlistEntry) {
            $existingRegistration = EventRegistration::where('event_id', $event->id)
                ->where('user_id', $waitlistEntry->user_id)
                ->first();
                
            if ($existingRegistration && $existingRegistration->status !== 'cancelled') {
                $waitlistEntry->delete();
                continue;
            }
            
            if ($existingRegistration && $existingRegistration->status === 'cancelled') {
                $existingRegistration->update([
                    'status' => 'confirmed',
                    'confirmed_at' => now(),
                    'cancelled_at' => null,
                    'cancellation_reason' => null,
                ]);
            } else {
                EventRegistration::create([
                    'event_id' => $event->id,
                    'user_id' => $waitlistEntry->user_id,
                    'guest_count' => 1,
                    'status' => 'confirmed',
                    'confirmed_at' => now(),
                    'registration_date' => now(),
                ]);
            }
            
            $event->increment('registered_attendees', 1);
            
            $waitlistEntry->update(['converted_at' => now()]);
            
            $movedUsers[] = $waitlistEntry->user;
        }
        
        return $movedUsers;
    }

    /**
     * Helper: Handle event count changes when updating registration
     */
    private function handleEventCountChanges($registration, $oldEventId, $oldStatus, $oldGuestCount)
    {
        if ($oldEventId != $registration->event_id) {
            if ($oldStatus === 'confirmed') {
                Event::find($oldEventId)->decrement('registered_attendees', $oldGuestCount);
            }
            if ($registration->status === 'confirmed') {
                $registration->event->increment('registered_attendees', $registration->guest_count);
            }
        } elseif ($oldStatus != $registration->status) {
            if ($oldStatus === 'confirmed' && $registration->status !== 'confirmed') {
                $registration->event->decrement('registered_attendees', $oldGuestCount);
            } elseif ($oldStatus !== 'confirmed' && $registration->status === 'confirmed') {
                $registration->event->increment('registered_attendees', $registration->guest_count);
            } elseif ($oldStatus === 'confirmed' && $registration->status === 'confirmed' && $oldGuestCount != $registration->guest_count) {
                $difference = $registration->guest_count - $oldGuestCount;
                if ($difference > 0) {
                    $registration->event->increment('registered_attendees', $difference);
                } else {
                    $registration->event->decrement('registered_attendees', abs($difference));
                }
            }
        }
    }

    /**
     * Helper: Create confirmation notification
     */
    private function createConfirmationNotification($registration)
    {
        $notification = Notification::create([
            'title' => 'Registration Confirmed',
            'message' => "Your registration for '{$registration->event->title}' has been confirmed. Registration number: {$registration->registration_number}",
            'type' => 'success',
            'priority' => 1,
            'action_url' => route('my-events.index'),
            'action_text' => 'View My Events',
            'data' => [
                'registration_id' => $registration->id,
                'event_id' => $registration->event_id,
                'registration_number' => $registration->registration_number,
            ],
            'created_by' => auth()->id(),
        ]);

        $notification->users()->attach($registration->user_id, [
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Helper: Create cancellation notification
     */
    private function createCancellationNotification($registration, $reason)
    {
        $notification = Notification::create([
            'title' => 'Registration Cancelled',
            'message' => "Your registration for '{$registration->event->title}' has been cancelled." . ($reason ? " Reason: {$reason}" : ""),
            'type' => 'warning',
            'priority' => 1,
            'action_url' => route('my-events.index'),
            'action_text' => 'View My Events',
            'data' => [
                'registration_id' => $registration->id,
                'event_id' => $registration->event_id,
                'cancellation_reason' => $reason,
            ],
            'created_by' => auth()->id(),
        ]);

        $notification->users()->attach($registration->user_id, [
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Helper: Get registration history
     */
    private function getRegistrationHistory($registration)
    {
        $history = [];

        $history[] = [
            'date' => $registration->created_at,
            'action' => 'created',
            'description' => 'Registration created',
            'user' => $registration->user->name,
        ];

        if ($registration->confirmed_at) {
            $history[] = [
                'date' => $registration->confirmed_at,
                'action' => 'confirmed',
                'description' => 'Registration confirmed',
                'user' => $registration->status === 'confirmed' ? 'System/Auto' : 'Admin',
            ];
        }

        if ($registration->cancelled_at) {
            $history[] = [
                'date' => $registration->cancelled_at,
                'action' => 'cancelled',
                'description' => $registration->cancellation_reason ? "Cancelled: {$registration->cancellation_reason}" : 'Registration cancelled',
                'user' => 'Admin/User',
            ];
        }

        if ($registration->check_in_time) {
            $history[] = [
                'date' => $registration->check_in_time,
                'action' => 'checked_in',
                'description' => 'Checked in to event',
                'user' => $registration->user->name,
            ];
        }

        usort($history, function ($a, $b) {
            return $b['date']->timestamp - $a['date']->timestamp;
        });

        return $history;
    }


    /**
 * Move user from waitlist to registration
 */
public function moveFromWaitlist(Request $request, $eventId)
{
    $request->validate([
        'waitlist_id' => 'required|exists:waitlists,id',
    ]);

    $waitlist = Waitlist::with('event')->findOrFail($request->waitlist_id);

    if ($waitlist->event_id != $eventId) {
        return redirect()->back()->with('error', 'Waitlist entry does not belong to this event.');
    }

    if ($waitlist->converted_at) {
        return redirect()->back()->with('error', 'This user has already been moved from waitlist.');
    }

    DB::beginTransaction();

    try {
        // Check if event has available seats
        if ($waitlist->event->is_full) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Event is full. Cannot move from waitlist.');
        }

        // Check if user already has registration
        $existingRegistration = EventRegistration::where('event_id', $eventId)
            ->where('user_id', $waitlist->user_id)
            ->first();

        if ($existingRegistration && $existingRegistration->status !== 'cancelled') {
            DB::rollBack();
            return redirect()->back()->with('error', 'User already has an active registration for this event.');
        }

        // Create or update registration
        if ($existingRegistration && $existingRegistration->status === 'cancelled') {
            $existingRegistration->update([
                'status' => 'confirmed',
                'confirmed_at' => now(),
                'cancelled_at' => null,
                'cancellation_reason' => null,
            ]);
        } else {
            EventRegistration::create([
                'event_id' => $eventId,
                'user_id' => $waitlist->user_id,
                'guest_count' => 1,
                'status' => 'confirmed',
                'confirmed_at' => now(),
                'registration_date' => now(),
            ]);
        }

        // Update event attendee count
        $waitlist->event->increment('registered_attendees', 1);

        // Mark waitlist as converted
        $waitlist->update(['converted_at' => now()]);

        // Notify user
        $this->createWaitlistConvertedNotification($waitlist);

        DB::commit();

        return redirect()->back()
            ->with('success', 'User moved from waitlist to confirmed registration successfully.');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()
            ->with('error', 'Failed to move from waitlist: ' . $e->getMessage());
    }
}

/**
 * Helper: Create waitlist converted notification
 */
private function createWaitlistConvertedNotification($waitlist)
{
    $notification = Notification::create([
        'title' => 'Moved from Waitlist!',
        'message' => "Great news! You've been moved from the waitlist and your registration for '{$waitlist->event->title}' is now confirmed.",
        'type' => 'success',
        'priority' => 2,
        'action_url' => route('my-events.index'),
        'action_text' => 'View My Events',
        'data' => [
            'event_id' => $waitlist->event_id,
        ],
        'created_by' => auth()->id(),
    ]);

    $notification->users()->attach($waitlist->user_id, [
        'created_at' => now(),
        'updated_at' => now()
    ]);
}



}