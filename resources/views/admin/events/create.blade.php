{{-- resources/views/admin/events/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Create Event - Jimma University')

@section('page-title', 'Create New Event')
@section('page-subtitle', 'Add a new university event with speakers')

@section('breadcrumb-items')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">Dashboard</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.events.index') }}">Events</a>
    </li>
    <li class="breadcrumb-item active">Create Event</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="ju-card shadow-sm">
                <div class="ju-card-header d-flex align-items-center justify-content-between py-3" style="background: linear-gradient(135deg, #003366 0%, #004080 100%);">
                    <h4 class="ju-card-title mb-0 text-white">
                        <i class="fas fa-calendar-plus me-2"></i>
                        Create New Event
                    </h4>
                    <div class="badge bg-white text-primary p-2">
                        <i class="fas fa-clock me-1"></i>
                        {{ now()->format('M d, Y') }}
                    </div>
                </div>
                
                <div class="ju-card-body">
                    <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data" id="eventForm">
                        @csrf

                        <div class="event-wizard mb-4">
                            <div class="wizard-progress-track">
                                <div class="wizard-progress-fill" id="wizardProgressFill"></div>
                            </div>
                            <div class="wizard-steps">
                                <div class="wizard-step active" data-step-label="Details">
                                    <span>1</span>
                                    <small>Details</small>
                                </div>
                                <div class="wizard-step" data-step-label="Media & Settings">
                                    <span>2</span>
                                    <small>Media & Settings</small>
                                </div>
                                <div class="wizard-step" data-step-label="Speakers & Submit">
                                    <span>3</span>
                                    <small>Speakers & Submit</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <!-- Left Column: Event Information -->
                            <div class="col-lg-7">
                                <!-- Basic Information Card -->
                                <div class="ju-sub-card mb-4 wizard-step-section" data-step="1">
                                    <div class="ju-sub-card-header" style="background: linear-gradient(135deg, #003366 0%, #004080 100%);">
                                        <h5 class="mb-0 text-white">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Basic Information
                                        </h5>
                                    </div>
                                    <div class="ju-sub-card-body">
                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="title" class="form-label required" style="color: #003366;">
                                                        <i class="fas fa-heading me-1"></i>
                                                        Event Title
                                                    </label>
                                                    <input type="text" 
                                                           class="form-control form-control-lg @error('title') is-invalid @enderror" 
                                                           id="title" 
                                                           name="title" 
                                                           value="{{ old('title') }}" 
                                                           placeholder="Enter event title..."
                                                           required>
                                                    @error('title')
                                                        <div class="invalid-feedback d-flex align-items-center">
                                                            <i class="fas fa-exclamation-circle me-2"></i>
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="short_description" class="form-label" style="color: #003366;">
                                                        <i class="fas fa-align-left me-1"></i>
                                                        Short Description (Optional)
                                                    </label>
                                                    <textarea class="form-control @error('short_description') is-invalid @enderror" 
                                                              id="short_description" 
                                                              name="short_description" 
                                                              rows="2" 
                                                              placeholder="Brief summary of your event (max 200 characters)...">{{ old('short_description') }}</textarea>
                                                    <div class="form-text">
                                                        <small>Brief summary that appears in event cards. Leave empty to use first 100 characters of main description.</small>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="description" class="form-label required" style="color: #003366;">
                                                        <i class="fas fa-align-left me-1"></i>
                                                        Full Description
                                                    </label>
                                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                                              id="description" 
                                                              name="description" 
                                                              rows="5" 
                                                              placeholder="Describe your event in detail..."
                                                              required>{{ old('description') }}</textarea>
                                                    <div class="form-text">
                                                        <small>Provide a clear and detailed description of your event. HTML is allowed.</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Date & Time Card -->
                                <div class="ju-sub-card mb-4 wizard-step-section" data-step="1">
                                    <div class="ju-sub-card-header" style="background: linear-gradient(135deg, #003366 0%, #004080 100%);">
                                        <h5 class="mb-0 text-white">
                                            <i class="fas fa-clock me-2"></i>
                                            Date & Time
                                        </h5>
                                    </div>
                                    <div class="ju-sub-card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="start_date" class="form-label required" style="color: #003366;">
                                                        <i class="fas fa-play me-1"></i>
                                                        Start Date & Time
                                                    </label>
                                                    <input type="datetime-local" 
                                                           class="form-control @error('start_date') is-invalid @enderror" 
                                                           id="start_date" 
                                                           name="start_date" 
                                                           value="{{ old('start_date') }}" 
                                                           required>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="end_date" class="form-label required" style="color: #003366;">
                                                        <i class="fas fa-stop me-1"></i>
                                                        End Date & Time
                                                    </label>
                                                    <input type="datetime-local" 
                                                           class="form-control @error('end_date') is-invalid @enderror" 
                                                           id="end_date" 
                                                           name="end_date" 
                                                           value="{{ old('end_date') }}" 
                                                           required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Location Card -->
                                <div class="ju-sub-card mb-4 wizard-step-section" data-step="1">
                                    <div class="ju-sub-card-header" style="background: linear-gradient(135deg, #003366 0%, #004080 100%);">
                                        <h5 class="mb-0 text-white">
                                            <i class="fas fa-map-marker-alt me-2"></i>
                                            Location
                                        </h5>
                                    </div>
                                    <div class="ju-sub-card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="campus_id" class="form-label required" style="color: #003366;">
                                                        <i class="fas fa-university me-1"></i>
                                                        Campus
                                                    </label>
                                                    <select class="form-select @error('campus_id') is-invalid @enderror" 
                                                            id="campus_id" 
                                                            name="campus_id" 
                                                            required>
                                                        <option value="">Select Campus</option>
                                                        @foreach($campuses as $campus)
                                                            <option value="{{ $campus->id }}" 
                                                                    {{ old('campus_id') == $campus->id ? 'selected' : '' }}
                                                                    data-code="{{ $campus->code ?? '' }}">
                                                                {{ $campus->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="building_id" class="form-label" style="color: #003366;">
                                                        <i class="fas fa-building me-1"></i>
                                                        Building
                                                    </label>
                                                    <select class="form-select @error('building_id') is-invalid @enderror" 
                                                            id="building_id" 
                                                            name="building_id">
                                                        <option value="">Select Building (Optional)</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label for="venue_id" class="form-label" style="color: #003366;">
                                                        <i class="fas fa-door-open me-1"></i>
                                                        Venue/Room
                                                    </label>
                                                    <select class="form-select @error('venue_id') is-invalid @enderror" 
                                                            id="venue_id" 
                                                            name="venue_id">
                                                        <option value="">Select Venue (Optional)</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="max_attendees" class="form-label" style="color: #003366;">
                                                        <i class="fas fa-users me-1"></i>
                                                        Capacity
                                                    </label>
                                                    <input type="number" 
                                                           class="form-control @error('max_attendees') is-invalid @enderror" 
                                                           id="max_attendees" 
                                                           name="max_attendees" 
                                                           value="{{ old('max_attendees') }}" 
                                                           min="1"
                                                           placeholder="Max attendees">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column: Settings & Media -->
                            <div class="col-lg-5">
                                <!-- Media Card -->
                                <div class="ju-sub-card mb-4 wizard-step-section" data-step="2">
                                    <div class="ju-sub-card-header" style="background: linear-gradient(135deg, #003366 0%, #004080 100%);">
                                        <h5 class="mb-0 text-white">
                                            <i class="fas fa-images me-2"></i>
                                            Event Image
                                        </h5>
                                    </div>
                                    <div class="ju-sub-card-body">
                                        <div class="form-group">
                                            <label for="image" class="form-label" style="color: #003366;">
                                                <i class="fas fa-image me-1"></i>
                                                Event Image
                                            </label>
                                            <div class="image-upload-container">
                                                <div class="upload-area border rounded p-4 text-center @error('image') border-danger @enderror"
                                                     id="dropArea">
                                                    <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-3"></i>
                                                    <p class="mb-2">Drag & drop your image here</p>
                                                    <p class="text-muted small mb-3">or click to browse</p>
                                                    <input type="file" 
                                                           class="form-control d-none @error('image') is-invalid @enderror" 
                                                           id="image" 
                                                           name="image" 
                                                           accept="image/*">
                                                    <button type="button" class="btn btn-sm" style="background-color: #003366; color: white;" onclick="document.getElementById('image').click()">
                                                        <i class="fas fa-upload me-1"></i> Choose File
                                                    </button>
                                                </div>
                                                
                                                <div class="image-preview mt-3 d-none" id="imagePreview">
                                                    <div class="preview-container position-relative">
                                                        <img id="previewImage" src="#" alt="Preview" class="img-fluid rounded border">
                                                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2" 
                                                                onclick="removeImage()">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                    <small class="text-muted d-block mt-2">
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        Recommended: 1200x600px, Max: 5MB
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Event Type & Organizer Card -->
                                <div class="ju-sub-card mb-4 wizard-step-section" data-step="2">
                                    <div class="ju-sub-card-header" style="background: linear-gradient(135deg, #003366 0%, #004080 100%);">
                                        <h5 class="mb-0 text-white">
                                            <i class="fas fa-tag me-2"></i>
                                            Event Type & Organizer
                                        </h5>
                                    </div>
                                    <div class="ju-sub-card-body">
                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="event_type" class="form-label required" style="color: #003366;">
                                                        <i class="fas fa-tag me-1"></i>
                                                        Event Type
                                                    </label>
                                                    <select class="form-select @error('event_type') is-invalid @enderror" 
                                                            id="event_type" 
                                                            name="event_type" 
                                                            required>
                                                        <option value="">Select Event Type</option>
                                                        <option value="academic" {{ old('event_type') == 'academic' ? 'selected' : '' }}>📚 Academic</option>
                                                        <option value="cultural" {{ old('event_type') == 'cultural' ? 'selected' : '' }}>🎭 Cultural</option>
                                                        <option value="sports" {{ old('event_type') == 'sports' ? 'selected' : '' }}>⚽ Sports</option>
                                                        <option value="conference" {{ old('event_type') == 'conference' ? 'selected' : '' }}>🎤 Conference</option>
                                                        <option value="workshop" {{ old('event_type') == 'workshop' ? 'selected' : '' }}>🛠️ Workshop</option>
                                                        <option value="seminar" {{ old('event_type') == 'seminar' ? 'selected' : '' }}>👨‍🏫 Seminar</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="organizer" class="form-label required" style="color: #003366;">
                                                        <i class="fas fa-users me-1"></i>
                                                        Organizer
                                                    </label>
                                                    <input type="text" 
                                                           class="form-control @error('organizer') is-invalid @enderror" 
                                                           id="organizer" 
                                                           name="organizer" 
                                                           value="{{ old('organizer') }}" 
                                                           placeholder="Department, Club, or Organization..."
                                                           required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact Information Card -->
                                <div class="ju-sub-card mb-4 wizard-step-section" data-step="2">
                                    <div class="ju-sub-card-header" style="background: linear-gradient(135deg, #003366 0%, #004080 100%);">
                                        <h5 class="mb-0 text-white">
                                            <i class="fas fa-address-book me-2"></i>
                                            Contact Information
                                        </h5>
                                    </div>
                                    <div class="ju-sub-card-body">
                                        <div class="form-group mb-3">
                                            <label for="contact_email" class="form-label" style="color: #003366;">
                                                <i class="fas fa-envelope me-1"></i>
                                                Contact Email
                                            </label>
                                            <input type="email" 
                                                   class="form-control @error('contact_email') is-invalid @enderror" 
                                                   id="contact_email" 
                                                   name="contact_email" 
                                                   value="{{ old('contact_email') }}" 
                                                   placeholder="event@ju.edu.et">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="contact_phone" class="form-label" style="color: #003366;">
                                                <i class="fas fa-phone me-1"></i>
                                                Contact Phone
                                            </label>
                                            <input type="tel" 
                                                   class="form-control @error('contact_phone') is-invalid @enderror" 
                                                   id="contact_phone" 
                                                   name="contact_phone" 
                                                   value="{{ old('contact_phone') }}" 
                                                   placeholder="+251 47 111 0000">
                                        </div>
                                    </div>
                                </div>

                                <!-- Settings Card -->
                                <div class="ju-sub-card mb-4 wizard-step-section" data-step="2">
                                    <div class="ju-sub-card-header" style="background: linear-gradient(135deg, #003366 0%, #004080 100%);">
                                        <h5 class="mb-0 text-white">
                                            <i class="fas fa-cog me-2"></i>
                                            Event Settings
                                        </h5>
                                    </div>
                                    <div class="ju-sub-card-body">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   id="is_featured" 
                                                   name="is_featured" 
                                                   value="1"
                                                   {{ old('is_featured') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_featured" style="color: #003366;">
                                                <i class="fas fa-star me-2 text-warning"></i>
                                                Featured Event
                                            </label>
                                            <small class="text-muted d-block mt-1">
                                                Featured events appear on the homepage.
                                            </small>
                                        </div>
                                        
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   id="is_public" 
                                                   name="is_public" 
                                                   value="1"
                                                   {{ old('is_public', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_public" style="color: #003366;">
                                                <i class="fas fa-globe me-2 text-primary"></i>
                                                Public Event
                                            </label>
                                            <small class="text-muted d-block mt-1">
                                                Visible to all website visitors.
                                            </small>
                                        </div>
                                        
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   id="requires_registration" 
                                                   name="requires_registration" 
                                                   value="1"
                                                   {{ old('requires_registration') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="requires_registration" style="color: #003366;">
                                                <i class="fas fa-user-plus me-2 text-success"></i>
                                                Requires Registration
                                            </label>
                                            <small class="text-muted d-block mt-1">
                                                Attendees need to register for this event.
                                            </small>
                                        </div>
                                        
                                        <div class="registration-link-field mt-3 {{ old('requires_registration') ? '' : 'd-none' }}" 
                                             id="registrationLinkField">
                                            <div class="form-group">
                                                <label for="registration_link" class="form-label" style="color: #003366;">
                                                    <i class="fas fa-link me-1"></i>
                                                    Registration Link
                                                </label>
                                                <input type="url" 
                                                       class="form-control @error('registration_link') is-invalid @enderror" 
                                                       id="registration_link" 
                                                       name="registration_link" 
                                                       value="{{ old('registration_link') }}" 
                                                       placeholder="https://forms.example.com/event">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tags Card -->
                                <div class="ju-sub-card wizard-step-section" data-step="2">
                                    <div class="ju-sub-card-header" style="background: linear-gradient(135deg, #003366 0%, #004080 100%);">
                                        <h5 class="mb-0 text-white">
                                            <i class="fas fa-tags me-2"></i>
                                            Tags
                                        </h5>
                                    </div>
                                    <div class="ju-sub-card-body">
                                        <div class="form-group">
                                            <label for="tags" class="form-label" style="color: #003366;">
                                                <i class="fas fa-hashtag me-1"></i>
                                                Event Tags
                                            </label>
                                            <input type="text" 
                                                   class="form-control @error('tags') is-invalid @enderror" 
                                                   id="tags" 
                                                   name="tags" 
                                                   value="{{ old('tags') }}" 
                                                   placeholder="workshop, training, seminar, technology">
                                            <small class="text-muted d-block mt-1">
                                                Separate tags with commas (max 10 tags).
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Speakers Section - Full Width -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="ju-sub-card mb-4">
                                    <div class="ju-sub-card-header" style="background: linear-gradient(135deg, #003366 0%, #004080 100%);">
                                        <h5 class="mb-0 text-white">
                                            <i class="fas fa-users me-2"></i>
                                            Event Speakers
                                        </h5>
                                    </div>
                                    <div class="ju-sub-card-body">
                                        <div class="alert alert-info" style="background-color: #e6f0ff; border-color: #003366; color: #003366;">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Add speakers to your event. You can specify their session details and role.
                                        </div>
                                        
                                        <div id="speakers-container">
                                            <!-- Speakers will be added here dynamically -->
                                        </div>
                                        
                                        <div class="text-center mt-3">
                                            <button type="button" class="btn" style="background-color: #003366; color: white;" id="addSpeakerBtn">
                                                <i class="fas fa-plus-circle me-2"></i>
                                                Add Speaker
                                            </button>
                                        </div>
                                        
                                        <template id="speaker-template">
                                            <div class="speaker-item card mb-3 border" style="border-color: #003366 !important;">
                                                <div class="card-header" style="background-color: #f0f5ff; border-bottom-color: #003366;">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <h6 class="mb-0" style="color: #003366;">
                                                            <i class="fas fa-user-tie me-2"></i>
                                                            Speaker <span class="speaker-number"></span>
                                                        </h6>
                                                        <button type="button" class="btn btn-sm btn-outline-danger remove-speaker">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <input type="hidden" name="speakers[INDEX][speaker_id]" class="speaker-id-input">
                                                    
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label required" style="color: #003366;">Select Speaker</label>
                                                                <select class="form-select speaker-select" required>
                                                                    <option value="">Select a speaker</option>
                                                                    @foreach($speakers as $speaker)
                                                                        <option value="{{ $speaker->id }}" 
                                                                                data-name="{{ $speaker->name }}"
                                                                                data-title="{{ $speaker->title }}"
                                                                                data-organization="{{ $speaker->organization }}"
                                                                                data-photo="{{ $speaker->photo_url }}">
                                                                            {{ $speaker->name }} - {{ $speaker->title ?? '' }} {{ $speaker->organization ? '('.$speaker->organization.')' : '' }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-6">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label" style="color: #003366;">Session Title</label>
                                                                <input type="text" 
                                                                       class="form-control" 
                                                                       name="speakers[INDEX][session_title]" 
                                                                       placeholder="e.g., Keynote Address, Workshop Session">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label" style="color: #003366;">Session Time</label>
                                                                <input type="datetime-local" 
                                                                       class="form-control" 
                                                                       name="speakers[INDEX][session_time]">
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label" style="color: #003366;">Duration (minutes)</label>
                                                                <input type="number" 
                                                                       class="form-control" 
                                                                       name="speakers[INDEX][session_duration]" 
                                                                       min="1" 
                                                                       placeholder="e.g., 60">
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label" style="color: #003366;">Order</label>
                                                                <input type="number" 
                                                                       class="form-control" 
                                                                       name="speakers[INDEX][order]" 
                                                                       value="0" 
                                                                       min="0">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group mb-3">
                                                        <label class="form-label" style="color: #003366;">Session Description</label>
                                                        <textarea class="form-control" 
                                                                  name="speakers[INDEX][session_description]" 
                                                                  rows="2" 
                                                                  placeholder="Describe the speaker's session..."></textarea>
                                                    </div>
                                                    
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-check">
                                                                <input class="form-check-input" 
                                                                       type="checkbox" 
                                                                       name="speakers[INDEX][is_keynote]" 
                                                                       value="1"
                                                                       id="keynote-INDEX">
                                                                <label class="form-check-label" for="keynote-INDEX" style="color: #003366;">
                                                                    Keynote Speaker
                                                                </label>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-4">
                                                            <div class="form-check">
                                                                <input class="form-check-input" 
                                                                       type="checkbox" 
                                                                       name="speakers[INDEX][is_moderator]" 
                                                                       value="1"
                                                                       id="moderator-INDEX">
                                                                <label class="form-check-label" for="moderator-INDEX" style="color: #003366;">
                                                                    Moderator
                                                                </label>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-4">
                                                            <div class="form-check">
                                                                <input class="form-check-input" 
                                                                       type="checkbox" 
                                                                       name="speakers[INDEX][is_panelist]" 
                                                                       value="1"
                                                                       id="panelist-INDEX">
                                                                <label class="form-check-label" for="panelist-INDEX" style="color: #003366;">
                                                                    Panelist
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row mt-4 wizard-step-section" data-step="3">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded border">
                                    <div>
                                        <small class="text-muted">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            Fields marked with <span class="text-danger">*</span> are required.
                                        </small>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-outline-primary" id="wizardPrevBtn">
                                            <i class="fas fa-arrow-left me-2"></i>
                                            Previous
                                        </button>
                                        <button type="button" class="btn btn-primary" id="wizardNextBtn">
                                            Next
                                            <i class="fas fa-arrow-right ms-2"></i>
                                        </button>
                                        <a href="{{ route('admin.events.index') }}" 
                                           class="btn btn-outline-secondary">
                                            <i class="fas fa-times me-2"></i>
                                            Cancel
                                        </a>
                                        <button type="submit" class="btn d-none" id="wizardSubmitBtn" style="background-color: #003366; color: white;">
                                            <i class="fas fa-save me-2"></i>
                                            Create Event
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .required::after {
        content: " *";
        color: #dc3545;
    }
    
    .ju-sub-card {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .ju-sub-card-header {
        color: white;
        padding: 12px 20px;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    
    .ju-sub-card-body {
        padding: 20px;
    }
    
    .upload-area {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .upload-area:hover {
        border-color: #003366;
        background-color: rgba(0, 51, 102, 0.05);
    }
    
    .upload-area.dragover {
        border-color: #003366;
        background-color: rgba(0, 51, 102, 0.1);
    }
    
    .image-preview img {
        max-height: 200px;
        object-fit: cover;
    }
    
    .preview-container {
        position: relative;
        max-width: 300px;
        margin: 0 auto;
    }
    
    .preview-container .btn {
        width: 30px;
        height: 30px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }
    
    .speaker-item .card-header {
        border-bottom-width: 2px;
    }

    .event-wizard {
        background: linear-gradient(145deg, #f7fbff 0%, #eef4ff 100%);
        border: 1px solid #dbe7ff;
        border-radius: 14px;
        padding: 1rem 1.25rem;
    }

    .wizard-progress-track {
        width: 100%;
        height: 8px;
        background: #d9e4f7;
        border-radius: 999px;
        overflow: hidden;
        margin-bottom: 1rem;
    }

    .wizard-progress-fill {
        width: 33.33%;
        height: 100%;
        background: linear-gradient(90deg, #003366 0%, #0050a0 100%);
        transition: width 0.25s ease;
    }

    .wizard-steps {
        display: flex;
        justify-content: space-between;
        gap: 0.75rem;
    }

    .wizard-step {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #6c757d;
        font-weight: 600;
    }

    .wizard-step span {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #b6c6e8;
        background: #fff;
        color: #003366;
    }

    .wizard-step.active {
        color: #003366;
    }

    .wizard-step.active span {
        border-color: #003366;
        background: #003366;
        color: #fff;
    }

    .wizard-step.completed span {
        border-color: #198754;
        background: #198754;
        color: #fff;
    }

    .wizard-step small {
        font-size: 0.8rem;
    }
    
    .form-check-input:checked {
        background-color: #003366;
        border-color: #003366;
    }

    @media (max-width: 768px) {
        .wizard-step small {
            display: none;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let currentWizardStep = 1;
        const totalWizardSteps = 3;
        const wizardSections = document.querySelectorAll('.wizard-step-section');
        const wizardSteps = document.querySelectorAll('.wizard-step');
        const wizardPrevBtn = document.getElementById('wizardPrevBtn');
        const wizardNextBtn = document.getElementById('wizardNextBtn');
        const wizardSubmitBtn = document.getElementById('wizardSubmitBtn');
        const wizardProgressFill = document.getElementById('wizardProgressFill');

        function updateWizardUI() {
            wizardSections.forEach(section => {
                const step = Number(section.dataset.step);
                section.classList.toggle('d-none', step !== currentWizardStep);
            });

            wizardSteps.forEach((stepNode, idx) => {
                const step = idx + 1;
                stepNode.classList.remove('active', 'completed');
                if (step < currentWizardStep) stepNode.classList.add('completed');
                if (step === currentWizardStep) stepNode.classList.add('active');
            });

            if (wizardProgressFill) {
                wizardProgressFill.style.width = `${(currentWizardStep / totalWizardSteps) * 100}%`;
            }

            if (wizardPrevBtn) wizardPrevBtn.classList.toggle('d-none', currentWizardStep === 1);
            if (wizardNextBtn) wizardNextBtn.classList.toggle('d-none', currentWizardStep === totalWizardSteps);
            if (wizardSubmitBtn) wizardSubmitBtn.classList.toggle('d-none', currentWizardStep !== totalWizardSteps);
        }

        function validateCurrentStep() {
            const currentSections = Array.from(wizardSections).filter(s => Number(s.dataset.step) === currentWizardStep);
            const requiredInputs = currentSections
                .flatMap(section => Array.from(section.querySelectorAll('input, select, textarea')))
                .filter(input => input.required && !input.disabled && input.offsetParent !== null);

            for (const input of requiredInputs) {
                if (!input.checkValidity()) {
                    input.reportValidity();
                    return false;
                }
            }
            return true;
        }

        wizardNextBtn?.addEventListener('click', function() {
            if (!validateCurrentStep()) return;
            if (currentWizardStep < totalWizardSteps) {
                currentWizardStep += 1;
                updateWizardUI();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });

        wizardPrevBtn?.addEventListener('click', function() {
            if (currentWizardStep > 1) {
                currentWizardStep -= 1;
                updateWizardUI();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });

        updateWizardUI();

        let speakerCount = 0;
        const speakersContainer = document.getElementById('speakers-container');
        const speakerTemplate = document.getElementById('speaker-template');
        const addSpeakerBtn = document.getElementById('addSpeakerBtn');
        
        // Add speaker function
        function addSpeaker() {
            const template = speakerTemplate.content.cloneNode(true);
            const speakerItem = template.querySelector('.speaker-item');
            const html = speakerItem.outerHTML.replace(/INDEX/g, speakerCount);
            
            speakersContainer.insertAdjacentHTML('beforeend', html);
            
            const newSpeaker = speakersContainer.lastElementChild;
            const select = newSpeaker.querySelector('.speaker-select');
            const speakerIdInput = newSpeaker.querySelector('.speaker-id-input');
            const speakerNumber = newSpeaker.querySelector('.speaker-number');
            
            speakerNumber.textContent = speakerCount + 1;
            
            // Update speaker ID when select changes
            select.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                speakerIdInput.value = this.value;
                
                // Auto-fill some fields based on speaker data
                if (this.value) {
                    // You could auto-fill session title or other fields here if needed
                }
            });
            
            // Remove speaker
            const removeBtn = newSpeaker.querySelector('.remove-speaker');
            removeBtn.addEventListener('click', function() {
                newSpeaker.remove();
                updateSpeakerNumbers();
            });
            
            speakerCount++;
        }
        
        // Update speaker numbers after removal
        function updateSpeakerNumbers() {
            const speakers = document.querySelectorAll('.speaker-item');
            speakers.forEach((speaker, index) => {
                const numberSpan = speaker.querySelector('.speaker-number');
                if (numberSpan) {
                    numberSpan.textContent = index + 1;
                }
                
                // Update all input names with new index
                const inputs = speaker.querySelectorAll('input, select, textarea');
                inputs.forEach(input => {
                    const name = input.getAttribute('name');
                    if (name) {
                        const newName = name.replace(/\[\d+\]/, '[' + index + ']');
                        input.setAttribute('name', newName);
                    }
                });
                
                // Update checkbox IDs
                const checkboxes = speaker.querySelectorAll('input[type="checkbox"]');
                checkboxes.forEach(checkbox => {
                    const id = checkbox.getAttribute('id');
                    if (id) {
                        const newId = id.replace(/-\d+/, '-' + index);
                        checkbox.setAttribute('id', newId);
                        
                        // Update associated label
                        const label = speaker.querySelector('label[for="' + id + '"]');
                        if (label) {
                            label.setAttribute('for', newId);
                        }
                    }
                });
            });
            
            speakerCount = speakers.length;
        }
        
        addSpeakerBtn.addEventListener('click', addSpeaker);
        
        // Image upload with drag & drop
        const dropArea = document.getElementById('dropArea');
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('imagePreview');
        const previewImage = document.getElementById('previewImage');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight() {
            dropArea.classList.add('dragover');
        }
        
        function unhighlight() {
            dropArea.classList.remove('dragover');
        }
        
        dropArea.addEventListener('drop', handleDrop, false);
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            handleFiles(files);
        }
        
        imageInput.addEventListener('change', function() {
            handleFiles(this.files);
        });
        
        function handleFiles(files) {
            if (files.length > 0) {
                const file = files[0];
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        imagePreview.classList.remove('d-none');
                    };
                    reader.readAsDataURL(file);
                } else {
                    alert('Please select an image file.');
                }
            }
        }
        
        // Campus, Building, Venue cascading dropdowns
        const campusSelect = document.getElementById('campus_id');
        const buildingSelect = document.getElementById('building_id');
        const venueSelect = document.getElementById('venue_id');
        const oldCampusId = @json(old('campus_id'));
        const oldBuildingId = @json(old('building_id'));
        const oldVenueId = @json(old('venue_id'));

        function resetBuildingSelect(message = 'Select Building (Optional)') {
            buildingSelect.innerHTML = `<option value="">${message}</option>`;
        }

        function resetVenueSelect(message = 'Select Venue (Optional)') {
            venueSelect.innerHTML = `<option value="">${message}</option>`;
        }

        async function loadBuildings(campusId, selectedBuildingId = null) {
            resetBuildingSelect('Loading buildings...');
            resetVenueSelect();

            if (!campusId) {
                resetBuildingSelect();
                return;
            }

            try {
                const response = await fetch(`/admin/events/get-buildings/${campusId}`);
                if (!response.ok) throw new Error('Failed to fetch buildings');
                const data = await response.json();

                resetBuildingSelect(data.length ? 'Select Building (Optional)' : 'No buildings found');

                data.forEach(building => {
                    const option = document.createElement('option');
                    option.value = building.id;
                    option.textContent = `${building.name} ${building.code ? `(${building.code})` : ''}`;
                    if (selectedBuildingId && String(selectedBuildingId) === String(building.id)) {
                        option.selected = true;
                    }
                    buildingSelect.appendChild(option);
                });

                if (selectedBuildingId) {
                    await loadVenues(selectedBuildingId, oldVenueId);
                }
            } catch (error) {
                resetBuildingSelect('Unable to load buildings');
                showToast('Failed to load buildings for selected campus.', 'warning');
                console.error('Error loading buildings:', error);
            }
        }

        async function loadVenues(buildingId, selectedVenueId = null) {
            resetVenueSelect('Loading venues...');

            if (!buildingId) {
                resetVenueSelect();
                return;
            }

            try {
                const response = await fetch(`/admin/events/get-venues/${buildingId}`);
                if (!response.ok) throw new Error('Failed to fetch venues');
                const data = await response.json();

                resetVenueSelect(data.length ? 'Select Venue (Optional)' : 'No venues found');

                data.forEach(venue => {
                    const option = document.createElement('option');
                    option.value = venue.id;
                    option.textContent = `${venue.name} (${venue.type}, Capacity: ${venue.capacity})`;
                    if (selectedVenueId && String(selectedVenueId) === String(venue.id)) {
                        option.selected = true;
                    }
                    venueSelect.appendChild(option);
                });
            } catch (error) {
                resetVenueSelect('Unable to load venues');
                showToast('Failed to load venues for selected building.', 'warning');
                console.error('Error loading venues:', error);
            }
        }

        campusSelect.addEventListener('change', function() {
            loadBuildings(this.value);
        });

        buildingSelect.addEventListener('change', function() {
            loadVenues(this.value);
        });

        // Restore dependent dropdown values after validation errors.
        if (oldCampusId) {
            campusSelect.value = oldCampusId;
            loadBuildings(oldCampusId, oldBuildingId);
        }
        
        // Registration link toggle
        const requiresRegistration = document.getElementById('requires_registration');
        const registrationLinkField = document.getElementById('registrationLinkField');
        
        requiresRegistration.addEventListener('change', function() {
            if (this.checked) {
                registrationLinkField.classList.remove('d-none');
            } else {
                registrationLinkField.classList.add('d-none');
                document.getElementById('registration_link').value = '';
            }
        });
        
        // Date validation
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        const localISOTime = now.toISOString().slice(0, 16);
        
        startDateInput.min = localISOTime;
        endDateInput.min = localISOTime;
        
        startDateInput.addEventListener('change', function() {
            if (this.value) {
                endDateInput.min = this.value;
                if (endDateInput.value && endDateInput.value < this.value) {
                    showToast('End date must be after start date', 'warning');
                    endDateInput.value = '';
                }
            }
        });
        
        endDateInput.addEventListener('change', function() {
            if (startDateInput.value && this.value < startDateInput.value) {
                showToast('End date must be after start date', 'warning');
                this.value = '';
            }
        });
        
        // Form validation
        const form = document.getElementById('eventForm');
        form.addEventListener('submit', function(e) {
            if (startDateInput.value && endDateInput.value) {
                const start = new Date(startDateInput.value);
                const end = new Date(endDateInput.value);
                
                if (end <= start) {
                    e.preventDefault();
                    showToast('End date must be after start date', 'error');
                    return;
                }
            }
            
            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Creating...';
            submitBtn.disabled = true;
        });
        
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
            toast.style.top = '20px';
            toast.style.right = '20px';
            toast.style.zIndex = '9999';
            toast.style.minWidth = '300px';
            toast.innerHTML = `
                <strong>${type === 'error' ? 'Error' : type === 'warning' ? 'Warning' : 'Info'}!</strong> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 5000);
        }
        
        function removeImage() {
            imagePreview.classList.add('d-none');
            previewImage.src = '#';
            imageInput.value = '';
        }
        
        window.removeImage = removeImage;
    });
</script>
@endpush
