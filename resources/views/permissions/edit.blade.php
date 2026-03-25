@extends('layouts.app')

@section('title', 'Edit Permission - JU Event Management')
@section('page-title', 'Edit Permission')
@section('page-subtitle', 'Update permission information')

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('permissions.index') }}">Permissions</a></li>
    <li class="breadcrumb-item"><a href="{{ route('permissions.show', $permission) }}">{{ $permission->name }}</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="ju-card">
            <div class="ju-card-header">
                <h5 class="ju-card-title mb-0"><i class="fas fa-edit me-2"></i>Edit Permission: {{ $permission->name }}</h5>
            </div>
            <div class="ju-card-body">
                <form method="POST" action="{{ route('permissions.update', $permission) }}" id="editPermissionForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Permission Name *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $permission->name) }}" 
                                       placeholder="e.g., View Users" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="slug" class="form-label">Slug *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-code"></i></span>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                       id="slug" name="slug" value="{{ old('slug', $permission->slug) }}" 
                                       placeholder="e.g., view_users" required>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="form-text text-muted">Lowercase, underscores, no spaces</small>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" 
                                  placeholder="Describe what this permission allows...">{{ old('description', $permission->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="alert alert-warning alert-ju mb-4">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Warning:</strong> Changing the slug may break existing role assignments if the permission is referenced elsewhere in the code.
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <div>
                            <a href="{{ route('permissions.show', $permission) }}" class="btn btn-ju-outline me-2">
                                <i class="fas fa-eye me-2"></i>View
                            </a>
                            <a href="{{ route('permissions.index') }}" class="btn btn-ju-outline">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-ju">
                                <i class="fas fa-save me-2"></i>Update Permission
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Permission Details -->
        <div class="ju-card mb-4">
            <div class="ju-card-header">
                <h5 class="ju-card-title mb-0"><i class="fas fa-info-circle me-2"></i>Permission Details</h5>
            </div>
            <div class="ju-card-body">
                <div class="mb-3">
                    <label class="form-label text-muted">Current Slug</label>
                    <p>
                        <code class="bg-light p-2 rounded d-block">{{ $permission->slug }}</code>
                    </p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted">Assigned to Roles</label>
                    <p>
                        <span class="badge bg-info fs-6">{{ $permission->roles->count() }} roles</span>
                    </p>
                    
                    @if($permission->roles->count() > 0)
                    <div class="mt-2">
                        @foreach($permission->roles as $role)
                        <a href="{{ route('roles.show', $role) }}" class="badge badge-ju me-1 mb-1">
                            {{ $role->name }}
                        </a>
                        @endforeach
                    </div>
                    @else
                    <p class="text-muted">Not assigned to any roles</p>
                    @endif
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted">Created</label>
                    <p>{{ $permission->created_at->format('M d, Y h:i A') }}</p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted">Last Updated</label>
                    <p>{{ $permission->updated_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>
        </div>
        
        <!-- Danger Zone -->
        @if($permission->roles->count() == 0)
        <div class="ju-card">
            <div class="ju-card-header bg-danger text-white">
                <h5 class="ju-card-title mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Danger Zone</h5>
            </div>
            <div class="ju-card-body">
                <div class="alert alert-danger">
                    <h6><i class="fas fa-skull-crossbones me-2"></i>Delete Permission</h6>
                    <p class="mb-3">This action cannot be undone. The permission will be permanently deleted.</p>
                    
                    <form method="POST" action="{{ route('permissions.destroy', $permission) }}" 
                          id="deleteForm" class="text-center">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-outline-danger" 
                                onclick="confirmDelete()">
                            <i class="fas fa-trash me-2"></i>Delete This Permission
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete() {
        if (confirm('Are you absolutely sure you want to delete this permission? This action cannot be undone.')) {
            document.getElementById('deleteForm').submit();
        }
    }
    
    // Form validation
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('editPermissionForm').addEventListener('submit', function(e) {
            const slugInput = document.getElementById('slug');
            if (!slugInput.value.match(/^[a-z0-9_]+$/)) {
                e.preventDefault();
                alert('Slug must contain only lowercase letters, numbers, and underscores.');
                slugInput.focus();
            }
        });
    });
</script>
@endpush