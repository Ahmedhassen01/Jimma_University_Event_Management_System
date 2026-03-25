{{-- resources/views/feedback/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Feedback Details - #' . $feedback->id)
@section('page-title', 'Feedback Details')

@section('content')
@php
    $canManageAllFeedback = auth()->user()->hasPermission('manage_feedback')
        || auth()->user()->isAdmin()
        || auth()->user()->hasRole('super-administrator')
        || auth()->user()->hasRole('administrator');
    $canWorkFeedback = $canManageAllFeedback || ((int) $feedback->assigned_to === (int) auth()->id());
@endphp
<style>
    .feedback-detail-card {
        border-left: 5px solid #4361ee;
    }
    
    .message-box {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        white-space: pre-wrap;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
    }
    
    .response-card {
        border-left: 4px solid #28a745;
        background: #f8fff9;
    }
    
    .status-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .status-pending { background: #fff3cd; color: #856404; }
    .status-reviewed { background: #cce5ff; color: #004085; }
    .status-resolved { background: #d4edda; color: #155724; }
    .status-closed { background: #e2e3e5; color: #383d41; }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <!-- Feedback Details Card -->
            <div class="card feedback-detail-card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Feedback #{{ $feedback->id }}</h5>
                        <div>
                            <span class="status-badge status-{{ $feedback->status }}">
                                {{ ucfirst($feedback->status) }}
                            </span>
                            @if($feedback->is_public)
                                <span class="badge bg-success ms-1">
                                    <i class="fas fa-eye"></i> Public
                                </span>
                            @endif
                            @if($feedback->featured)
                                <span class="badge bg-warning ms-1">
                                    <i class="fas fa-star"></i> Featured
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6><i class="fas fa-user me-2"></i>Submitted By</h6>
                            <p class="mb-1">
                                <strong>{{ $feedback->getSubmitterName() }}</strong>
                                @if($feedback->user)
                                    <span class="badge bg-info">Registered User</span>
                                @endif
                            </p>
                            @if($feedback->email)
                                <p class="text-muted mb-0">
                                    <i class="fas fa-envelope me-1"></i>{{ $feedback->email }}
                                </p>
                            @endif
                            @if($feedback->allow_contact)
                                <span class="badge bg-success mt-1">
                                    <i class="fas fa-phone-alt me-1"></i>Contact Allowed
                                </span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-calendar-alt me-2"></i>Submitted On</h6>
                            <p>{{ $feedback->created_at->format('F d, Y h:i A') }}</p>
                            
                            <h6><i class="fas fa-tag me-2"></i>Feedback Type</h6>
                            <span class="badge bg-primary">{{ ucfirst($feedback->type) }}</span>
                        </div>
                    </div>
                    
                    @if($feedback->event)
                    <div class="alert alert-info mb-4">
                        <h6><i class="fas fa-calendar me-2"></i>Related Event</h6>
                        <strong>{{ $feedback->event->title }}</strong>
                        <br>
                        <small class="text-muted">{{ $feedback->event->start_date->format('M d, Y') }} • {{ $feedback->event->venue }}</small>
                        <a href="{{ route('events.guest.show', $feedback->event->slug) }}" target="_blank" class="btn btn-sm btn-outline-info float-end">
                            <i class="fas fa-external-link-alt me-1"></i>View Event
                        </a>
                    </div>
                    @endif
                    
                    <h6><i class="fas fa-file-alt me-2"></i>Subject</h6>
                    <p class="mb-4">{{ $feedback->subject ?: 'No subject provided' }}</p>
                    
                    <h6><i class="fas fa-comment me-2"></i>Message</h6>
                    <div class="message-box mb-4">
                        {{ $feedback->message }}
                    </div>
                    
                    @if($feedback->rating)
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6><i class="fas fa-star me-2"></i>Rating</h6>
                            <div class="rating-stars fs-4">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $feedback->rating)
                                        <i class="fas fa-star text-warning"></i>
                                    @else
                                        <i class="far fa-star text-secondary"></i>
                                    @endif
                                @endfor
                                <span class="ms-2">{{ $feedback->rating }}/5</span>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    @if($feedback->categories->count() > 0)
                    <div class="mb-4">
                        <h6><i class="fas fa-tags me-2"></i>Categories</h6>
                        @foreach($feedback->categories as $category)
                            <span class="badge bg-secondary me-1">{{ $category->name }}</span>
                        @endforeach
                    </div>
                    @endif
                    
                    @if($feedback->admin_notes)
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-sticky-note me-2"></i>Admin Notes</h6>
                        <p class="mb-0">{{ $feedback->admin_notes }}</p>
                    </div>
                    @endif
                    
                    @if($feedback->resolution_notes)
                    <div class="alert alert-success">
                        <h6><i class="fas fa-check-circle me-2"></i>Resolution Notes</h6>
                        <p class="mb-0">{{ $feedback->resolution_notes }}</p>
                        @if($feedback->resolved_at)
                            <small class="text-muted">
                                Resolved on: {{ $feedback->resolved_at->format('M d, Y h:i A') }}
                            </small>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Responses Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-reply me-2"></i>Responses
                        <span class="badge bg-secondary">{{ $feedback->responses->count() }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    @if($feedback->responses->count() > 0)
                        @foreach($feedback->responses as $response)
                        <div class="card response-card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <strong>{{ $response->responder->name }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            {{ $response->created_at->format('M d, Y h:i A') }}
                                        </small>
                                    </div>
                                    @if($response->send_email && $response->email_sent_at)
                                    <span class="badge bg-success">
                                        <i class="fas fa-paper-plane me-1"></i>Email Sent
                                    </span>
                                    @endif
                                </div>
                                <p>{{ $response->message }}</p>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center py-3">
                            <i class="fas fa-inbox fa-2x mb-2"></i><br>
                            No responses yet
                        </p>
                    @endif
                    
                    <!-- Add Response Form -->
                    @if(auth()->user()->can('respond_feedback') && $canWorkFeedback)
                    <div class="mt-4">
                        <h6>Add Response</h6>
                        <form action="{{ route('feedback.add-response', $feedback) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <textarea name="message" class="form-control" rows="4" 
                                          placeholder="Type your response here..." required></textarea>
                            </div>
                            <div class="form-check mb-3">
                                <input type="checkbox" name="send_email" value="1" class="form-check-input" id="sendEmail">
                                <label class="form-check-label" for="sendEmail">
                                    Send email to submitter
                                </label>
                                @if($feedback->email)
                                    <small class="text-muted d-block">
                                        Email will be sent to: {{ $feedback->email }}
                                    </small>
                                @else
                                    <small class="text-danger d-block">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        No email address provided by submitter
                                    </small>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Send Response
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Sidebar Actions -->
        <div class="col-md-4">
            <!-- Status Update Card -->
            @if(auth()->user()->can('update_feedback') && $canWorkFeedback)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Update Status</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('feedback.update-status', $feedback) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="pending" {{ $feedback->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="reviewed" {{ $feedback->status == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                                <option value="resolved" {{ $feedback->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ $feedback->status == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Priority</label>
                            @php $currentPriority = old('priority', data_get($feedback->metadata, 'priority')); @endphp
                            <select name="priority" class="form-select">
                                <option value="">Not set</option>
                                <option value="low" {{ $currentPriority === 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ $currentPriority === 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ $currentPriority === 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ $currentPriority === 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                        </div>
                        
                        @if(($canAssignFeedback ?? false) && isset($assignableUsers))
                        <div class="mb-3">
                            <label class="form-label">Assign To</label>
                            <select name="assigned_to" class="form-select">
                                <option value="">Unassigned</option>
                                @foreach($assignableUsers as $user)
                                    <option value="{{ $user->id }}" {{ $feedback->assigned_to == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->role->name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        
                        <div class="mb-3">
                            <label class="form-label">Admin Notes</label>
                            <textarea name="admin_notes" class="form-control" rows="3" 
                                      placeholder="Internal notes...">{{ $feedback->admin_notes }}</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Resolution Notes</label>
                            <textarea name="resolution_notes" class="form-control" rows="3" 
                                      placeholder="Notes visible to submitter...">{{ $feedback->resolution_notes }}</textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-2"></i>Update Status
                        </button>
                    </form>
                </div>
            </div>
            @endif
            
            <!-- Quick Actions Card -->
            @if($canManageAllFeedback)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button onclick="togglePublic({{ $feedback->id }})" class="btn btn-outline-info">
                            <i class="fas fa-eye{{ $feedback->is_public ? '-slash' : '' }} me-2"></i>
                            {{ $feedback->is_public ? 'Make Private' : 'Make Public' }}
                        </button>
                        
                        @if($feedback->is_public)
                        <button onclick="toggleFeatured({{ $feedback->id }})" class="btn btn-outline-warning">
                            <i class="fas fa-star{{ $feedback->featured ? '-o' : '' }} me-2"></i>
                            {{ $feedback->featured ? 'Remove Featured' : 'Mark as Featured' }}
                        </button>
                        @endif
                        
                        <a href="{{ route('feedback.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                </div>
            </div>
            @endif

            @if(!$canManageAllFeedback)
            <div class="card mb-4">
                <div class="card-body">
                    <a href="{{ route('feedback.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-arrow-left me-2"></i>Back to List
                    </a>
                </div>
            </div>
            @endif

            <!-- Information Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Information</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Feedback ID</span>
                            <strong>#{{ $feedback->id }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Type</span>
                            <strong>{{ ucfirst($feedback->type) }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Priority</span>
                            <strong>{{ ucfirst(data_get($feedback->metadata, 'priority', 'Not set')) }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Submitted</span>
                            <strong>{{ $feedback->created_at->diffForHumans() }}</strong>
                        </li>
                        @if($feedback->reviewed_at)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Reviewed</span>
                            <strong>{{ $feedback->reviewed_at->diffForHumans() }}</strong>
                        </li>
                        @endif
                        @if($feedback->resolved_at)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Resolved</span>
                            <strong>{{ $feedback->resolved_at->diffForHumans() }}</strong>
                        </li>
                        @endif
                        @if($feedback->assignee)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Assigned To</span>
                            <strong>{{ $feedback->assignee->name }}</strong>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function togglePublic(feedbackId) {
    if (confirm('Are you sure you want to change the visibility of this feedback?')) {
        fetch(`/feedback/admin/${feedbackId}/toggle-public`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

function toggleFeatured(feedbackId) {
    if (confirm('Are you sure you want to toggle featured status?')) {
        fetch(`/feedback/admin/${feedbackId}/toggle-featured`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else if (data.message) {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}
</script>
@endpush
@endsection
