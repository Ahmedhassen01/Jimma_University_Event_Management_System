@extends('layouts.app')

@section('title', 'Edit Event Request - JU Event Management')

@section('content')
@php
    $isEventManager = auth()->user()->hasRole('event-manager');
@endphp
<div class="container-fluid px-4">
    <div class="row my-4">
        <div class="col-12">
            <div class="card ju-card">
                <div class="card-header ju-card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Edit Event Request
                    </h4>
                    <div>
                        <a href="{{ route('event-requests.show', $eventRequest) }}" class="btn ju-btn-outline me-2">
                            <i class="fas fa-eye me-1"></i> View
                        </a>
                        <a href="{{ route('event-requests.index') }}" class="btn ju-btn-outline">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <strong>Please fix the following:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <form action="{{ route('event-requests.update', $eventRequest) }}" method="POST" enctype="multipart/form-data" id="eventRequestForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-8">
                                <!-- Event Information -->
                                <div class="card ju-card-light mb-4">
                                    <div class="card-header ju-card-subheader">
                                        <h5 class="mb-0">Event Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="title" class="form-label">Event Title *</label>
                                            <input type="text" class="form-control ju-input @error('title') is-invalid @enderror" 
                                                   id="title" name="title" value="{{ old('title', $eventRequest->title) }}" required>
                                            @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Event Description *</label>
                                            <textarea class="form-control ju-input @error('description') is-invalid @enderror" 
                                                      id="description" name="description" rows="4" required>{{ old('description', $eventRequest->description) }}</textarea>
                                            @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="event_type" class="form-label">Event Type *</label>
                                                <select class="form-select ju-input @error('event_type') is-invalid @enderror" 
                                                        id="event_type" name="event_type" required>
                                                    <option value="">Select event type</option>
                                                    <option value="academic" {{ old('event_type', $eventRequest->event_type) == 'academic' ? 'selected' : '' }}>Academic</option>
                                                    <option value="cultural" {{ old('event_type', $eventRequest->event_type) == 'cultural' ? 'selected' : '' }}>Cultural</option>
                                                    <option value="sports" {{ old('event_type', $eventRequest->event_type) == 'sports' ? 'selected' : '' }}>Sports</option>
                                                    <option value="conference" {{ old('event_type', $eventRequest->event_type) == 'conference' ? 'selected' : '' }}>Conference</option>
                                                    <option value="workshop" {{ old('event_type', $eventRequest->event_type) == 'workshop' ? 'selected' : '' }}>Workshop</option>
                                                    <option value="seminar" {{ old('event_type', $eventRequest->event_type) == 'seminar' ? 'selected' : '' }}>Seminar</option>
                                                    <option value="exhibition" {{ old('event_type', $eventRequest->event_type) == 'exhibition' ? 'selected' : '' }}>Exhibition</option>
                                                    <option value="outreach" {{ old('event_type', $eventRequest->event_type) == 'outreach' ? 'selected' : '' }}>Outreach</option>
                                                </select>
                                                @error('event_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="expected_attendees" class="form-label">Expected Attendees *</label>
                                                <input type="number" class="form-control ju-input @error('expected_attendees') is-invalid @enderror" 
                                                       id="expected_attendees" name="expected_attendees" 
                                                       value="{{ old('expected_attendees', $eventRequest->expected_attendees) }}" min="1" required>
                                                @error('expected_attendees')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Date & Venue -->
                                <div class="card ju-card-light mb-4">
                                    <div class="card-header ju-card-subheader">
                                        <h5 class="mb-0">Date & Venue</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="proposed_start_date" class="form-label">Start Date *</label>
                                                <input type="date" class="form-control ju-input @error('proposed_start_date') is-invalid @enderror" 
                                                       id="proposed_start_date" name="proposed_start_date" 
                                                       value="{{ old('proposed_start_date', $eventRequest->proposed_start_date->format('Y-m-d')) }}" required>
                                                @error('proposed_start_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="proposed_end_date" class="form-label">End Date *</label>
                                                <input type="date" class="form-control ju-input @error('proposed_end_date') is-invalid @enderror" 
                                                       id="proposed_end_date" name="proposed_end_date" 
                                                       value="{{ old('proposed_end_date', $eventRequest->proposed_end_date->format('Y-m-d')) }}" required>
                                                @error('proposed_end_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="proposed_venue" class="form-label">Scheduled Venue *</label>
                                                <input type="text"
                                                       class="form-control ju-input @error('proposed_venue') is-invalid @enderror"
                                                       id="proposed_venue"
                                                       name="proposed_venue"
                                                       value="{{ old('proposed_venue', $eventRequest->proposed_venue) }}"
                                                       placeholder="Enter finalized venue"
                                                       required>
                                                @error('proposed_venue')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="proposed_campus" class="form-label">Campus</label>
                                                <select class="form-select ju-input @error('proposed_campus') is-invalid @enderror" 
                                                        id="proposed_campus" name="proposed_campus">
                                                    <option value="">Select campus</option>
                                                    <option value="Main Campus" {{ old('proposed_campus', $eventRequest->proposed_campus) == 'Main Campus' ? 'selected' : '' }}>Main Campus</option>
                                                    <option value="Technology Campus" {{ old('proposed_campus', $eventRequest->proposed_campus) == 'Technology Campus' ? 'selected' : '' }}>Technology Campus</option>
                                                    <option value="Medical Campus" {{ old('proposed_campus', $eventRequest->proposed_campus) == 'Medical Campus' ? 'selected' : '' }}>Medical Campus</option>
                                                </select>
                                                @error('proposed_campus')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Additional Requirements -->
                                <div class="card ju-card-light mb-4">
                                    <div class="card-header ju-card-subheader">
                                        <h5 class="mb-0">Additional Requirements</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="additional_requirements" class="form-label">Special Requirements</label>
                                            <textarea class="form-control ju-input" id="additional_requirements" 
                                                      name="additional_requirements" rows="3">{{ old('additional_requirements', $eventRequest->additional_requirements_text) }}</textarea>
                                        </div>

                                        @if($eventRequest->requested_image_url)
                                        <div class="mb-3">
                                            <label class="form-label">Current Event Image</label>
                                            <div>
                                                <img src="{{ $eventRequest->requested_image_url }}"
                                                     alt="{{ $eventRequest->title }}"
                                                     class="img-fluid rounded border"
                                                     style="max-height: 180px; object-fit: cover;">
                                            </div>
                                        </div>
                                        @endif

                                        <div class="mb-3">
                                            <label for="event_image" class="form-label">Upload/Replace Event Image</label>
                                            <input type="file"
                                                   class="form-control ju-input @error('event_image') is-invalid @enderror"
                                                   id="event_image"
                                                   name="event_image"
                                                   accept=".jpg,.jpeg,.png,.gif,.webp,image/*">
                                            @error('event_image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Optional. Upload a new poster/banner image (max 5MB).</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <!-- Current Status -->
                                <div class="card ju-card-light mb-4">
                                    <div class="card-header ju-card-subheader">
                                        <h5 class="mb-0">Current Status</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-info">
                                            <p class="mb-2"><strong>Status:</strong> 
                                                <span class="badge bg-{{ $eventRequest->status_color }}">
                                                    {{ ucfirst($eventRequest->status) }}
                                                </span>
                                            </p>
                                            <p class="mb-2"><strong>Submitted:</strong> 
                                                {{ $eventRequest->created_at->format('M d, Y') }}
                                            </p>
                                            <p class="mb-0"><strong>Last Updated:</strong> 
                                                {{ $eventRequest->updated_at->format('M d, Y') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Form Actions -->
                                <div class="card ju-card-light">
                                    <div class="card-body">
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn ju-btn-primary">
                                                <i class="fas fa-save me-1"></i> Update Request
                                            </button>
                                            @if($isEventManager && $eventRequest->status === 'manager_review')
                                            <button type="submit" class="btn btn-success" name="submit_for_approval" value="1">
                                                <i class="fas fa-paper-plane me-1"></i> Send For Admin Approval
                                            </button>
                                            <a href="{{ route('event-requests.show', $eventRequest) }}" class="btn btn-danger">
                                                <i class="fas fa-times-circle me-1"></i> Reject From Details
                                            </a>
                                            @endif
                                            <a href="{{ route('event-requests.show', $eventRequest) }}" 
                                               class="btn ju-btn-outline">
                                                <i class="fas fa-times me-1"></i> Cancel
                                            </a>
                                        </div>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.getElementById('eventRequestForm');
    
    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('proposed_start_date').min = today;
    document.getElementById('proposed_end_date').min = today;
    
    // Form submission validation
    form.addEventListener('submit', function(e) {
        const startDate = new Date(document.getElementById('proposed_start_date').value);
        const endDate = new Date(document.getElementById('proposed_end_date').value);
        
        if (endDate < startDate) {
            e.preventDefault();
            alert('End date cannot be before start date');
            return false;
        }
        
        return true;
    });
});
</script>
@endpush
@endsection
