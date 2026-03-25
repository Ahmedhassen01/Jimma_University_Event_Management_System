@extends('layouts.app')

@section('title', 'Event Request #' . $eventRequest->id . ' - JU Event Management')

@section('content')
@php
    $isEventManager = auth()->user()->hasRole('event-manager');
    $isApprover = auth()->user()->hasRole('super-admin')
        || auth()->user()->hasRole('super-administrator')
        || auth()->user()->hasRole('administrator')
        || auth()->user()->hasRole('admin')
        || auth()->user()->isAdmin();

    $requestImageUrl = $eventRequest->requested_image_url;
    if (!$requestImageUrl && $eventRequest->requested_image_path) {
        $requestImageUrl = asset('storage/' . ltrim($eventRequest->requested_image_path, '/'));
    }
    $heroImageUrl = $requestImageUrl ?: ($eventRequest->event?->image_url ?? null);
    $eventTypeLabel = ucwords(str_replace('_', ' ', $eventRequest->event_type));
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
                            <div class="card ju-card-light mb-4 overflow-hidden request-details-card">
                                <div class="request-hero row g-0">
                                    <div class="col-lg-5">
                                        <div class="request-hero-media">
                                            @if($heroImageUrl)
                                                <img src="{{ $heroImageUrl }}"
                                                     alt="{{ $eventRequest->title }}"
                                                     class="request-hero-image"
                                                     onerror="this.style.display='none'; this.parentElement.querySelector('.request-hero-placeholder').style.display='flex';">
                                            @endif
                                            <div class="request-hero-placeholder" style="display: {{ $heroImageUrl ? 'none' : 'flex' }};">
                                                <i class="fas fa-calendar-alt"></i>
                                                <span>No event image yet</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <div class="request-hero-content">
                                            <div class="d-flex align-items-center flex-wrap gap-2 mb-3">
                                                <span class="badge ju-badge">{{ $eventTypeLabel }}</span>
                                                <span class="badge bg-{{ $eventRequest->status_color }}">
                                                    {{ ucwords(str_replace('_', ' ', $eventRequest->status)) }}
                                                </span>
                                                @if($eventRequest->event_id)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check-circle me-1"></i>Published Event
                                                    </span>
                                                @endif
                                            </div>
                                            <h3 class="request-hero-title">{{ $eventRequest->title }}</h3>
                                            <p class="request-hero-description">{{ $eventRequest->description }}</p>

                                            <div class="request-metrics">
                                                <div class="metric-tile">
                                                    <span class="metric-label">Start Date</span>
                                                    <span class="metric-value">
                                                        {{ $eventRequest->proposed_start_date?->format('M d, Y') ?? 'TBD' }}
                                                    </span>
                                                </div>
                                                <div class="metric-tile">
                                                    <span class="metric-label">End Date</span>
                                                    <span class="metric-value">
                                                        {{ $eventRequest->proposed_end_date?->format('M d, Y') ?? 'TBD' }}
                                                    </span>
                                                </div>
                                                <div class="metric-tile">
                                                    <span class="metric-label">Venue</span>
                                                    <span class="metric-value">{{ $eventRequest->proposed_venue ?: 'To be assigned' }}</span>
                                                </div>
                                                <div class="metric-tile">
                                                    <span class="metric-label">Expected Attendees</span>
                                                    <span class="metric-value">
                                                        {{ $eventRequest->expected_attendees ? number_format($eventRequest->expected_attendees) : 'Not set' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if($eventRequest->additional_requirements_text)
                                    <div class="request-extra">
                                        <h6 class="text-muted mb-2">Additional Requirements</h6>
                                        <p class="mb-0">{{ $eventRequest->additional_requirements_text }}</p>
                                    </div>
                                @endif
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
                                        
                                        @if($isApprover)
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
                                                <p class="text-muted mb-0">{{ $eventRequest->reviewed_at?->format('M d, Y h:i A') ?? 'Pending timestamp' }}</p>
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
.request-details-card {
    border: 1px solid #e7eef7;
    box-shadow: 0 12px 28px rgba(0, 39, 137, 0.08);
}

.request-hero-media {
    position: relative;
    height: 100%;
    min-height: 320px;
    background: linear-gradient(145deg, #0a1929, #0d2b4b);
}

.request-hero-image {
    width: 100%;
    height: 100%;
    min-height: 320px;
    object-fit: cover;
}

.request-hero-placeholder {
    position: absolute;
    inset: 0;
    color: #fff;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.2px;
}

.request-hero-placeholder i {
    font-size: 2rem;
    opacity: 0.9;
}

.request-hero-content {
    padding: 1.5rem;
    background: linear-gradient(180deg, #f8fbff 0%, #ffffff 100%);
    height: 100%;
}

.request-hero-title {
    font-weight: 800;
    color: #0a1929;
    margin-bottom: 0.75rem;
}

.request-hero-description {
    color: #475569;
    margin-bottom: 1rem;
    line-height: 1.65;
}

.request-metrics {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0.75rem;
}

.metric-tile {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 0.8rem;
    padding: 0.8rem 0.9rem;
}

.metric-label {
    display: block;
    font-size: 0.78rem;
    color: #64748b;
    margin-bottom: 0.2rem;
}

.metric-value {
    display: block;
    color: #0f172a;
    font-weight: 700;
    line-height: 1.3;
}

.request-extra {
    border-top: 1px solid #edf2f7;
    padding: 1rem 1.25rem 1.25rem;
    background: #ffffff;
}

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

@media (max-width: 991.98px) {
    .request-hero-media,
    .request-hero-image {
        min-height: 260px;
    }
}

@media (max-width: 767.98px) {
    .request-metrics {
        grid-template-columns: 1fr;
    }
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
