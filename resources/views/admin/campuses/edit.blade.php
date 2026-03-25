@extends('layouts.app')

@section('title', 'Edit Campus')
@section('page-title', 'Edit Campus')
@section('page-subtitle', 'Update campus information')

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.campuses.index') }}">Campuses</a></li>
    <li class="breadcrumb-item active">Edit {{ $campus->name }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="ju-card">
                <div class="ju-card-header">
                    <h5 class="ju-card-title">Edit Campus Details</h5>
                </div>
                <div class="ju-card-body">
                    <form action="{{ route('admin.campuses.update', $campus) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Campus Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $campus->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="location" class="form-label">Location *</label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                       id="location" name="location" value="{{ old('location', $campus->location) }}" required>
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $campus->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="contact_email" class="form-label">Contact Email</label>
                                <input type="email" class="form-control @error('contact_email') is-invalid @enderror" 
                                       id="contact_email" name="contact_email" value="{{ old('contact_email', $campus->contact_email) }}">
                                @error('contact_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="contact_phone" class="form-label">Contact Phone</label>
                                <input type="text" class="form-control @error('contact_phone') is-invalid @enderror" 
                                       id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $campus->contact_phone) }}">
                                @error('contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" 
                                       id="is_active" name="is_active" value="1" {{ old('is_active', $campus->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active Campus</label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.campuses.index') }}" class="btn btn-ju-outline">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-ju">
                                <i class="fas fa-save me-1"></i> Update Campus
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="ju-card">
                <div class="ju-card-header">
                    <h5 class="ju-card-title">Campus Information</h5>
                </div>
                <div class="ju-card-body">
                    <div class="mb-3">
                        <h6>Current Status</h6>
                        <p>
                            @if($campus->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <h6>Buildings Count</h6>
                        <p class="h4">{{ $campus->buildings_count }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6>Created</h6>
                        <p>{{ $campus->created_at->format('M d, Y') }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6>Last Updated</h6>
                        <p>{{ $campus->updated_at->format('M d, Y') }}</p>
                    </div>
                    
                    <hr>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.campuses.show', $campus) }}" class="btn btn-info">
                            <i class="fas fa-eye me-1"></i> View Details
                        </a>
                        <form action="{{ route('admin.campuses.destroy', $campus) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash me-1"></i> Delete Campus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection