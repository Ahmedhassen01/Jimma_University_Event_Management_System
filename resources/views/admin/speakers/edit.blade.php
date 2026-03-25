@extends('layouts.app')

@section('title', 'Edit Speaker - Jimma University')

@section('page-title', 'Edit Speaker')
@section('page-subtitle', 'Update speaker details')

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('speakers.index') }}">Speakers</a></li>
    <li class="breadcrumb-item"><a href="{{ route('speakers.show', $speaker) }}">{{ $speaker->name }}</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="ju-card">
    <div class="ju-card-header">
        <h5 class="ju-card-title">Edit Speaker Details</h5>
    </div>
    <div class="ju-card-body">
        <form action="{{ route('speakers.update', $speaker) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $speaker->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title/Position *</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title', $speaker->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="department" class="form-label">Department *</label>
                                <input type="text" class="form-control @error('department') is-invalid @enderror" 
                                       id="department" name="department" value="{{ old('department', $speaker->department) }}" required>
                                @error('department')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="expertise" class="form-label">Expertise/Research Area</label>
                                <input type="text" class="form-control @error('expertise') is-invalid @enderror" 
                                       id="expertise" name="expertise" value="{{ old('expertise', $speaker->expertise) }}" 
                                       placeholder="e.g., Artificial Intelligence, Renewable Energy">
                                @error('expertise')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="bio" class="form-label">Biography *</label>
                        <textarea class="form-control @error('bio') is-invalid @enderror" 
                                  id="bio" name="bio" rows="4" required>{{ old('bio', $speaker->bio) }}</textarea>
                        @error('bio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="photo" class="form-label">Profile Photo</label>
                        <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                               id="photo" name="photo" accept="image/*">
                        @error('photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        
                        @if($speaker->photo)
                        <div class="mt-2">
                            <p class="small mb-1">Current Photo:</p>
                            <img src="{{ asset('storage/' . $speaker->photo) }}" alt="Current photo" class="img-thumbnail" style="max-height: 150px;">
                        </div>
                        @endif
                        
                        <div class="mt-2">
                            <img id="photoPreview" src="#" alt="New photo preview" class="img-thumbnail d-none" style="max-height: 150px;">
                        </div>
                        <small class="text-muted">Leave empty to keep current photo</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email', $speaker->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" name="phone" value="{{ old('phone', $speaker->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="website" class="form-label">Website</label>
                        <input type="url" class="form-control @error('website') is-invalid @enderror" 
                               id="website" name="website" value="{{ old('website', $speaker->website) }}" placeholder="https://...">
                        @error('website')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="linkedin" class="form-label">LinkedIn Profile</label>
                        <input type="url" class="form-control @error('linkedin') is-invalid @enderror" 
                               id="linkedin" name="linkedin" value="{{ old('linkedin', $speaker->linkedin) }}" placeholder="https://linkedin.com/in/...">
                        @error('linkedin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="twitter" class="form-label">Twitter/X Profile</label>
                        <input type="url" class="form-control @error('twitter') is-invalid @enderror" 
                               id="twitter" name="twitter" value="{{ old('twitter', $speaker->twitter) }}" placeholder="https://twitter.com/...">
                        @error('twitter')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" 
                                   {{ old('is_active', $speaker->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active Speaker</label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" 
                                   {{ old('is_featured', $speaker->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">Featured Speaker</label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                <button type="submit" class="btn btn-ju">
                    <i class="fas fa-save me-2"></i> Update Speaker
                </button>
                <a href="{{ route('speakers.show', $speaker) }}" class="btn btn-outline-secondary ms-2">
                    <i class="fas fa-times me-2"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Photo preview for new photo
        const photoInput = document.getElementById('photo');
        const photoPreview = document.getElementById('photoPreview');
        
        photoInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    photoPreview.src = e.target.result;
                    photoPreview.classList.remove('d-none');
                }
                reader.readAsDataURL(this.files[0]);
            } else {
                photoPreview.classList.add('d-none');
            }
        });
    });
</script>
@endpush