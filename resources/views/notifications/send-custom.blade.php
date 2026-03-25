@extends('layouts.app')

@section('title', 'Send Custom Notification - Jimma University Events')
@section('page-title', 'Send Custom Notification')
@section('page-subtitle', 'Send notifications to specific users or groups')

@section('breadcrumb-items')
    <li class="breadcrumb-item">
        <a href="{{ route('notifications.index') }}">Notifications</a>
    </li>
    <li class="breadcrumb-item active">Send Custom</li>
@endsection

<!-- @can('send_notifications')
    Show the form -->
@else
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle me-2"></i>
        You don't have permission to send notifications.
    </div>
@endcan

@section('content')
<div class="row">
    <div class="col-12">
        <div class="ju-card">
            <div class="ju-card-header">
                <h5 class="ju-card-title mb-0">
                    <i class="fas fa-paper-plane me-2"></i>Send Custom Notification
                </h5>
            </div>
            
            <div class="ju-card-body">
                @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <form method="POST" action="{{ route('notifications.send-custom.store') }}" 
                      id="notificationForm" onsubmit="return validateAndSubmit()">
                    @csrf
                    
                    <!-- Title -->
                    <div class="mb-4">
                        <label for="title" class="form-label fw-bold">
                            <i class="fas fa-heading me-2"></i>Notification Title *
                        </label>
                        <input type="text" class="form-control form-control-lg @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title') }}" 
                               placeholder="Enter notification title" required>
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Keep it clear and concise (max 255 characters)
                        </small>
                    </div>
                    
                    <!-- Message -->
                    <div class="mb-4">
                        <label for="message" class="form-label fw-bold">
                            <i class="fas fa-comment-dots me-2"></i>Notification Message *
                        </label>
                        <textarea class="form-control @error('message') is-invalid @enderror" 
                                  id="message" name="message" rows="6" 
                                  placeholder="Enter detailed notification message..." required>{{ old('message') }}</textarea>
                        @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="d-flex justify-content-between mt-1">
                            <small class="form-text text-muted">
                                Minimum 10 characters. Be clear and informative.
                            </small>
                            <small class="form-text text-muted" id="charCount">0 characters</small>
                        </div>
                    </div>
                    
                    <!-- Priority & Recipient Type -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="priority" class="form-label fw-bold">
                                <i class="fas fa-exclamation-circle me-2"></i>Priority *
                            </label>
                            <select class="form-select @error('priority') is-invalid @enderror" 
                                    id="priority" name="priority" required>
                                <option value="">Select priority level</option>
                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>
                                    Low (General Information)
                                </option>
                                <option value="normal" {{ old('priority') == 'normal' || !old('priority') ? 'selected' : '' }}>
                                    Normal (Regular Updates)
                                </option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>
                                    High (Important Announcements)
                                </option>
                                <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>
                                    Urgent (Immediate Attention Required)
                                </option>
                            </select>
                            @error('priority')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="recipient_type" class="form-label fw-bold">
                                <i class="fas fa-users me-2"></i>Recipient Type *
                            </label>
                            <select class="form-select @error('recipient_type') is-invalid @enderror" 
                                    id="recipient_type" name="recipient_type" required 
                                    onchange="toggleRecipientFields()">
                                <option value="">Select recipient type</option>
                                <option value="all" {{ old('recipient_type') == 'all' ? 'selected' : '' }}>
                                    All Users
                                </option>
                                <option value="specific" {{ old('recipient_type') == 'specific' ? 'selected' : '' }}>
                                    Specific Users
                                </option>
                                <option value="event_participants" {{ old('recipient_type') == 'event_participants' ? 'selected' : '' }}>
                                    Event Participants
                                </option>
                                <option value="waitlisted" {{ old('recipient_type') == 'waitlisted' ? 'selected' : '' }}>
                                    Event Waitlist
                                </option>
                            </select>
                            @error('recipient_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Specific Users Selection (Hidden by default) -->
                    <div class="mb-4" id="specificUsersField" style="display: none;">
                        <label for="recipient_ids" class="form-label fw-bold">
                            <i class="fas fa-user-check me-2"></i>Select Users *
                        </label>
                        <select class="form-select select2 @error('recipient_ids') is-invalid @enderror" 
                                id="recipient_ids" name="recipient_ids[]" multiple="multiple" 
                                data-placeholder="Select users...">
                            @foreach($users as $user)
                            <option value="{{ $user->id }}" 
                                {{ is_array(old('recipient_ids')) && in_array($user->id, old('recipient_ids')) ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                            @endforeach
                        </select>
                        @error('recipient_ids')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Select multiple users. You can type to search.
                        </small>
                    </div>
                    
                    <!-- Event Selection (Hidden by default) -->
                    <div class="mb-4" id="eventField" style="display: none;">
                        <label for="event_id" class="form-label fw-bold">
                            <i class="fas fa-calendar-alt me-2"></i>Select Event *
                        </label>
                        <select class="form-select @error('event_id') is-invalid @enderror" 
                                id="event_id" name="event_id">
                            <option value="">Select an event</option>
                            @foreach($events as $event)
                            <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                {{ $event->title }} 
                                ({{ $event->start_date->format('M d, Y') }})
                                @if($event->venue && is_object($event->venue) && property_exists($event->venue, 'name'))
                                - {{ $event->venue->name }}
                                @endif
                            </option>
                            @endforeach
                        </select>
                        @error('event_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Select event to notify participants or waitlisted users
                        </small>
                    </div>
                    
                    <!-- Preview & Summary -->
                    <div class="mb-4" id="recipientSummary" style="display: none;">
                        <div class="alert alert-info">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle fa-2x me-3"></i>
                                <div>
                                    <h6 class="alert-heading mb-1">Recipient Summary</h6>
                                    <p class="mb-0" id="summaryText"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Debug Info (hidden) -->
                    <div class="alert alert-secondary d-none" id="debugInfo">
                        <h6>Debug Information:</h6>
                        <p id="debugContent"></p>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="mt-5">
                        <div class="alert alert-warning">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                                <div>
                                    <h6 class="alert-heading mb-1">Important Notice</h6>
                                    <p class="mb-0">
                                        This notification will be sent immediately to selected recipients.
                                        Please double-check all details before sending.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('notifications.index') }}" class="btn btn-outline-ju">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-ju px-4" id="submitBtn">
                                <i class="fas fa-paper-plane me-2"></i>Send Notification
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.select2-container .select2-selection--multiple {
    min-height: 46px;
    border: 1px solid #dee2e6;
    border-radius: var(--ju-radius-md);
}

.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background: var(--ju-primary);
    border: none;
    color: white;
    border-radius: var(--ju-radius-sm);
}

.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: var(--ju-primary);
    color: white;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>

    
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page loaded, initializing...');
    
    // Initialize Select2
    if ($.fn.select2) {
        console.log('Initializing Select2...');
        $('#recipient_ids').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Select users...',
            allowClear: true,
            maximumSelectionLength: 100,
            dropdownParent: $('#notificationForm')
        });
        console.log('Select2 initialized');
    }
    
    // Character counter for message
    const messageTextarea = document.getElementById('message');
    const charCount = document.getElementById('charCount');
    
    messageTextarea.addEventListener('input', function() {
        const length = this.value.length;
        charCount.textContent = length + ' characters';
        
        if (length < 10) {
            charCount.classList.add('text-danger');
            charCount.classList.remove('text-success');
        } else {
            charCount.classList.remove('text-danger');
            charCount.classList.add('text-success');
        }
    });
    
    // Initial character count
    messageTextarea.dispatchEvent(new Event('input'));
    
    // Toggle fields based on recipient type
    toggleRecipientFields();
    
    // Debug: Show form info
    console.log('Form action URL:', '{{ route("notifications.send-custom.store") }}');
    console.log('CSRF token exists:', document.querySelector('input[name="_token"]') ? 'Yes' : 'No');
});

