<?php
// app/Http/Controllers/EventRegistrationController.php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Waitlist;
use App\Models\Notification; // Add this import
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EventParticipantsExport;
use Barryvdh\DomPDF\Facade\Pdf;

class EventRegistrationController extends Controller
{
    /**
     * Display upcoming events for registration
     */
    public function index(Request $request)
    {
        $query = Event::query()
            ->where('is_public', true)
            ->where('requires_registration', true)
            ->where('end_date', '>=', now())
            ->orderBy('start_date');
        
        // Apply filters
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }
        
        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }
        
        if ($request->filled('campus')) {
            $query->where('campus', $request->campus);
        }
        
        $events = $query->paginate(12);
        
        // Get user's registered events
        $registeredEventIds = [];
        if (Auth::check()) {
            $registeredEventIds = EventRegistration::where('user_id', Auth::id())
                ->whereIn('status', ['confirmed', 'pending', 'waitlisted'])
                ->pluck('event_id')
                ->toArray();
        }
        
        return view('events.registration.index', compact('events', 'registeredEventIds'));
    }

    /**
     * Show event registration form
     */
    public function create(Event $event)
    {
        // Check if event requires registration
        if (!$event->requires_registration || !$event->is_public) {
            return redirect()->route('events.guest.show', $event)
                ->with('error', 'This event does not require registration or is not public.');
        }
        
        // Check if already registered
        if (Auth::check()) {
            $existingRegistration = EventRegistration::where('event_id', $event->id)
                ->where('user_id', Auth::id())
                ->whereIn('status', ['confirmed', 'pending', 'waitlisted'])
                ->first();
                
            if ($existingRegistration) {
                if ($existingRegistration->status === 'waitlisted') {
                    return redirect()->route('my-events.index')
                        ->with('info', 'You are on the waitlist for this event.');
                }
                return redirect()->route('my-events.index')
                    ->with('info', 'You are already registered for this event.');
            }
        }
        
        // Check if event is full
        $isFull = $event->is_full;
        $availableSeats = $event->available_seats;
        
        return view('events.registration.create', compact('event', 'isFull', 'availableSeats'));
    }

    /**
     * Register for an event - UPDATED to set status as 'pending'
     */
    public function store(Request $request, Event $event)
    {
        // Validation
        $validated = $request->validate([
            'guest_count' => 'required|integer|min:1|max:5',
            'additional_info' => 'nullable|string|max:1000',
            'agree_terms' => 'required|accepted',
        ]);
        
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please login to register for events.')
                ->with('redirect', url()->current());
        }
        
        $userId = Auth::id();
        
        // Check if event requires registration
        if (!$event->requires_registration || !$event->is_public) {
            return redirect()->back()
                ->with('error', 'This event does not require registration.');
        }
        
        // Check if already registered
        $existingRegistration = EventRegistration::where('event_id', $event->id)
            ->where('user_id', $userId)
            ->first();
            
        if ($existingRegistration) {
            if ($existingRegistration->status === 'cancelled') {
                // If cancelled, we can allow re-registration by updating the existing record
                return $this->updateCancelledRegistration($request, $event, $existingRegistration);
            }
            
            $message = $existingRegistration->status === 'waitlisted' 
                ? 'You are already on the waitlist for this event.'
                : 'You are already registered for this event. Your registration is ' . $existingRegistration->status . '.';
                
            return redirect()->route('my-events.index')
                ->with('info', $message);
        }
        
        // Check if on waitlist
        $existingWaitlist = Waitlist::where('event_id', $event->id)
            ->where('user_id', $userId)
            ->whereNull('converted_at')
            ->first();
            
        if ($existingWaitlist) {
            return redirect()->route('my-events.index')
                ->with('info', 'You are already on the waitlist for this event at position #' . $existingWaitlist->position);
        }
        
        // Check if event is full
        if ($event->is_full) {
            // Add to waitlist
            $waitlist = $this->addToWaitlist($event, $userId);
            
            if ($waitlist) {
                return redirect()->route('my-events.index')
                    ->with('success', 'Event is full. You have been added to the waitlist at position #' . $waitlist->position . '.');
            }
            
            return redirect()->back()
                ->with('error', 'Failed to add you to the waitlist.');
        }
        
        // Check if enough seats available
        if ($event->max_attendees && $validated['guest_count'] > $event->available_seats) {
            return redirect()->back()
                ->with('error', 'Only ' . $event->available_seats . ' seats available.');
        }
        
        // Use database transaction to ensure data consistency
        DB::beginTransaction();
        
        try {
            // Double-check for any race condition
            $finalCheck = EventRegistration::where('event_id', $event->id)
                ->where('user_id', $userId)
                ->first();
                
            if ($finalCheck) {
                DB::rollBack();
                return redirect()->route('my-events.index')
                    ->with('info', 'You are already registered for this event.');
            }
            
            // Create registration with status = 'pending' (not 'confirmed')
            $registration = EventRegistration::create([
                'event_id' => $event->id,
                'user_id' => $userId,
                'guest_count' => $validated['guest_count'],
                'additional_info' => $validated['additional_info'],
                'status' => 'pending', // Changed from 'confirmed' to 'pending'
                'confirmed_at' => null, // Not confirmed yet
                'registration_date' => now(),
            ]);
            
            // DO NOT increment registered_attendees count here
            // That will happen when admin confirms the registration
            
            // Create notification for user that registration is pending
            $this->createPendingNotification($registration);
            
            // Notify admins about pending registration
            $this->notifyAdminsOfPendingRegistration($registration);
            
            DB::commit();
            
            return redirect()->route('my-events.index')
                ->with('success', 'Your registration has been submitted and is pending admin approval. You will be notified once confirmed. Registration number: ' . $registration->registration_number);
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($e instanceof \Illuminate\Database\UniqueConstraintViolationException) {
                return redirect()->route('my-events.index')
                    ->with('info', 'You are already registered for this event.');
            }
            
            return redirect()->back()
                ->with('error', 'Registration failed: ' . $e->getMessage());
        }
    }

    /**
     * Update a cancelled registration to pending
     */
    private function updateCancelledRegistration(Request $request, Event $event, EventRegistration $registration)
    {
        DB::beginTransaction();
        
        try {
            $validated = $request->validate([
                'guest_count' => 'required|integer|min:1|max:5',
                'additional_info' => 'nullable|string|max:1000',
            ]);
            
            // Check if enough seats available
            if ($event->max_attendees && $validated['guest_count'] > $event->available_seats) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Only ' . $event->available_seats . ' seats available.');
            }
            
            // Update the cancelled registration
            $registration->update([
                'guest_count' => $validated['guest_count'],
                'additional_info' => $validated['additional_info'],
                'status' => 'pending',
                'confirmed_at' => null,
                'cancelled_at' => null,
                'cancellation_reason' => null,
            ]);
            
            // DO NOT increment registered_attendees count here
            
            DB::commit();
            
            return redirect()->route('my-events.index')
                ->with('success', 'Your registration for ' . $event->title . ' has been reactivated and is pending admin approval.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to reactivate registration: ' . $e->getMessage());
        }
    }

    /**
     * Add user to waitlist helper method
     */
    private function addToWaitlist(Event $event, $userId)
    {
        // Check if already on waitlist
        $existing = Waitlist::where('event_id', $event->id)
            ->where('user_id', $userId)
            ->first();
            
        if ($existing) {
            if ($existing->converted_at) {
                // Already converted, create new waitlist entry
                $existing->delete();
            } else {
                return $existing;
            }
        }
        
        // Get next position
        $nextPosition = Waitlist::where('event_id', $event->id)
            ->whereNull('converted_at')
            ->max('position') ?? 0;
        $nextPosition++;
        
        return Waitlist::create([
            'event_id' => $event->id,
            'user_id' => $userId,
            'position' => $nextPosition,
            'joined_at' => now(),
        ]);
    }

    /**
     * Cancel registration
     */
    public function cancel(Event $event)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $userId = Auth::id();
        
        $registration = EventRegistration::where('event_id', $event->id)
            ->where('user_id', $userId)
            ->whereIn('status', ['confirmed', 'pending'])
            ->first();
        
        if (!$registration) {
            return redirect()->back()
                ->with('error', 'You are not registered for this event.');
        }
        
        DB::beginTransaction();
        
        try {
            // Store guest count before updating
            $guestCount = $registration->guest_count;
            
            // Update registration status
            $registration->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancellation_reason' => 'Cancelled by user',
            ]);
            
            // Update registered attendees count only if it was confirmed
            if ($registration->status === 'confirmed' && $event->max_attendees) {
                $event->decrement('registered_attendees', $guestCount);
            }
            
            // Move someone from waitlist if available
            $movedFromWaitlist = $this->fillFromWaitlist($event, $guestCount);
            
            DB::commit();
            
            $message = 'Your registration has been cancelled.';
            if (count($movedFromWaitlist) > 0) {
                $message .= ' ' . count($movedFromWaitlist) . ' person(s) from waitlist have been notified.';
            }
            
            return redirect()->route('my-events.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to cancel registration: ' . $e->getMessage());
        }
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
            // Check if user already has a registration
            $existingRegistration = EventRegistration::where('event_id', $event->id)
                ->where('user_id', $waitlistEntry->user_id)
                ->first();
                
            if ($existingRegistration && $existingRegistration->status !== 'cancelled') {
                // Skip this user, move to next
                $waitlistEntry->delete();
                continue;
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
                    'event_id' => $event->id,
                    'user_id' => $waitlistEntry->user_id,
                    'guest_count' => 1,
                    'status' => 'confirmed',
                    'confirmed_at' => now(),
                    'registration_date' => now(),
                ]);
            }
            
            // Update registered attendees count
            $event->increment('registered_attendees', 1);
            
            // Mark waitlist as converted
            $waitlistEntry->update([
                'converted_at' => now(),
            ]);
            
            $movedUsers[] = $waitlistEntry->user;
        }
        
        return $movedUsers;
    }

    /**
     * "My Events" page - show user's registered events
     */
    public function myEvents(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $userId = Auth::id();
        
        $query = EventRegistration::with('event')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc');
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $registrations = $query->paginate(15);
        
        // Get waitlisted events
        $waitlists = Waitlist::with('event')
            ->where('user_id', $userId)
            ->whereNull('converted_at')
            ->orderBy('position')
            ->get();
        
        // Get upcoming events count
        $upcomingCount = EventRegistration::where('user_id', $userId)
            ->whereHas('event', function ($q) {
                $q->where('start_date', '>', now());
            })
            ->whereIn('status', ['confirmed', 'pending'])
            ->count();
        
        // Get attended events count
        $attendedCount = EventRegistration::where('user_id', $userId)
            ->where('attended', true)
            ->count();
        
        return view('events.my-events', compact('registrations', 'waitlists', 'upcomingCount', 'attendedCount'));
    }

    /**
     * Show registration details
     */
    public function show(EventRegistration $registration)
    {
        // Authorization check
        if (Auth::id() !== $registration->user_id && !Auth::user()->hasPermission('manage_events')) {
            abort(403, 'Unauthorized action.');
        }
        
        $registration->load(['event', 'user']);
        
        return view('events.registration.show', compact('registration'));
    }

    /**
     * Organizer: List participants for an event
     */
    public function participants(Event $event)
    {
        // Authorization check - only organizers and admins
        if (!Auth::user()->hasPermission('manage_events')) {
            abort(403, 'Unauthorized action.');
        }
        
        $query = EventRegistration::with('user')
            ->where('event_id', $event->id)
            ->orderBy('created_at', 'desc');
        
        // Filter by status
        if (request()->filled('status')) {
            $query->where('status', request('status'));
        }
        
        $participants = $query->paginate(20);
        
        // Statistics
        $confirmedCount = $event->confirmedRegistrations()->count();
        $pendingCount = EventRegistration::where('event_id', $event->id)
            ->where('status', 'pending')
            ->count();
        $cancelledCount = EventRegistration::where('event_id', $event->id)
            ->where('status', 'cancelled')
            ->count();
        $attendedCount = EventRegistration::where('event_id', $event->id)
            ->where('attended', true)
            ->count();
        
        return view('events.participants.index', compact(
            'event', 'participants', 'confirmedCount',
            'pendingCount', 'cancelledCount', 'attendedCount'
        ));
    }

    /**
     * Organizer: Export participants to Excel
     */
    public function exportExcel(Event $event)
    {
        if (!Auth::user()->hasPermission('manage_events')) {
            abort(403, 'Unauthorized action.');
        }
        
        $filename = 'participants-' . Str::slug($event->title) . '-' . date('Y-m-d') . '.xlsx';
        
        return Excel::download(new EventParticipantsExport($event->id), $filename);
    }

    /**
     * Organizer: Export participants to PDF
     */
    public function exportPdf(Event $event)
    {
        if (!Auth::user()->hasPermission('manage_events')) {
            abort(403, 'Unauthorized action.');
        }
        
        $participants = EventRegistration::with('user')
            ->where('event_id', $event->id)
            ->where('status', 'confirmed')
            ->orderBy('created_at')
            ->get();
        
        $pdf = Pdf::loadView('exports.participants-pdf', compact('event', 'participants'));
        
        return $pdf->download('participants-' . Str::slug($event->title) . '-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Organizer: Check-in participant
     */
    public function checkIn(EventRegistration $registration)
    {
        if (!Auth::user()->hasPermission('manage_events')) {
            abort(403, 'Unauthorized action.');
        }
        
        $registration->update([
            'attended' => true,
            'check_in_time' => now(),
        ]);
        
        return redirect()->back()
            ->with('success', 'Participant checked in successfully.');
    }

    /**
     * Helper: Create pending notification for user
     */
    private function createPendingNotification($registration)
    {
        $notification = Notification::create([
            'title' => 'Registration Pending Approval',
            'message' => "Your registration for '{$registration->event->title}' has been submitted and is awaiting admin approval. You will be notified once confirmed.",
            'type' => 'info',
            'priority' => 1,
            'action_url' => route('my-events.index'),
            'action_text' => 'View My Registrations',
            'data' => [
                'registration_id' => $registration->id,
                'event_id' => $registration->event_id,
                'registration_number' => $registration->registration_number,
            ],
            'created_by' => $registration->user_id,
        ]);

        $notification->users()->attach($registration->user_id, [
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Helper: Notify admins about pending registration
     */
    private function notifyAdminsOfPendingRegistration($registration)
    {
        // Get all admin users
        $adminRoles = Role::whereIn('slug', ['super-admin', 'admin'])->pluck('id');
        $admins = User::whereIn('role_id', $adminRoles)->where('is_active', true)->get();
        
        if ($admins->isEmpty()) {
            return;
        }
        
        $notification = Notification::create([
            'title' => 'New Pending Registration',
            'message' => "{$registration->user->name} has registered for '{$registration->event->title}' and is pending approval.",
            'type' => 'info',
            'priority' => 2,
            'action_url' => route('admin.registrations.pending'),
            'action_text' => 'Review Pending Registrations',
            'data' => [
                'registration_id' => $registration->id,
                'event_id' => $registration->event_id,
                'user_id' => $registration->user_id,
                'registration_number' => $registration->registration_number,
            ],
            'created_by' => $registration->user_id,
        ]);
        
        foreach ($admins as $admin) {
            $notification->users()->attach($admin->id, [
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}