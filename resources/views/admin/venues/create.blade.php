@extends('layouts.app')

@section('title', 'Create New Venue')
@section('page-title', 'Create New Venue')
@section('page-subtitle', 'Add a new hall, classroom, or auditorium')

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.venues.index') }}">Venues</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="ju-card">
                <div class="ju-card-header">
                    <h5 class="ju-card-title">Venue Details</h5>
                </div>
                <div class="ju-card-body">
                    <form action="{{ route('admin.venues.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="building_id" class="form-label">Building *</label>
                                <select class="form-control select2 @error('building_id') is-invalid @enderror" 
                                        id="building_id" name="building_id" required>
                                    <option value="">Select Building</option>
                                    @foreach($buildings as $building)
                                    <option value="{{ $building->id }}" {{ old('building_id') == $building->id ? 'selected' : '' }}>
                                        {{ $building->name }} ({{ $building->campus->name }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('building_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Venue Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">Venue Type *</label>
                                <select class="form-control @error('type') is-invalid @enderror" 
                                        id="type" name="type" required>
                                    <option value="">Select Type</option>
                                    @foreach($venueTypes as $key => $value)
                                    <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="capacity" class="form-label">Capacity *</label>
                                <input type="number" class="form-control @error('capacity') is-invalid @enderror" 
                                       id="capacity" name="capacity" value="{{ old('capacity', 50) }}" min="1" required>
                                @error('capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Maximum number of people</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Amenities</label>
                            <div class="row">
                                @php
                                    $commonAmenities = ['Projector', 'Whiteboard', 'Sound System', 'WiFi', 'Air Conditioning', 'Microphone', 'Stage', 'Lighting', 'Video Conference', 'Recording Equipment'];
                                @endphp
                                @foreach($commonAmenities as $amenity)
                                <div class="col-md-4 col-sm-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               id="amenity_{{ Str::slug($amenity) }}" 
                                               name="amenities[]" value="{{ $amenity }}"
                                               {{ in_array($amenity, old('amenities', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="amenity_{{ Str::slug($amenity) }}">
                                            {{ $amenity }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <small class="form-text text-muted">Select available amenities</small>
                            @error('amenities')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="booking_fee" class="form-label">Booking Fee (ETB)</label>
                                <input type="number" class="form-control @error('booking_fee') is-invalid @enderror" 
                                       id="booking_fee" name="booking_fee" value="{{ old('booking_fee') }}" min="0" step="0.01">
                                @error('booking_fee')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Leave empty for free venues</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" 
                                           id="is_available" name="is_available" value="1" checked>
                                    <label class="form-check-label" for="is_available">Available for Booking</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" 
                                           id="requires_approval" name="requires_approval" value="1">
                                    <label class="form-check-label" for="requires_approval">Requires Approval</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="available_hours" class="form-label">Available Hours (Optional)</label>
                            <div class="row">
                                @php
                                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                @endphp
                                @foreach($days as $day)
                                <div class="col-md-4 mb-2">
                                    <label class="form-label small">{{ $day }}</label>
                                    <input type="text" class="form-control form-control-sm" 
                                           name="available_hours[{{ strtolower($day) }}]" 
                                           value="{{ old('available_hours.' . strtolower($day), '08:00-17:00') }}"
                                           placeholder="e.g., 08:00-17:00">
                                </div>
                                @endforeach
                            </div>
                            <small class="form-text text-muted">Format: HH:MM-HH:MM (24-hour format)</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.venues.index') }}" class="btn btn-ju-outline">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-ju">
                                <i class="fas fa-save me-1"></i> Create Venue
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="ju-card">
                <div class="ju-card-header">
                    <h5 class="ju-card-title"><i class="fas fa-lightbulb text-warning me-2"></i> Quick Tips</h5>
                </div>
                <div class="ju-card-body">
                    <h6>Venue Setup Guidelines</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><i class="fas fa-check text-success me-1"></i> Set accurate capacity for safety</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-1"></i> List all available amenities</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-1"></i> Mark unavailable venues during maintenance</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-1"></i> Set appropriate booking fees if applicable</li>
                    </ul>
                    
                    <hr>
                    
                    <h6><i class="fas fa-info-circle text-info me-2"></i> Common Venue Types</h6>
                    <div class="row small">
                        @foreach($venueTypes as $key => $value)
                        <div class="col-6 mb-2">
                            <span class="badge bg-light text-dark">{{ $value }}</span>
                        </div>
                        @endforeach
                    </div>
                    
                    <hr>
                    
                    <h6><i class="fas fa-calendar text-primary me-2"></i> Usage</h6>
                    <p class="small">Once created, venues can be:</p>
                    <ol class="small">
                        <li>Assigned to events</li>
                        <li>Scheduled for bookings</li>
                        <li>Checked for availability</li>
                        <li>Managed for maintenance</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .form-check-input:checked {
        background-color: var(--ju-green);
        border-color: var(--ju-green);
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap5',
            width: '100%'
        });
        
        // If building_id is passed in URL, select it
        const urlParams = new URLSearchParams(window.location.search);
        const buildingId = urlParams.get('building_id');
        if (buildingId) {
            $('#building_id').val(buildingId).trigger('change');
        }
    });
</script>
@endpush