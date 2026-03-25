@extends('layouts.app')

@section('title', 'Event Request #' . $eventRequest->id . ' - JU Event Management')

@section('content')
@php
    $isEventManager = auth()->user()->hasRole('event-manager');
@endphp
<div class="container-fluid px-4">
    <div class="row my-4">
        <div class="col-12">
            <div class="card ju-card">
                <div class="card-header ju-card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">
                            <i class="fas fa-clipboard me-2"></i>Event Request #{{ $eventRequest->id }}
                        </h4>
                        <p class="mb-0 text-muted">
                            Submitted by {{ $eventRequest->user->name }} on 
                            {{ $eventRequest->created_at->format('F d, Y \a\t h:i A') }}
                        </p>
                    </div>
                    <div>
                        <span class="badge bg-{{ $eventRequest->status_color }} fs-6">
                            {{ ucwords(str_replace('_', ' ', $eventRequest->status)) }}
                        </span>
                        @if($eventRequest->event_id)
                        <span class="badge bg-success ms-2 fs-6">
                            <i class="fas fa-check-circle me-1"></i> Event Created
                        </span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Left Column: Request Details -->
                        <div class="col-md-8">
                            <!-- Event Details -->
                            <div class="card ju-card-light mb-4">
                                <div class="card-header ju-card-subheader">
                                    <h5 class="mb-0">Event Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <h6 class="text-muted mb-2">Event Title</h6>
                                            <p class="fs-5">{{ $eventRequest->title }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-muted mb-2">Event Type</h6>
                                            <p>
                                                <span class="badge ju-badge">{{ ucfirst($eventRequest->event_type) }}</span>
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <h6 class="text-muted mb-2">Description</h6>
                                        <p>{{ $eventRequest->description }}</p>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="text-muted mb-2">Proposed Dates</h6>
                                            <p class="mb-1">
                                                <strong>Start:</strong> 
                                                {{ $eventRequest->proposed_start_date->format('F d, Y') }}
                                            </p>
                                            <p class="mb-0">
                                                <strong>End:</strong> 
                                                {{ $eventRequest->proposed_end_date->format('F d, Y') }}
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-muted mb-2">Venue</h6>
                                            <p class="mb-1">{{ $eventRequest->proposed_venue }}</p>
                                            @if($eventRequest->proposed_campus)
                                            <p class="mb-0 text-muted">{{ $eventRequest->proposed_campus }} Campus</p>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    @if($eventRequest->expected_attendees)
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <h6 class="text-muted mb-2">Expected Attendees</h6>
                                            <p>{{ number_format($eventRequest->expected_attendees) }}</p>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if($eventRequest->additional_requirements_text)
                                    <div class="mt-3">
                                        <h6 class="text-muted mb-2">Additional Requirements</h6>
                                        <p>{{ $eventRequest->additional_requirements_text }}</p>
                                    </div>
                                    @endif

                                    @if($eventRequest->requested_image_url)
                                    <div class="mt-3">
                                        <h6 class="text-muted mb-2">Requested Event Image</h6>
                                        <img src="{{ $eventRequest->requested_image_url }}"
                                             alt="{{ $eventRequest->title }}"
                                             class="img-fluid rounded border"
                                             style="max-height: 220px; object-fit: cover;">
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Organizer Information -->
                            <div class="card ju-card-light mb-4">
                                <div class="card-header ju-card-subheader">
                                    <h5 class="mb-0">Organizer Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <h6 class="text-muted mb-2">Organizer Name</h6>
                                            <p>{{ $eventRequest->organizer_name }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <h6 class="text-muted mb-2">Organizer Email</h6>
                                            <p>{{ $eventRequest->organizer_email }}</p>
                                        </div>
                                    </div>
                                    
                                    @if($eventRequest->organizer_phone)
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="text-muted mb-2">Organizer Phone</h6>
                                            <p>{{ $eventRequest->organizer_phone }}</p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Created Event (if approved) -->
                            @if($eventRequest->isApproved() && $eventRequest->event)
                            <div class="card ju-card-light mb-4">
                                <div class="card-header ju-card-subheader bg-success text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-check-circle me-2"></i>Created Event
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-success">
                                        <p class="mb-2">This request has been approved and an event has been created.</p>
                                        <a href="{{ route('events.guest.show', $eventRequest->event->slug) }}" class="btn btn-sm btn-light">
                                            <i class="fas fa-external-link-alt me-1"></i> View Event
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <!-- Review Information -->
                            @if($eventRequest->isApproved() || $eventRequest->isRejected())
                            <div class="card ju-card-light">
                                <div class="card-header ju-card-subheader">
                                    <h5 class="mb-0">Review Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <h6 class="text-muted mb-2">Reviewed By</h6>
                                            <p>{{ $eventRequest->reviewer->name ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <h6 class="text-muted mb-2">Reviewed On</h6>
                                            <p>{{ $eventRequest->reviewed_at?->format('F d, Y \a\t h:i A') ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    
                                    @if($eventRequest->review_notes)
                                    <div>
                                        <h6 class="text-muted mb-2">Review Notes</h6>
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <p class="mb-0">{{ $eventRequest->review_notes }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Right Column: Actions & Timeline -->
                        <div class="col-md-4">
                            <!-- Actions -->
                            <div class="card ju-card-light mb-4">
                                <div class="card-header ju-card-subheader">
                                    <h5 class="mb-0">Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        @if($isEventManager && $eventRequest->status === 'manager_review')
                                        <a href="{{ route('event-requests.edit', $eventRequest) }}" 
                                           class="btn ju-btn-primary">
                                            <i class="fas fa-edit me-1"></i> Edit Request
                                        </a>

                                        <form action="{{ route('event-requests.manager-reject', $eventRequest) }}" method="POST" onsubmit="return confirm('Reject this request at manager stage?')">
                                            @csrf
                                            <input type="hidden" name="review_notes" value="Rejected by Event Manager">
                                            <button type="submit" class="btn btn-danger w-100">
                                                <i class="fas fa-times-circle me-1"></i> Reject Request
                                            </button>
                                        </form>
                                        @endif
                                        
                                        @if(!$isEventManager && $eventRequest->user_id == auth()->id() && !in_array($eventRequest->status, ['approved', 'cancelled']))
                                        <form action="{{ route('event-requests.cancel', $eventRequest) }}" 
                                              method="POST" onsubmit="return confirm('Are you sure you want to cancel this request?')">
                                            @csrf
                                            <button type="submit" class="btn btn-warning w-100">
                                                <i class="fas fa-times me-1"></i> Cancel Request
                                            </button>
                                        </form>
                                        @endif
                                        
                                        @if(auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('super-administrator') || auth()->user()->hasRole('administrator') || auth()->user()->hasRole('admin') || auth()->user()->isAdmin())
                                            @if($eventRequest->isPending())
                                            <button type="button" class="btn btn-success w-100" 
                                                    onclick="approveEventRequest({{ $eventRequest->id }})">
                                                <i class="fas fa-check me-1"></i> Approve Request
                                            </button>
                                            
                                            <button type="button" class="btn btn-danger w-100" 
                                                    onclick="rejectEventRequest({{ $eventRequest->id }})">
                                                <i class="fas fa-times-circle me-1"></i> Reject Request
                                            </button>
                                            @endif
                                        @endif
                                        
                                        <a href="{{ (auth()->user()->hasRole('event-manager') || auth()->id() === $eventRequest->user_id) ? route('event-requests.my-requests') : route('event-requests.index') }}" class="btn ju-btn-outline">
                                            <i class="fas fa-arrow-left me-1"></i> Back to List
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Timeline -->
                            <div class="card ju-card-light mb-4">
                                <div class="card-header ju-card-subheader">
                                    <h5 class="mb-0">Request Timeline</h5>
                                </div>
                                <div class="card-body">
                                    <div class="timeline">
                                        <div class="timeline-item {{ in_array($eventRequest->status, ['manager_review', 'pending']) ? 'active' : '' }}">
                                            <div class="timeline-marker"></div>
                                            <div class="timeline-content">
                                                <h6 class="mb-1">Request Submitted</h6>
                                                <p class="text-muted mb-0">{{ $eventRequest->created_at->format('M d, Y h:i A') }}</p>
                                            </div>
                                        </div>
                                        
                                        @if($eventRequest->isApproved() || $eventRequest->isRejected())
                                        <div class="timeline-item active">
                                            <div class="timeline-marker"></div>
                                            <div class="timeline-content">
                                                <h6 class="mb-1">Request {{ ucfirst($eventRequest->status) }}</h6>
                                                <p class="text-muted mb-0">{{ $eventRequest->reviewed_at->format('M d, Y h:i A') }}</p>
                                            </div>
                                        </div>
                                        @endif
                                        
                                        @if($eventRequest->isApproved() && $eventRequest->event)
                                        <div class="timeline-item active">
                                            <div class="timeline-marker"></div>
                                            <div class="timeline-content">
                                                <h6 class="mb-1">Event Created</h6>
                                                <p class="text-muted mb-0">{{ $eventRequest->event->created_at->format('M d, Y h:i A') }}</p>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Request Information -->
                            <div class="card ju-card-light">
                                <div class="card-header ju-card-subheader">
                                    <h5 class="mb-0">Request Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <h6 class="text-muted mb-2">Request ID</h6>
                                        <p class="mb-0">#{{ $eventRequest->id }}</p>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <h6 class="text-muted mb-2">Submitted By</h6>
                                        <p class="mb-0">{{ $eventRequest->user->name }}</p>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <h6 class="text-muted mb-2">Submission Date</h6>
                                        <p class="mb-0">{{ $eventRequest->created_at->format('F d, Y') }}</p>
                                    </div>
                                    
                                    <div>
                                        <h6 class="text-muted mb-2">Last Updated</h6>
                                        <p class="mb-0">{{ $eventRequest->updated_at->format('F d, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 1.5rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 0.5rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background-color: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 1.5rem;
}

.timeline-marker {
    position: absolute;
    left: -1.5rem;
    top: 0;
    width: 1rem;
    height: 1rem;
    border-radius: 50%;
    background-color: #adb5bd;
    border: 2px solid white;
}

.timeline-item.active .timeline-marker {
    background-color: var(--ju-primary);
}

.timeline-content {
    padding-left: 0.5rem;
}
</style>
@endpush

@push('scripts')
<script>
function approveEventRequest(requestId) {
    if (!confirm('Are you sure you want to approve this event request?')) {
        return;
    }

    const notes = prompt('Optional review notes (you can leave this empty):') ?? '';
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/event-requests/${requestId}/approve`;
    form.style.display = 'none';

    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);

    const reviewNotes = document.createElement('input');
    reviewNotes.type = 'hidden';
    reviewNotes.name = 'review_notes';
    reviewNotes.value = notes;
    form.appendChild(reviewNotes);

    document.body.appendChild(form);
    form.submit();
}

function rejectEventRequest(requestId) {
    const reason = prompt('Please provide a reason for rejection:');
    if (reason === null) {
        return;
    }

    if (!reason.trim()) {
        alert('Rejection reason is required.');
        return;
    }

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/event-requests/${requestId}/reject`;
    form.style.display = 'none';

    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);

    const reviewNotes = document.createElement('input');
    reviewNotes.type = 'hidden';
    reviewNotes.name = 'review_notes';
    reviewNotes.value = reason.trim();
    form.appendChild(reviewNotes);

    document.body.appendChild(form);
    form.submit();
}
</script>
@endpush
