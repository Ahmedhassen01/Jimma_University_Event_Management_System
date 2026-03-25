@extends('layouts.app')

@section('title', 'Create New Campus')
@section('page-title', 'Create New Campus')
@section('page-subtitle', 'Add a new campus to the system')

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.campuses.index') }}">Campuses</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="ju-card">
                <div class="ju-card-header">
                    <h5 class="ju-card-title">Campus Details</h5>
                </div>
                <div class="ju-card-body">
                    <form action="{{ route('admin.campuses.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Campus Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="location" class="form-label">Location *</label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                       id="location" name="location" value="{{ old('location') }}" required>
                                @error('location')
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
                                <label for="contact_email" class="form-label">Contact Email</label>
                                <input type="email" class="form-control @error('contact_email') is-invalid @enderror" 
                                       id="contact_email" name="contact_email" value="{{ old('contact_email') }}">
                                @error('contact_email')
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
                                <label class="form-check-label" for="is_active">Active Campus</label>
                            </div>
                            <small class="form-text text-muted">Inactive campuses won't be available for selection.</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.campuses.index') }}" class="btn btn-ju-outline">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-ju">
                                <i class="fas fa-save me-1"></i> Create Campus
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
                    <h6>Campus Setup Guidelines</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><i class="fas fa-check text-success me-1"></i> Use official campus names</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-1"></i> Include precise location details</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-1"></i> Add contact information for campus administration</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-1"></i> Mark campuses inactive during renovations</li>
                    </ul>
                    
                    <hr>
                    
                    <h6><i class="fas fa-info-circle text-info me-2"></i> Next Steps</h6>
                    <p class="small">After creating a campus, you can:</p>
                    <ol class="small">
                        <li>Add buildings to this campus</li>
                        <li>Assign venues to those buildings</li>
                        <li>Schedule events in the venues</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection