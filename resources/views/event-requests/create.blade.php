@extends('layouts.app')

@section('title', 'New Event Request - JU Event Management')
@section('page-title', 'New Event Request')
@section('page-subtitle', 'Submit a new event request')

@section('content')
@php
    $isEventManager = $isEventManager ?? auth()->user()->hasRole('event-manager');
@endphp
<div class="container-fluid">
    <div class="card ju-card">
        <div class="card-header ju-card-header">
            <h4 class="mb-0">
                <i class="fas fa-plus-circle me-2"></i>New Event Request
            </h4>
        </div>
        <div class="card-body">
            @if(!$isEventManager)
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Submit basic event details only. The Event Manager will schedule date and venue, then send it for final admin approval.
            </div>
            @endif
            <form action="{{ route('event-requests.store') }}" method="POST" enctype="multipart/form-data" id="eventRequestForm">
                @csrf
                
                <div class="row">
                    <div class="col-md-8">
                        <!-- Event Information -->
                        <div class="card ju-card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Event Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Event Title *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description *</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                                    @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="event_type" class="form-label">Event Type *</label>
                                        <select class="form-control @error('event_type') is-invalid @enderror" 
                                                id="event_type" name="event_type" required>
                                            <option value="">Select type</option>
                                            <option value="academic" {{ old('event_type') == 'academic' ? 'selected' : '' }}>Academic</option>
                                            <option value="cultural" {{ old('event_type') == 'cultural' ? 'selected' : '' }}>Cultural</option>
                                            <option value="sports" {{ old('event_type') == 'sports' ? 'selected' : '' }}>Sports</option>
                                            <option value="conference" {{ old('event_type') == 'conference' ? 'selected' : '' }}>Conference</option>
                                            <option value="workshop" {{ old('event_type') == 'workshop' ? 'selected' : '' }}>Workshop</option>
                                            <option value="seminar" {{ old('event_type') == 'seminar' ? 'selected' : '' }}>Seminar</option>
                                        </select>
                                        @error('event_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="expected_attendees" class="form-label">Expected Attendees</label>
                                        <input type="number" class="form-control @error('expected_attendees') is-invalid @enderror" 
                                               id="expected_attendees" name="expected_attendees" 
                                               value="{{ old('expected_attendees', 50) }}" min="1">
                                        @error('expected_attendees')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Date & Venue -->
                        <div class="card ju-card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Date & Venue</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="proposed_start_date" class="form-label">Start Date *</label>
                                        <input type="date" class="form-control @error('proposed_start_date') is-invalid @enderror" 
                                               id="proposed_start_date" name="proposed_start_date" 
                                               value="{{ old('proposed_start_date') }}" {{ $isEventManager ? 'required' : '' }}>
                                        @error('proposed_start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="proposed_end_date" class="form-label">End Date *</label>
                                        <input type="date" class="form-control @error('proposed_end_date') is-invalid @enderror" 
                                               id="proposed_end_date" name="proposed_end_date" 
                                               value="{{ old('proposed_end_date') }}" {{ $isEventManager ? 'required' : '' }}>
                                        @error('proposed_end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="campus_id" class="form-label">Campus *</label>
                                        <select class="form-control @error('campus_id') is-invalid @enderror" 
                                                id="campus_id" name="campus_id" {{ $isEventManager ? 'required' : '' }} onchange="loadBuildings(this.value)">
                                            <option value="">Select campus</option>
                                            @foreach($campuses as $campus)
                                                <option value="{{ $campus->id }}" {{ old('campus_id') == $campus->id ? 'selected' : '' }}>
                                                    {{ $campus->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('campus_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="building_id" class="form-label">Building</label>
                                        <select class="form-control @error('building_id') is-invalid @enderror" 
                                                id="building_id" name="building_id" onchange="loadVenues(this.value)">
                                            <option value="">Select building first</option>
                                        </select>
                                        @error('building_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="venue_id" class="form-label">Preferred Venue *</label>
                                        <select class="form-control select2 @error('venue_id') is-invalid @enderror" 
                                                id="venue_id" name="venue_id" {{ $isEventManager ? 'required' : '' }}>
                                            <option value="">Select venue</option>
                                            @foreach($venues as $venue)
                                                <option value="{{ $venue->id }}" {{ old('venue_id') == $venue->id ? 'selected' : '' }}
                                                    data-capacity="{{ $venue->capacity }}"
                                                    data-building="{{ $venue->building->name ?? 'N/A' }}"
                                                    data-campus="{{ $venue->building->campus->name ?? 'N/A' }}"
                                                    data-type="{{ $venue->type }}">
                                                    {{ $venue->name }} (Capacity: {{ $venue->capacity }} | {{ ucfirst($venue->type) }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('venue_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Select your preferred venue from available options</small>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <!-- Venue Details Display -->
                                        <div class="card ju-card mb-3" id="venueDetailsCard" style="display: none;">
                                            <div class="card-body">
                                                <h6 class="mb-3">Venue Details</h6>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <p class="mb-1"><strong>Type:</strong> <span id="venueType"></span></p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p class="mb-1"><strong>Capacity:</strong> <span id="venueCapacity"></span></p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p class="mb-1"><strong>Building:</strong> <span id="venueBuilding"></span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Alternative venue input (if venue not found in dropdown) -->
                                <div class="mb-3">
                                    <label for="alternative_venue" class="form-label">Alternative Venue Request</label>
                                    <input type="text" class="form-control" 
                                           id="alternative_venue" name="alternative_venue" 
                                           value="{{ old('alternative_venue') }}"
                                           placeholder="If your preferred venue is not listed, describe it here">
                                    <small class="text-muted">Use this only if your venue is not in the dropdown above</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <!-- Organizer Information -->
                        <div class="card ju-card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Organizer Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="organizer_name" class="form-label">Organizer Name *</label>
                                    <input type="text" class="form-control @error('organizer_name') is-invalid @enderror" 
                                           id="organizer_name" name="organizer_name" 
                                           value="{{ old('organizer_name', auth()->user()->name) }}"
                                           {{ $isEventManager ? 'required' : '' }}
                                           {{ !$isEventManager ? 'readonly' : '' }}>
                                    @error('organizer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="organizer_email" class="form-label">Organizer Email *</label>
                                    <input type="email" class="form-control @error('organizer_email') is-invalid @enderror" 
                                           id="organizer_email" name="organizer_email" 
                                           value="{{ old('organizer_email', auth()->user()->email) }}"
                                           {{ $isEventManager ? 'required' : '' }}
                                           {{ !$isEventManager ? 'readonly' : '' }}>
                                    @error('organizer_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="organizer_phone" class="form-label">Organizer Phone</label>
                                    <input type="tel" class="form-control @error('organizer_phone') is-invalid @enderror" 
                                           id="organizer_phone" name="organizer_phone" 
                                           value="{{ old('organizer_phone') }}">
                                    @error('organizer_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Additional Requirements -->
                        <div class="card ju-card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Additional Requirements</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="additional_requirements" class="form-label">Special Requirements</label>
                                    <textarea class="form-control" id="additional_requirements" 
                                              name="additional_requirements" rows="3">{{ old('additional_requirements') }}</textarea>
                                    <small class="text-muted">Any special equipment, setup, or other requirements</small>
                                </div>

                                <div class="mb-3">
                                    <label for="event_image" class="form-label">Event Image</label>
                                    <input type="file"
                                           class="form-control @error('event_image') is-invalid @enderror"
                                           id="event_image"
                                           name="event_image"
                                           accept=".jpg,.jpeg,.png,.gif,.webp,image/*">
                                    @error('event_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Upload a poster/banner image (max 5MB).</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="card ju-card">
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-ju" id="submitBtn">
                                        <i class="fas fa-paper-plane me-1"></i> Submit Request
                                    </button>
                                    <a href="{{ route('event-requests.index') }}" class="btn btn-ju-outline">
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

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container .select2-selection--single {
        height: 38px;
        padding: 5px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
    .select2-container--default .select2-selection--single {
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2
    $('#venue_id').select2({
        theme: 'bootstrap-5',
        placeholder: 'Select a venue',
        allowClear: true,
        width: '100%'
    });
    
    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('proposed_start_date').min = today;
    document.getElementById('proposed_end_date').min = today;
    
    // Handle venue selection change
    $('#venue_id').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const capacity = selectedOption.data('capacity');
        const building = selectedOption.data('building');
        const campus = selectedOption.data('campus');
        const type = selectedOption.data('type');
        
        if (capacity) {
            $('#venueDetailsCard').show();
            $('#venueCapacity').text(capacity);
            $('#venueBuilding').text(building);
            $('#venueType').text(type.charAt(0).toUpperCase() + type.slice(1));
            
            // Update expected attendees field with venue capacity as max
            const attendeesInput = document.getElementById('expected_attendees');
            if (attendeesInput.value > capacity) {
                attendeesInput.value = capacity;
                showToast('Expected attendees adjusted to venue capacity', 'info');
            }
            attendeesInput.max = capacity;
        } else {
            $('#venueDetailsCard').hide();
        }
    });
    
    // Load buildings when campus is selected
    @if(old('campus_id'))
        loadBuildings({{ old('campus_id') }}, {{ old('building_id') }});
    @endif
    
    // Form validation
    document.getElementById('eventRequestForm').addEventListener('submit', function(e) {
        const venueId = document.getElementById('venue_id').value;
        const alternativeVenue = document.getElementById('alternative_venue').value;
        
        if (!venueId && !alternativeVenue.trim()) {
            e.preventDefault();
            showToast('Please select a venue or provide an alternative venue request', 'error');
            return false;
        }
        
        // Show loading state
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Submitting...';
    });
});

function loadBuildings(campusId, selectedBuildingId = null) {
    if (!campusId) {
        $('#building_id').html('<option value="">Select campus first</option>');
        return;
    }
    
    $('#building_id').html('<option value="">Loading...</option>');
    
    fetch(`/admin/buildings/by-campus/${campusId}`)
        .then(response => response.json())
        .then(data => {
            let options = '<option value="">Select building</option>';
            data.forEach(building => {
                const selected = (selectedBuildingId && building.id == selectedBuildingId) ? 'selected' : '';
                options += `<option value="${building.id}" ${selected}>${building.name} ${building.code ? '(' + building.code + ')' : ''}</option>`;
            });
            $('#building_id').html(options);
            
            // Load venues if building was previously selected
            if (selectedBuildingId) {
                loadVenues(selectedBuildingId);
            }
        })
        .catch(error => {
            console.error('Error loading buildings:', error);
            $('#building_id').html('<option value="">Error loading buildings</option>');
        });
}

function loadVenues(buildingId) {
    if (!buildingId) {
        return;
    }
    
    // Show loading in venues dropdown
    const venueSelect = $('#venue_id');
    const currentVal = venueSelect.val();
    venueSelect.prop('disabled', true);
    
    fetch(`/admin/events/get-venues/${buildingId}`)
        .then(response => response.json())
        .then(data => {
            let options = '<option value="">Select venue</option>';
            data.forEach(venue => {
                const selected = (currentVal && venue.id == currentVal) ? 'selected' : '';
                options += `<option value="${venue.id}" ${selected}
                    data-capacity="${venue.capacity}"
                    data-type="${venue.type}">${venue.name} (Capacity: ${venue.capacity} | ${venue.type.charAt(0).toUpperCase() + venue.type.slice(1)})</option>`;
            });
            
            venueSelect.html(options);
            venueSelect.prop('disabled', false);
            
            // Reinitialize Select2
            venueSelect.select2({
                theme: 'bootstrap-5',
                placeholder: 'Select a venue',
                allowClear: true,
                width: '100%'
            });
            
            // Trigger change if value was restored
            if (currentVal) {
                venueSelect.val(currentVal).trigger('change');
            }
        })
        .catch(error => {
            console.error('Error loading venues:', error);
            venueSelect.html('<option value="">Error loading venues</option>');
            venueSelect.prop('disabled', false);
        });
}

// Toast notification function
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} alert-dismissible fade show`;
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('.toast-container') || document.body;
    container.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 5000);
}
</script>
@endpush
@endsection
