@extends('layouts.app')

@section('title', 'Create New Building')
@section('page-title', 'Create New Building')
@section('page-subtitle', 'Add a new building to a campus')

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.buildings.index') }}">Buildings</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="ju-card">
                <div class="ju-card-header">
                    <h5 class="ju-card-title">Building Details</h5>
                </div>
                <div class="ju-card-body">
                    <form action="{{ route('admin.buildings.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="campus_id" class="form-label">Campus *</label>
                                <select class="form-control @error('campus_id') is-invalid @enderror" 
                                        id="campus_id" name="campus_id" required>
                                    <option value="">Select Campus</option>
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
                                <label for="name" class="form-label">Building Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="code" class="form-label">Building Code</label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                       id="code" name="code" value="{{ old('code') }}" placeholder="e.g., SCI-01, ADM-01">
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Short code for easy identification</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="floors" class="form-label">Number of Floors</label>
                                <input type="number" class="form-control @error('floors') is-invalid @enderror" 
                                       id="floors" name="floors" value="{{ old('floors', 1) }}" min="1" max="50">
                                @error('floors')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="contact_person" class="form-label">Contact Person</label>
                                <input type="text" class="form-control @error('contact_person') is-invalid @enderror" 
                                       id="contact_person" name="contact_person" value="{{ old('contact_person') }}">
                                @error('contact_person')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="contact_phone" class="form-label">Contact Phone</label>
                                <input type="text" class="form-control @error('contact_phone') is-invalid @enderror" 
                                       id="contact_phone" name="contact_phone" value="{{ old('contact_phone') }}">
                                @error('contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" 
                                       id="is_active" name="is_active" value="1" checked>
                                <label class="form-check-label" for="is_active">Active Building</label>
                            </div>
                            <small class="form-text text-muted">Inactive buildings won't be available for venue assignment.</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.buildings.index') }}" class="btn btn-ju-outline">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-ju">
                                <i class="fas fa-save me-1"></i> Create Building
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
                    <h6>Building Setup Guidelines</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><i class="fas fa-check text-success me-1"></i> Use descriptive building names</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-1"></i> Add building codes for easy reference</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-1"></i> Include accurate floor count</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-1"></i> Assign contact person for maintenance</li>
                    </ul>
                    
                    <hr>
                    
                    <h6><i class="fas fa-info-circle text-info me-2"></i> Next Steps</h6>
                    <p class="small">After creating a building, you can:</p>
                    <ol class="small">
                        <li>Add venues (halls, classrooms) to this building</li>
                        <li>Set capacities for each venue</li>
                        <li>Schedule events in the venues</li>
                        <li>Manage venue availability</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // If campus_id is passed in URL, select it
        const urlParams = new URLSearchParams(window.location.search);
        const campusId = urlParams.get('campus_id');
        if (campusId) {
            $('#campus_id').val(campusId);
        }
    });
</script>
@endpush