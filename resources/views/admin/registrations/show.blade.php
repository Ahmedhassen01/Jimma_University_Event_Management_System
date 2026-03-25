{{-- resources/views/admin/registrations/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Registration Details | Jimma University')
@section('page-title', 'Registration Details')
@section('page-subtitle', 'View registration information')

@section('breadcrumb-items')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.registrations.index') }}">Registrations</a></li>
<li class="breadcrumb-item active">{{ $registration->registration_number }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Main Registration Card -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold" style="color: #002789;">
                    <i class="fas fa-ticket-alt me-2"></i>Registration Information
                </h5>
                <div class="btn-group" role="group">
                    @if($registration->status == 'pending')
                    <a href="{{ route('admin.registrations.confirm-form', $registration->id) }}" 
                       class="btn btn-sm btn-success" 
                       data-bs-toggle="tooltip" 
                       title="Confirm this registration">
                        <i class="fas fa-check me-1"></i> Confirm
                    </a>
                    @endif
                    
                    <a href="{{ route('admin.registrations.edit', $registration->id) }}" 
                       class="btn btn-sm btn-warning" 
                       data-bs-toggle="tooltip" 
                       title="Edit registration details">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    
                    <a href="{{ route('admin.registrations.export-pdf', $registration->id) }}" 
                       class="btn btn-sm btn-info" 
                       target="_blank"
                       data-bs-toggle="tooltip" 
                       title="Download as PDF">
                        <i class="fas fa-file-pdf me-1"></i> PDF
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="text-muted" width="140">Registration #:</td>
                                <td class="fw-bold">{{ $registration->registration_number }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Status:</td>
                                <td>
                                    @php
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'confirmed' => 'success',
                                        'cancelled' => 'danger',
                                        'waitlisted' => 'info'
                                    ];
                                    $statusIcons = [
                                        'pending' => 'clock',
                                        'confirmed' => 'check-circle',
                                        'cancelled' => 'times-circle',
                                        'waitlisted' => 'list-ol'
                                    ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$registration->status] }} fs-6 p-2">
                                        <i class="fas fa-{{ $statusIcons[$registration->status] }} me-1"></i>
                                        {{ ucfirst($registration->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Guest Count:</td>
                                <td class="fw-bold">{{ $registration->guest_count }} person(s)</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Registered On:</td>
                                <td>{{ $registration->created_at->format('F j, Y g:i A') }}</td>
                            </tr>
                            @if($registration->confirmed_at)
                            <tr>
                                <td class="text-muted">Confirmed On:</td>
                                <td>{{ $registration->confirmed_at->format('F j, Y g:i A') }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="text-muted" width="140">Attendance:</td>
                                <td>
                                    @if($registration->attended)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i> Checked In
                                    </span>
                                    <small class="text-muted d-block mt-1">
                                        <i class="fas fa-clock me-1"></i>{{ $registration->check_in_time?->format('F j, Y g:i A') }}
                                    </small>
                                    @else
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-clock me-1"></i> Not Checked In
                                    </span>
                                    @endif
                                </td>
                            </tr>
                            @if($waitlistPosition)
                            <tr>
                                <td class="text-muted">Waitlist Position:</td>
                                <td>
                                    <span class="badge bg-info">
                                        <i class="fas fa-list-ol me-1"></i> #{{ $waitlistPosition }}
                                    </span>
                                </td>
                            </tr>
                            @endif
                            @if($registration->cancelled_at)
                            <tr>
                                <td class="text-muted">Cancelled On:</td>
                                <td>{{ $registration->cancelled_at->format('F j, Y g:i A') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Reason:</td>
                                <td><em>{{ $registration->cancellation_reason ?? 'Not specified' }}</em></td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>

                @if($registration->additional_info)
                <div class="mt-3 p-3 bg-light rounded">
                    <h6 class="fw-bold mb-2">
                        <i class="fas fa-info-circle me-2" style="color: #002789;"></i>
                        Additional Information
                    </h6>
                    <p class="mb-0">{{ $registration->additional_info }}</p>
                </div>
                @endif

                @if($registration->notes)
                <div class="mt-3 p-3 bg-light rounded">
                    <h6 class="fw-bold mb-2">
                        <i class="fas fa-sticky-note me-2" style="color: #002789;"></i>
                        Admin Notes
                    </h6>
                    <p class="mb-0" style="white-space: pre-line;">{{ $registration->notes }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Event Details Card -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold" style="color: #002789;">
                    <i class="fas fa-calendar-alt me-2"></i>Event Details
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h5 class="fw-bold">{{ $registration->event->title }}</h5>
                        <p class="text-muted">{{ Str::limit($registration->event->description, 200) }}</p>
                        
                        <div class="mt-3">
                            <div class="mb-2">
                                <i class="fas fa-calendar me-2" style="color: #002789; width: 20px;"></i>
                                {{ $registration->event->start_date->format('l, F j, Y') }}
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-clock me-2" style="color: #002789; width: 20px;"></i>
                                {{ $registration->event->start_date->format('g:i A') }} - {{ $registration->event->end_date->format('g:i A') }}
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-map-marker-alt me-2" style="color: #002789; width: 20px;"></i>
                                {{ $registration->event->venue_name }}, {{ $registration->event->building_name }}
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-university me-2" style="color: #002789; width: 20px;"></i>
                                {{ $registration->event->campus_name }}
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-user-tie me-2" style="color: #002789; width: 20px;"></i>
                                {{ $registration->event->organizer }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-light p-3 rounded">
                            <h6 class="fw-bold">Capacity Status</h6>
                            @if($registration->event->max_attendees)
                                @php
                                $percentage = ($registration->event->registered_attendees / $registration->event->max_attendees) * 100;
                                $progressClass = $percentage >= 90 ? 'bg-danger' : ($percentage >= 70 ? 'bg-warning' : 'bg-success');
                                @endphp
                                <div class="progress mb-2" style="height: 10px;">
                                    <div class="progress-bar {{ $progressClass }}" 
                                         style="width: {{ $percentage }}%"
                                         role="progressbar"
                                         aria-valuenow="{{ $percentage }}"
                                         aria-valuemin="0"
                                         aria-valuemax="100">
                                    </div>
                                </div>
                                <small class="text-muted">
                                    <strong>{{ $registration->event->registered_attendees }}</strong> / {{ $registration->event->max_attendees }} registered
                                    ({{ round($percentage) }}%)
                                </small>
                            @else
                                <p class="text-muted mb-0">Unlimited capacity</p>
                                <small><strong>{{ $registration->event->registered_attendees }}</strong> registered so far</small>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="mt-3 text-end">
                    <a href="{{ route('admin.events.show', $registration->event) }}" class="btn btn-sm btn-outline-primary">
                        View Full Event Details <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Registrant Details Card -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold" style="color: #002789;">
                    <i class="fas fa-user me-2"></i>Registrant Details
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2 text-center">
                        <div class="avatar-lg bg-primary text-white rounded-circle mx-auto d-flex align-items-center justify-content-center" 
                             style="width: 80px; height: 80px; font-size: 32px;">
                            {{ strtoupper(substr($registration->user->name, 0, 1)) }}
                        </div>
                    </div>
                    <div class="col-md-10">
                        <table class="table table-borderless">
                            <tr>
                                <td class="text-muted" width="120">Name:</td>
                                <td class="fw-bold">{{ $registration->user->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Email:</td>
                                <td>
                                    <a href="mailto:{{ $registration->user->email }}" class="text-decoration-none">
                                        <i class="fas fa-envelope me-1" style="color: #002789;"></i>
                                        {{ $registration->user->email }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">User ID:</td>
                                <td><code>{{ $registration->user->id }}</code></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Account Status:</td>
                                <td>
                                    @if($registration->user->is_active)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i> Active
                                    </span>
                                    @else
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times-circle me-1"></i> Inactive
                                    </span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Registration History -->
        @if(count($history) > 0)
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold" style="color: #002789;">
                    <i class="fas fa-history me-2"></i>Registration History
                </h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @foreach($history as $item)
                    <div class="timeline-item d-flex mb-3">
                        <div class="timeline-icon me-3">
                            <div class="rounded-circle p-2 bg-{{ $item['action'] == 'confirmed' ? 'success' : ($item['action'] == 'cancelled' ? 'danger' : 'info') }} text-white" 
                                 style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-{{ $item['action'] == 'confirmed' ? 'check' : ($item['action'] == 'cancelled' ? 'times' : 'plus') }}"></i>
                            </div>
                        </div>
                        <div class="timeline-content flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <h6 class="fw-bold mb-1">{{ ucfirst($item['action']) }}</h6>
                                <small class="text-muted">{{ $item['date']->format('M d, Y g:i A') }}</small>
                            </div>
                            <p class="mb-0">{{ $item['description'] }}</p>
                            <small class="text-muted">By: {{ $item['user'] }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold" style="color: #002789;">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($registration->status == 'pending')
                    <a href="{{ route('admin.registrations.confirm-form', $registration->id) }}" 
                       class="btn btn-success">
                        <i class="fas fa-check-circle me-2"></i> Confirm Registration
                    </a>
                    @endif

                    @if($registration->status == 'confirmed' && !$registration->attended)
                    <button type="button" 
                            class="btn btn-info" 
                            onclick="checkIn({{ $registration->id }})">
                        <i class="fas fa-sign-in-alt me-2"></i> Check In Participant
                    </button>
                    @endif

                    @if($registration->status != 'cancelled')
                    <a href="{{ route('admin.registrations.cancel-page', $registration->id) }}" 
                       class="btn btn-danger">
                        <i class="fas fa-times-circle me-2"></i> Cancel Registration
                    </a>
                    @endif

                    <a href="{{ route('admin.registrations.edit', $registration->id) }}" 
                       class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i> Edit Registration
                    </a>

                    <a href="{{ route('admin.registrations.export-pdf', $registration->id) }}" 
                       class="btn btn-outline-primary" 
                       target="_blank">
                        <i class="fas fa-file-pdf me-2"></i> Download PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- Waitlist Information (if applicable) -->
        @if($waitlistPosition)
        <div class="card shadow-sm border-0 mb-4 border-warning">
            <div class="card-header bg-warning text-white py-3">
                <h6 class="mb-0 fw-bold">
                    <i class="fas fa-clock me-2"></i>Waitlist Information
                </h6>
            </div>
            <div class="card-body">
                <p class="mb-3">
                    This registration is currently on the waitlist at position 
                    <span class="badge bg-warning text-dark fs-6 p-2">#{{ $waitlistPosition }}</span>
                </p>
                <div class="alert alert-info mb-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <small>Confirming this registration will automatically move the user from waitlist.</small>
                </div>
                <div class="d-grid">
                    <a href="{{ route('admin.registrations.confirm-form', $registration->id) }}" 
                       class="btn btn-success">
                        <i class="fas fa-check-circle me-2"></i> Confirm & Move from Waitlist
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Status Summary Card -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold" style="color: #002789;">
                    <i class="fas fa-chart-pie me-2"></i>Registration Summary
                </h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                        <span class="text-muted">Created:</span>
                        <span class="fw-bold">{{ $registration->created_at->format('M d, Y') }}</span>
                    </li>
                    @if($registration->confirmed_at)
                    <li class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                        <span class="text-muted">Confirmed:</span>
                        <span class="fw-bold">{{ $registration->confirmed_at->format('M d, Y') }}</span>
                    </li>
                    @endif
                    @if($registration->cancelled_at)
                    <li class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                        <span class="text-muted">Cancelled:</span>
                        <span class="fw-bold">{{ $registration->cancelled_at->format('M d, Y') }}</span>
                    </li>
                    @endif
                    @if($registration->attended)
                    <li class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                        <span class="text-muted">Checked In:</span>
                        <span class="fw-bold">{{ $registration->check_in_time?->format('h:i A') }}</span>
                    </li>
                    @endif
                    <li class="d-flex justify-content-between">
                        <span class="text-muted">Event Date:</span>
                        <span class="fw-bold">{{ $registration->event->start_date->format('M d, Y') }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Form for Check-in -->
<form id="checkInForm" method="POST" style="display: none;">
    @csrf
</form>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize all tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();
        
        // Add confirmation for check-in
        window.checkIn = function(registrationId) {
            if (confirm('Are you sure you want to check in this participant?')) {
                $('#checkInForm').attr('action', '{{ url("admin/registrations") }}/' + registrationId + '/check-in');
                $('#checkInForm').submit();
            }
        };
    });
</script>
@endpush