function validateAndSubmit() {
    console.log('Form validation started...');
    
    const form = document.getElementById('notificationForm');
    const submitBtn = document.getElementById('submitBtn');
    
    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
    
    // Collect form data for debugging
    const formData = new FormData(form);
    const data = {};
    for (let [key, value] of formData.entries()) {
        if (key === 'recipient_ids[]') {
            if (!data['recipient_ids']) data['recipient_ids'] = [];
            data['recipient_ids'].push(value);
        } else {
            data[key] = value;
        }
    }
    
    console.log('Form data to submit:', data);
    
    // Basic validation
    const title = document.getElementById('title').value.trim();
    const message = document.getElementById('message').value.trim();
    const recipientType = document.getElementById('recipient_type').value;
    
    if (!title) {
        alert('Please enter a title.');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Send Notification';
        return false;
    }
    
    if (!message || message.length < 10) {
        alert('Please enter a message with at least 10 characters.');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Send Notification';
        return false;
    }
    
    if (!recipientType) {
        alert('Please select a recipient type.');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Send Notification';
        return false;
    }
    
    // Specific users validation
    if (recipientType === 'specific') {
        const selectedUsers = $('#recipient_ids').val() || [];
        if (selectedUsers.length === 0) {
            alert('Please select at least one user.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Send Notification';
            return false;
        }
    }
    
    // Event validation
    if (recipientType === 'event_participants' || recipientType === 'waitlisted') {
        const eventId = document.getElementById('event_id').value;
        if (!eventId) {
            alert('Please select an event.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Send Notification';
            return false;
        }
    }
    
    console.log('Form validation passed, submitting...');
    return true; // Allow form submission
}

function toggleRecipientFields() {
    console.log('Toggle recipient fields...');
    const recipientType = document.getElementById('recipient_type').value;
    const specificUsersField = document.getElementById('specificUsersField');
    const eventField = document.getElementById('eventField');
    const recipientSummary = document.getElementById('recipientSummary');
    
    console.log('Recipient type:', recipientType);
    
    // Hide all fields first
    specificUsersField.style.display = 'none';
    eventField.style.display = 'none';
    recipientSummary.style.display = 'none';
    
    // Show relevant fields
    switch (recipientType) {
        case 'specific':
            console.log('Showing specific users field');
            specificUsersField.style.display = 'block';
            updateSummary();
            recipientSummary.style.display = 'block';
            break;
        case 'event_participants':
        case 'waitlisted':
            console.log('Showing event field');
            eventField.style.display = 'block';
            updateSummary();
            recipientSummary.style.display = 'block';
            break;
        case 'all':
            console.log('All users selected');
            updateSummary();
            recipientSummary.style.display = 'block';
            break;
        default:
            console.log('No recipient type selected');
    }
}

function updateSummary() {
    const recipientType = document.getElementById('recipient_type').value;
    const summaryText = document.getElementById('summaryText');
    
    switch (recipientType) {
        case 'all':
            summaryText.textContent = 'This notification will be sent to ALL users in the system.';
            break;
        case 'specific':
            const selectedUsers = $('#recipient_ids').val() || [];
            summaryText.textContent = `This notification will be sent to ${selectedUsers.length} selected user(s).`;
            break;
        case 'event_participants':
            const eventSelect = document.getElementById('event_id');
            const eventName = eventSelect.options[eventSelect.selectedIndex]?.text || 'selected event';
            summaryText.textContent = `This notification will be sent to all participants of: ${eventName}`;
            break;
        case 'waitlisted':
            const waitlistEventSelect = document.getElementById('event_id');
            const waitlistEventName = waitlistEventSelect.options[waitlistEventSelect.selectedIndex]?.text || 'selected event';
            summaryText.textContent = `This notification will be sent to all waitlisted users of: ${waitlistEventName}`;
            break;
        default:
            summaryText.textContent = 'Please select a recipient type.';
    }
}
</script>
@endpush