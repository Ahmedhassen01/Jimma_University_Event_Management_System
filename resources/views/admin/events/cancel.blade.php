@extends('layouts.app')

@section('title', 'Cancel Event: ' . $event->title)
@section('page-title', 'Cancel Event')
@section('page-subtitle', 'This will send cancellation announcements to all attendees')

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.events.index') }}">Events</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.events.show', $event) }}">{{ Str::limit($event->title, 20) }}</a></li>
    <li class="breadcrumb-item active">Cancel Event</li>
@endsection

@section('content')
<style>
    .cancel-container {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .warning-card {
        background: linear-gradient(135deg, #fff5f5 0%, #fff0f0 100%);
        border-left: 6px solid #dc3545;
        border-radius: 16px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 10px 25px rgba(220, 53, 69, 0.15);
    }
    
    .warning-icon {
        width: 70px;
        height: 70px;
        background: #dc3545;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        color: white;
        font-size: 2rem;
        box-shadow: 0 10px 20px rgba(220, 53, 69, 0.3);
    }
    
    .event-summary {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin: 20px 0;
        border: 1px solid #ffcdd2;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin: 25px 0;
    }
    
    .stat-item {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        text-align: center;
        border: 1px solid #dee2e6;
    }
    
    .stat-number {
        font-size: 1.8rem;
        font-weight: 800;
        color: #0a2c6e;
        line-height: 1.2;
    }
    
    .stat-label {
        font-size: 0.8rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .notification-option {
        border: 2px solid #dee2e6;
        border-radius: 12px;
        padding: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        height: 100%;
        background: white;
    }
    
    .notification-option:hover {
        border-color: #0a2c6e;
        background: #f0f7ff;
        transform: translateY(-3px);
    }
    
    .notification-option.selected {
        border-color: #0a2c6e;
        background: #e6f0ff;
        box-shadow: 0 8px 20px rgba(10,44,110,0.15);
    }
    
    .notification-icon {
        font-size: 2rem;
        margin-bottom: 15px;
        color: #0a2c6e;
    }
    
    .audience-option {
        border: 2px solid #dee2e6;
        border-radius: 10px;
        padding: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .audience-option:hover {
        border-color: #0a2c6e;
        background: #f8f9fa;
    }
    
    .audience-option.selected {
        border-color: #0a2c6e;
        background: #e6f0ff;
    }
    
    .required::after {
        content: " *";
        color: #dc3545;
    }
</style>

<div class="cancel-container">
    <div class="warning-card animate__animated animate__fadeInDown">
        <div class="warning-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h2 class="text-center mb-3" style="color: #dc3545; font-weight: 800;">⚠️ WARNING: Event Cancellation</h2>
        <p class="text-center mb-4">You are about to cancel this event. This action will:</p>
        
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="text-center">
                    <i class="fas fa-ban fa-2x text-danger mb-2"></i>
                    <h6>Cancel the Event</h6>
                    <small class="text-muted">Event will be marked as cancelled and removed from listings</small>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="text-center">
                    <i class="fas fa-bullhorn fa-2x text-warning mb-2"></i>
                    <h6>Create Announcement</h6>
                    <small class="text-muted">A cancellation announcement will be created</small>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="text-center">
                    <i class="fas fa-bell fa-2x text-info mb-2"></i>
                    <h6>Notify Attendees</h6>
                    <small class="text-muted">Registered attendees will receive notifications</small>
                </div>
            </div>
        </div>
    </div>

    <div class="ju-card animate__animated animate__fadeInUp">
        <div class="ju-card-header" style="background: linear-gradient(135deg, #0a2c6e 0%, #041c47 100%);">
            <h5 class="ju-card-title text-white mb-0">
                <i class="fas fa-calendar-times me-2"></i>
                Event Cancellation Form
            </h5>
        </div>
        
        <div class="ju-card-body">
            <!-- Event Summary -->
            <div class="event-summary">
                <div class="d-flex align-items-center mb-3">
                    @if($event->image)
                    <img src="{{ $event->image_url }}" alt="{{ $event->title }}" 
                         style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px; margin-right: 15px;">
                    @else
                    <div style="width: 60px; height: 60px; background: #0a2c6e; border-radius: 8px; margin-right: 15px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-calendar text-white"></i>
                    </div>
                    @endif
                    <div>
                        <h5 class="mb-1">{{ $event->title }}</h5>
                        <p class="text-muted mb-0 small">
                            <i class="fas fa-calendar me-1"></i>{{ $event->start_date->format('l, F j, Y \a\t g:i A') }}
                        </p>
                    </div>
                </div>
                
                <div class="stats-grid">
                    @php
                        $registeredCount = $event->registrations()->whereIn('status', ['confirmed', 'pending'])->count();
                        $waitlistCount = $event->waitlists()->whereNull('converted_at')->count();
                    @endphp
                    <div class="stat-item">
                        <div class="stat-number">{{ $registeredCount }}</div>
                        <div class="stat-label">Registered Attendees</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">{{ $waitlistCount }}</div>
                        <div class="stat-label">On Waitlist</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">{{ $event->views_count ?? 0 }}</div>
                        <div class="stat-label">Total Views</div>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.events.cancel', $event) }}" method="POST" id="cancelForm">
                @csrf
                
                <!-- Cancellation Reason -->
                <div class="mb-4">
                    <label for="cancellation_reason" class="form-label fw-bold required" style="color: #0a2c6e;">
                        <i class="fas fa-pen me-2"></i>Reason for Cancellation
                    </label>
                    <textarea name="cancellation_reason" 
                              id="cancellation_reason" 
                              rows="4" 
                              class="form-control @error('cancellation_reason') is-invalid @enderror"
                              placeholder="Please provide a clear reason for cancelling this event..."
                              required>{{ old('cancellation_reason') }}</textarea>
                    @error('cancellation_reason')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">This reason will be included in the cancellation announcement.</small>
                </div>

                <!-- Additional Message -->
                <div class="mb-4">
                    <label for="additional_message" class="form-label fw-bold" style="color: #0a2c6e;">
                        <i class="fas fa-comment me-2"></i>Additional Information (Optional)
                    </label>
                    <textarea name="additional_message" 
                              id="additional_message" 
                              rows="3" 
                              class="form-control @error('additional_message') is-invalid @enderror"
                              placeholder="Any additional information you'd like to share...">{{ old('additional_message') }}</textarea>
                    @error('additional_message')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Notification Options -->
                <div class="mb-4">
                    <label class="form-label fw-bold" style="color: #0a2c6e;">
                        <i class="fas fa-bell me-2"></i>Notification Settings
                    </label>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div class="notification-option {{ old('send_announcement', true) ? 'selected' : '' }}" 
                                 onclick="selectOption(this, 'send_announcement')">
                                <input type="radio" name="send_announcement" value="1" 
                                       {{ old('send_announcement', true) ? 'checked' : '' }} 
                                       class="d-none">
                                <div class="notification-icon">
                                    <i class="fas fa-bullhorn"></i>
                                </div>
                                <h6 class="fw-bold">Create Announcement</h6>
                                <small class="text-muted">A public announcement will be created</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="notification-option {{ !old('send_announcement', true) ? 'selected' : '' }}" 
                                 onclick="selectOption(this, 'send_announcement', false)">
                                <input type="radio" name="send_announcement" value="0" 
                                       {{ !old('send_announcement', true) ? 'checked' : '' }} 
                                       class="d-none">
                                <div class="notification-icon">
                                    <i class="fas fa-ban"></i>
                                </div>
                                <h6 class="fw-bold">No Announcement</h6>
                                <small class="text-muted">Cancel without creating announcement</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Audience Selection -->
                <div class="mb-4" id="audienceSection" style="{{ old('send_announcement', true) ? 'display: block;' : 'display: none;' }}">
                    <label class="form-label fw-bold" style="color: #0a2c6e;">
                        <i class="fas fa-users me-2"></i>Announcement Audience
                    </label>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="audience-option {{ old('audience', 'all') == 'all' ? 'selected' : '' }}" 
                                 onclick="selectAudience(this, 'all')">
                                <input type="radio" name="audience" value="all" 
                                       {{ old('audience', 'all') == 'all' ? 'checked' : '' }} 
                                       class="d-none">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-globe fa-2x me-3" style="color: #0a2c6e;"></i>
                                    <div>
                                        <h6 class="mb-1">Everyone</h6>
                                        <small class="text-muted">All university members will see this announcement</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="audience-option {{ old('audience') == 'registered_only' ? 'selected' : '' }}" 
                                 onclick="selectAudience(this, 'registered_only')">
                                <input type="radio" name="audience" value="registered_only" 
                                       {{ old('audience') == 'registered_only' ? 'checked' : '' }} 
                                       class="d-none">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-check fa-2x me-3" style="color: #0a2c6e;"></i>
                                    <div>
                                        <h6 class="mb-1">Registered Only</h6>
                                        <small class="text-muted">Only attendees who registered will be notified</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Confirmation Checkbox -->
                <div class="mb-4 p-3 bg-light rounded">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="confirmCancellation" required>
                        <label class="form-check-label fw-bold" for="confirmCancellation" style="color: #dc3545;">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            I understand that this action will cancel the event and notify all affected attendees
                        </label>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-between align-items-center border-top pt-4">
                    <a href="{{ route('admin.events.show', $event) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Event
                    </a>
                    
                    <div>
                        <button type="button" class="btn btn-outline-warning me-2" onclick="previewAnnouncement()">
                            <i class="fas fa-eye me-2"></i>Preview
                        </button>
                        <button type="submit" class="btn btn-danger" id="submitBtn">
                            <i class="fas fa-ban me-2"></i>Cancel Event & Send Announcement
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: #0a2c6e; color: white;">
                <h5 class="modal-title">
                    <i class="fas fa-eye me-2"></i>Announcement Preview
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="previewContent">
                <!-- Preview will be inserted here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
@endpush

@push('scripts')
<script>
function selectOption(element, name, value = null) {
    const radio = element.querySelector('input[type="radio"]');
    if (value !== null) {
        radio.value = value;
    }
    radio.checked = true;
    
    // Update UI
    document.querySelectorAll(`.notification-option`).forEach(opt => {
        opt.classList.remove('selected');
    });
    element.classList.add('selected');
    
    // Show/hide audience section based on selection
    const audienceSection = document.getElementById('audienceSection');
    if (name === 'send_announcement' && radio.value == '1') {
        audienceSection.style.display = 'block';
    } else if (name === 'send_announcement' && radio.value == '0') {
        audienceSection.style.display = 'none';
    }
}

function selectAudience(element, value) {
    const radio = element.querySelector('input[type="radio"]');
    radio.checked = true;
    
    document.querySelectorAll('.audience-option').forEach(opt => {
        opt.classList.remove('selected');
    });
    element.classList.add('selected');
}

function previewAnnouncement() {
    const reason = document.getElementById('cancellation_reason').value;
    const additional = document.getElementById('additional_message').value;
    const audience = document.querySelector('input[name="audience"]:checked')?.value || 'all';
    
    if (!reason) {
        alert('Please enter a cancellation reason first.');
        document.getElementById('cancellation_reason').focus();
        return;
    }
    
    const previewContent = document.getElementById('previewContent');
    previewContent.innerHTML = `
        <div class="cancellation-notice" style="padding: 20px; background: #fff3f3; border-left: 4px solid #dc3545; margin-bottom: 20px;">
            <h2 style="color: #dc3545;"><i class="fas fa-exclamation-triangle"></i> CANCELLED: {{ $event->title }}</h2>
            <p>We regret to inform you that the following event has been cancelled:</p>
            <h3>{{ $event->title }}</h3>
            <table style="width: 100%; margin: 15px 0; border-collapse: collapse;">
                <tr><td style="padding: 8px; background: #f8f9fa;"><strong>Original Date:</strong></td><td style="padding: 8px;">{{ $event->start_date->format('l, F j, Y \a\t g:i A') }}</td></tr>
                <tr><td style="padding: 8px; background: #f8f9fa;"><strong>Location:</strong></td><td style="padding: 8px;">{{ $event->venue_name }}, {{ $event->campus_name }}</td></tr>
                <tr><td style="padding: 8px; background: #f8f9fa;"><strong>Organizer:</strong></td><td style="padding: 8px;">{{ $event->organizer }}</td></tr>
            </table>
            
            <div class="alert alert-warning" style="padding: 15px; background: #fff3cd; border: 1px solid #ffeeba; border-radius: 5px; margin: 15px 0;">
                <strong>Reason for cancellation:</strong><br>
                <p style="margin-top: 10px;">${reason}</p>
            </div>
            
            ${additional ? `
            <div style="margin: 15px 0; padding: 15px; background: #e7f3ff; border-left: 4px solid #0a2c6e;">
                <strong>Additional Information:</strong><br>
                <p style="margin-top: 10px;">${additional}</p>
            </div>
            ` : ''}
            
            <p>We apologize for any inconvenience this cancellation may cause. If you have any questions, please contact the organizer:</p>
            <p><strong>Email:</strong> <a href="mailto:{{ $event->contact_email }}">{{ $event->contact_email }}</a><br>
            @if($event->contact_phone)
            <strong>Phone:</strong> <a href="tel:{{ $event->contact_phone }}">{{ $event->contact_phone }}</a>
            @endif
            </p>
            <p class="text-muted small mt-3">
                <i class="fas fa-users me-1"></i>
                This announcement will be sent to: <strong>${audience === 'all' ? 'All University Members' : 'Registered Attendees Only'}</strong>
            </p>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('previewModal'));
    modal.show();
}

// Form validation
document.getElementById('cancelForm').addEventListener('submit', function(e) {
    const reason = document.getElementById('cancellation_reason').value.trim();
    if (!reason) {
        e.preventDefault();
        alert('Please enter a cancellation reason.');
        document.getElementById('cancellation_reason').focus();
        return;
    }
    
    const confirmCheck = document.getElementById('confirmCancellation');
    if (!confirmCheck.checked) {
        e.preventDefault();
        alert('Please confirm that you understand the consequences of cancelling this event.');
        confirmCheck.focus();
        return;
    }
    
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Cancelling...';
    submitBtn.disabled = true;
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const sendAnnouncement = document.querySelector('input[name="send_announcement"]:checked');
    if (sendAnnouncement && sendAnnouncement.value == '0') {
        document.getElementById('audienceSection').style.display = 'none';
    }
});
</script>
@endpush