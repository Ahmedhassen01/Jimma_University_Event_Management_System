@extends('layouts.app')

@section('title', 'Add Permission - JU Event Management')
@section('page-title', 'Add New Permission')
@section('page-subtitle', 'Create a new system permission')

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('permissions.index') }}">Permissions</a></li>
    <li class="breadcrumb-item active">Add Permission</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="ju-card">
            <div class="ju-card-header">
                <h5 class="ju-card-title mb-0"><i class="fas fa-plus-circle me-2"></i>Permission Information</h5>
            </div>
            <div class="ju-card-body">
                <form method="POST" action="{{ route('permissions.store') }}" id="permissionForm">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Permission Name *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" 
                                       placeholder="e.g., View Users" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="form-text text-muted">Use clear, descriptive names</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="slug" class="form-label">Slug *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-code"></i></span>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                       id="slug" name="slug" value="{{ old('slug') }}" 
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
                                  placeholder="Describe what this permission allows...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Optional but recommended</small>
                    </div>
                    
                    <!-- Auto-generate slug from name -->
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="autoGenerateSlug">
                            <label class="form-check-label" for="autoGenerateSlug">
                                Auto-generate slug from name
                            </label>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('permissions.index') }}" class="btn btn-ju-outline">
                            <i class="fas fa-arrow-left me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-ju">
                            <i class="fas fa-save me-2"></i>Create Permission
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Guidelines -->
        <div class="ju-card mb-4">
            <div class="ju-card-header">
                <h5 class="ju-card-title mb-0"><i class="fas fa-info-circle me-2"></i>Permission Guidelines</h5>
            </div>
            <div class="ju-card-body">
                <div class="alert alert-info alert-ju">
                    <h6><i class="fas fa-lightbulb me-2"></i>Best Practices:</h6>
                    <ul class="mb-0 ps-3">
                        <li>Use clear, action-based names (view, create, edit, delete)</li>
                        <li>Follow naming convention: <code>action_resource</code></li>
                        <li>Group related permissions together</li>
                        <li>Always add descriptions for clarity</li>
                        <li>Test permissions before assigning to roles</li>
                    </ul>
                </div>
                
                <h6 class="mt-3">Common Permission Patterns:</h6>
                <div class="list-group list-group-flush">
                    <div class="list-group-item px-0">
                        <small class="text-muted">Resource Management:</small><br>
                        <code>view_users, create_users, edit_users, delete_users</code>
                    </div>
                    <div class="list-group-item px-0">
                        <small class="text-muted">System Access:</small><br>
                        <code>view_dashboard, manage_settings, export_data</code>
                    </div>
                    <div class="list-group-item px-0">
                        <small class="text-muted">Content Management:</small><br>
                        <code>publish_content, moderate_comments, manage_categories</code>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Stats -->
        <div class="ju-card">
            <div class="ju-card-header">
                <h5 class="ju-card-title mb-0"><i class="fas fa-chart-pie me-2"></i>Quick Stats</h5>
            </div>
            <div class="ju-card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Total Permissions:</span>
                    <strong>{{ \App\Models\Permission::count() }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Most Common Prefix:</span>
                    <strong>view_</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Average Roles per Permission:</span>
                    <strong>{{ number_format(\App\Models\Permission::withCount('roles')->get()->avg('roles_count') ?? 0, 1) }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        const autoGenerateCheckbox = document.getElementById('autoGenerateSlug');
        
        // Auto-generate slug from name
        nameInput.addEventListener('input', function() {
            if (autoGenerateCheckbox.checked) {
                const slug = this.value
                    .toLowerCase()
                    .replace(/[^a-z0-9\s]/g, '')
                    .replace(/\s+/g, '_')
                    .replace(/_+/g, '_')
                    .replace(/^_+|_+$/g, '');
                slugInput.value = slug;
            }
        });
        
        // Toggle auto-generation
        autoGenerateCheckbox.addEventListener('change', function() {
            if (this.checked && nameInput.value) {
                const slug = nameInput.value
                    .toLowerCase()
                    .replace(/[^a-z0-9\s]/g, '')
                    .replace(/\s+/g, '_')
                    .replace(/_+/g, '_')
                    .replace(/^_+|_+$/g, '');
                slugInput.value = slug;
            }
        });
        
        // Form validation
        document.getElementById('permissionForm').addEventListener('submit', function(e) {
            if (!slugInput.value.match(/^[a-z0-9_]+$/)) {
                e.preventDefault();
                alert('Slug must contain only lowercase letters, numbers, and underscores.');
                slugInput.focus();
            }
        });
    });
</script>
@endpush