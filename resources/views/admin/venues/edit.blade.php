@extends('layouts.app')

@section('title', 'Edit Venue')
@section('page-title', 'Edit Venue')
@section('page-subtitle', 'Update venue information')

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.venues.index') }}">Venues</a></li>
    <li class="breadcrumb-item active">Edit {{ $venue->name }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="ju-card">
                <div class="ju-card-header">
                    <h5 class="ju-card-title">Edit Venue Details</h5>
                </div>
                <div class="ju-card-body">
                    <form action="{{ route('admin.venues.update', $venue) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="building_id" class="form-label">Building *</label>
                                <select class="form-control select2 @error('building_id') is-invalid @enderror" 
                                        id="building_id" name="building_id" required>
                                    <option value="">Select Building</option>
                                    @foreach($buildings as $building)
                                    <option value="{{ $building->id }}" {{ old('building_id', $venue->building_id) == $building->id ? 'selected' : '' }}>
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
                                       id="name" name="name" value="{{ old('name', $venue->name) }}" required>
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
                                    <option value="{{ $key }}" {{ old('type', $venue->type) == $key ? 'selected' : '' }}>
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
                                       id="capacity" name="capacity" value="{{ old('capacity', $venue->capacity) }}" min="1" required>
                                @error('capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $venue->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Amenities</label>
                            <div class="row">
                                @php
                                    $commonAmenities = ['Projector', 'Whiteboard', 'Sound System', 'WiFi', 'Air Conditioning', 'Microphone', 'Stage', 'Lighting', 'Video Conference', 'Recording Equipment'];
                                    $currentAmenities = old('amenities', $venue->amenities ?? []);
                                @endphp
                                @foreach($commonAmenities as $amenity)
                                <div class="col-md-4 col-sm-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               id="amenity_{{ Str::slug($amenity) }}" 
                                               name="amenities[]" value="{{ $amenity }}"
                                               {{ in_array($amenity, $currentAmenities) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="amenity_{{ Str::slug($amenity) }}">
                                            {{ $amenity }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="booking_fee" class="form-label">Booking Fee (ETB)</label>
                                <input type="number" class="form-control @error('booking_fee') is-invalid @enderror" 
                                       id="booking_fee" name="booking_fee" value="{{ old('booking_fee', $venue->booking_fee) }}" min="0" step="0.01">
                                @error('booking_fee')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" 
                                           id="is_available" name="is_available" value="1" 
                                           {{ old('is_available', $venue->is_available) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_available">Available for Booking</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" 
                                           id="requires_approval" name="requires_approval" value="1"
                                           {{ old('requires_approval', $venue->requires_approval) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="requires_approval">Requires Approval</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.venues.index') }}" class="btn btn-ju-outline">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-ju">
                                <i class="fas fa-save me-1"></i> Update Venue
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="ju-card">
                <div class="ju-card-header">
                    <h5 class="ju-card-title">Venue Information</h5>
                </div>
                <div class="ju-card-body">
                    <div class="mb-3">
                        <h6>Location</h6>
                        <p>{{ $venue->building->name }}, {{ $venue->building->campus->name }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6>Current Status</h6>
                        <p>
                            @if($venue->is_available)
                                <span class="badge bg-success">Available</span>
                            @else
                                <span class="badge bg-secondary">Unavailable</span>
                            @endif
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <h6>Capacity</h6>
                        <p class="h4">{{ $venue->capacity }} people</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6>Type</h6>
                        <p>
                            @php
                                $venueTypes = [
                                    'classroom' => 'Classroom',
                                    'auditorium' => 'Auditorium',
                                    'hall' => 'Hall',
                                    'lab' => 'Laboratory',
                                    'sports_complex' => 'Sports Complex',
                                    'conference_room' => 'Conference Room',
                                    'seminar_room' => 'Seminar Room',
                                    'open_space' => 'Open Space',
                                ];
                            @endphp
                            <span class="badge bg-info">{{ $venueTypes[$venue->type] ?? ucfirst($venue->type) }}</span>
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <h6>Created</h6>
                        <p>{{ $venue->created_at->format('M d, Y') }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6>Last Updated</h6>
                        <p>{{ $venue->updated_at->format('M d, Y') }}</p>
                    </div>
                    
                    <hr>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.venues.show', $venue) }}" class="btn btn-info">
                            <i class="fas fa-eye me-1"></i> View Details
                        </a>
                        <form action="{{ route('admin.venues.destroy', $venue) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash me-1"></i> Delete Venue
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap5',
            width: '100%'
        });
    });
</script>
@endpush