@extends('layouts.app')

@section('title', 'Edit Building')
@section('page-title', 'Edit Building')
@section('page-subtitle', 'Update building information')

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.buildings.index') }}">Buildings</a></li>
    <li class="breadcrumb-item active">Edit {{ $building->name }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="ju-card">
                <div class="ju-card-header">
                    <h5 class="ju-card-title">Edit Building Details</h5>
                </div>
                <div class="ju-card-body">
                    <form action="{{ route('admin.buildings.update', $building) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="campus_id" class="form-label">Campus *</label>
                                <select class="form-control @error('campus_id') is-invalid @enderror" 
                                        id="campus_id" name="campus_id" required>
                                    <option value="">Select Campus</option>
                                    @foreach($campuses as $campus)
                                    <option value="{{ $campus->id }}" {{ old('campus_id', $building->campus_id) == $campus->id ? 'selected' : '' }}>
                                        {{ $campus->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('campus_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Building Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $building->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="code" class="form-label">Building Code</label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                       id="code" name="code" value="{{ old('code', $building->code) }}" placeholder="e.g., SCI-01, ADM-01">
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="floors" class="form-label">Number of Floors</label>
                                <input type="number" class="form-control @error('floors') is-invalid @enderror" 
                                       id="floors" name="floors" value="{{ old('floors', $building->floors) }}" min="1" max="50">
                                @error('floors')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $building->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="contact_person" class="form-label">Contact Person</label>
                                <input type="text" class="form-control @error('contact_person') is-invalid @enderror" 
                                       id="contact_person" name="contact_person" value="{{ old('contact_person', $building->contact_person) }}">
                                @error('contact_person')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="contact_phone" class="form-label">Contact Phone</label>
                                <input type="text" class="form-control @error('contact_phone') is-invalid @enderror" 
                                       id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $building->contact_phone) }}">
                                @error('contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" 
                                       id="is_active" name="is_active" value="1" {{ old('is_active', $building->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active Building</label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.buildings.index') }}" class="btn btn-ju-outline">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-ju">
                                <i class="fas fa-save me-1"></i> Update Building
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="ju-card">
                <div class="ju-card-header">
                    <h5 class="ju-card-title">Building Information</h5>
                </div>
                <div class="ju-card-body">
                    <div class="mb-3">
                        <h6>Campus</h6>
                        <p>{{ $building->campus->name }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6>Current Status</h6>
                        <p>
                            @if($building->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <h6>Venues Count</h6>
                        <p class="h4">{{ $building->venues_count }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6>Created</h6>
                        <p>{{ $building->created_at->format('M d, Y') }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6>Last Updated</h6>
                        <p>{{ $building->updated_at->format('M d, Y') }}</p>
                    </div>
                    
                    <hr>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.buildings.show', $building) }}" class="btn btn-info">
                            <i class="fas fa-eye me-1"></i> View Details
                        </a>
                        <a href="{{ route('admin.venues.index') }}?building_id={{ $building->id }}" class="btn btn-primary">
                            <i class="fas fa-door-open me-1"></i> View Venues
                        </a>
                        <form action="{{ route('admin.buildings.destroy', $building) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash me-1"></i> Delete Building
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection