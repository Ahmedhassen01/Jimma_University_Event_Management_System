@extends('layouts.app')

@section('title', 'Edit Role - JU Event Management')
@section('page-title', 'Edit Role')
@section('page-subtitle', 'Update role information and permissions')

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
    <li class="breadcrumb-item"><a href="{{ route('roles.show', $role) }}">{{ $role->name }}</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="ju-card">
            <div class="ju-card-header">
                <h5 class="ju-card-title mb-0"><i class="fas fa-edit me-2"></i>Edit Role: {{ $role->name }}</h5>
            </div>
            <div class="ju-card-body">
                <form method="POST" action="{{ route('roles.update', $role) }}" id="editRoleForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Role Name *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $role->name) }}" 
                                       placeholder="e.g., Administrator" required>
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
                                       id="slug" name="slug" value="{{ old('slug', $role->slug) }}" 
                                       placeholder="e.g., admin" required>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="form-text text-muted">Lowercase, no spaces</small>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" 
                                  placeholder="Describe the purpose of this role...">{{ old('description', $role->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Permissions Selection -->
                    <div class="mb-4">
                        <label class="form-label">Assign Permissions *</label>
                        <div class="alert alert-warning alert-ju mb-3">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Changing permissions will affect all users assigned to this role.
                        </div>
                        
                        <!-- Quick Actions -->
                        <div class="mb-3">
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-success" onclick="selectAllPermissions()">
                                    Select All
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="deselectAllPermissions()">
                                    Deselect All
                                </button>
                                <button type="button" class="btn btn-outline-info" onclick="keepCurrent()">
                                    Keep Current
                                </button>
                            </div>
                        </div>
                        
                        <!-- Permissions Grid -->
                        <div class="row" id="permissionsGrid">
                            @php
                                $currentPermissions = old('permissions', $role->permissions->pluck('id')->toArray());
                            @endphp
                            
                            @foreach($permissions->chunk(ceil($permissions->count() / 3)) as $chunk)
                            <div class="col-md-4">
                                @foreach($chunk as $permission)
                                <div class="form-check mb-2">
                                    <input class="form-check-input permission-checkbox" 
                                           type="checkbox" 
                                           name="permissions[]" 
                                           value="{{ $permission->id }}" 
                                           id="perm_{{ $permission->id }}"
                                           {{ in_array($permission->id, $currentPermissions) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                        <strong>{{ $permission->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $permission->description }}</small>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @endforeach
                        </div>
                        
                        @error('permissions')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        
                        <div class="mt-3">
                            <span id="selectedCount">{{ count($currentPermissions) }}</span> permissions selected
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <div>
                            <a href="{{ route('roles.show', $role) }}" class="btn btn-ju-outline me-2">
                                <i class="fas fa-eye me-2"></i>View
                            </a>
                            <a href="{{ route('roles.index') }}" class="btn btn-ju-outline">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-ju">
                                <i class="fas fa-save me-2"></i>Update Role
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Role Details -->
        <div class="ju-card mb-4">
            <div class="ju-card-header">
                <h5 class="ju-card-title mb-0"><i class="fas fa-info-circle me-2"></i>Role Details</h5>
            </div>
            <div class="ju-card-body">
                <div class="mb-3">
                    <label class="form-label text-muted">Current Slug</label>
                    <p>
                        <code class="bg-light p-2 rounded d-block">{{ $role->slug }}</code>
                    </p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted">Assigned Users</label>
                    <p>
                        <span class="badge bg-secondary fs-6">{{ $role->users->count() }} users</span>
                    </p>
                    
                    @if($role->users->count() > 0)
                    <div class="mt-2">
                        <small class="text-muted">Sample users with this role:</small>
                        <ul class="list-unstyled mt-1">
                            @foreach($role->users->take(3) as $user)
                            <li>
                                <i class="fas fa-user me-1 text-muted"></i>
                                <a href="{{ route('users.show', $user) }}">{{ $user->name }}</a>
                            </li>
                            @endforeach
                            @if($role->users->count() > 3)
                            <li class="text-muted">
                                <small>... and {{ $role->users->count() - 3 }} more</small>
                            </li>
                            @endif
                        </ul>
                    </div>
                    @else
                    <p class="text-muted">No users assigned to this role</p>
                    @endif
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted">Current Permissions</label>
                    <p>
                        <span class="badge bg-info fs-6">{{ $role->permissions->count() }} permissions</span>
                    </p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted">Created</label>
                    <p>{{ $role->created_at->format('M d, Y h:i A') }}</p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted">Last Updated</label>
                    <p>{{ $role->updated_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>
        </div>
        
        <!-- Danger Zone -->
        @if($role->users->count() == 0)
        <div class="ju-card">
            <div class="ju-card-header bg-danger text-white">
                <h5 class="ju-card-title mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Danger Zone</h5>
            </div>
            <div class="ju-card-body">
                <div class="alert alert-danger">
                    <h6><i class="fas fa-skull-crossbones me-2"></i>Delete Role</h6>
                    <p class="mb-3">This action cannot be undone. The role will be permanently deleted.</p>
                    
                    <form method="POST" action="{{ route('roles.destroy', $role) }}" 
                          id="deleteForm" class="text-center">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-outline-danger" 
                                onclick="confirmDelete()">
                            <i class="fas fa-trash me-2"></i>Delete This Role
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @else
        <div class="ju-card">
            <div class="ju-card-header bg-warning text-dark">
                <h5 class="ju-card-title mb-0"><i class="fas fa-exclamation-circle me-2"></i>Warning</h5>
            </div>
            <div class="ju-card-body">
                <div class="alert alert-warning">
                    <h6><i class="fas fa-users me-2"></i>Role in Use</h6>
                    <p class="mb-3">
                        This role has <strong>{{ $role->users->count() }}</strong> users assigned to it. 
                        You cannot delete it until all users are reassigned to other roles.
                    </p>
                    <a href="{{ route('users.index') }}?role={{ $role->id }}" class="btn btn-sm btn-outline-warning">
                        <i class="fas fa-user-friends me-2"></i>View Assigned Users
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.permission-checkbox');
        const selectedCount = document.getElementById('selectedCount');
        
        // Update selected count
        function updateSelectedCount() {
            const selected = document.querySelectorAll('.permission-checkbox:checked').length;
            selectedCount.textContent = selected;
        }
        
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedCount);
        });
        
        // Bulk selection functions
        window.selectAllPermissions = function() {
            checkboxes.forEach(cb => cb.checked = true);
            updateSelectedCount();
        };
        
        window.deselectAllPermissions = function() {
            checkboxes.forEach(cb => cb.checked = false);
            updateSelectedCount();
        };
        
        window.keepCurrent = function() {
            // This would normally reset to original state
            // For now, just show a message
            alert('Current permissions kept. Make your changes and click Update to save.');
        };
        
        function confirmDelete() {
            if (confirm('Are you absolutely sure you want to delete this role? This action cannot be undone.')) {
                document.getElementById('deleteForm').submit();
            }
        }
        window.confirmDelete = confirmDelete;
        
        // Form validation
        document.getElementById('editRoleForm').addEventListener('submit', function(e) {
            const selectedPermissions = document.querySelectorAll('.permission-checkbox:checked').length;
            if (selectedPermissions === 0) {
                e.preventDefault();
                alert('Please select at least one permission for this role.');
            }
        });
    });
</script>
@endpush