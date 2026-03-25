@extends('layouts.app')

@section('title', 'Create Role - JU Event Management')
@section('page-title', 'Create New Role')
@section('page-subtitle', 'Define a new user role with permissions')

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
    <li class="breadcrumb-item active">Create Role</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="ju-card">
            <div class="ju-card-header">
                <h5 class="ju-card-title mb-0"><i class="fas fa-user-tag me-2"></i>Role Information</h5>
            </div>
            <div class="ju-card-body">
                <form method="POST" action="{{ route('roles.store') }}" id="roleForm">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Role Name *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" 
                                       placeholder="e.g., Administrator" required>
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
                                       placeholder="e.g., admin" required>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="form-text text-muted">Lowercase, no spaces, unique</small>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" 
                                  placeholder="Describe the purpose of this role...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Permissions Selection -->
                    <div class="mb-4">
                        <label class="form-label">Assign Permissions *</label>
                        <div class="alert alert-info alert-ju mb-3">
                            <i class="fas fa-info-circle me-2"></i>
                            Select the permissions this role should have. Users assigned this role will inherit these permissions.
                        </div>
                        
                        <!-- Permission Categories -->
                        <div class="mb-3">
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-success" onclick="selectAllPermissions()">
                                    Select All
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="deselectAllPermissions()">
                                    Deselect All
                                </button>
                                <button type="button" class="btn btn-outline-primary" onclick="selectCategory('view')">
                                    All View
                                </button>
                                <button type="button" class="btn btn-outline-warning" onclick="selectCategory('edit')">
                                    All Edit
                                </button>
                            </div>
                        </div>
                        
                        <!-- Permissions Grid -->
                        <div class="row" id="permissionsGrid">
                            @foreach($permissions->chunk(ceil($permissions->count() / 3)) as $chunk)
                            <div class="col-md-4">
                                @foreach($chunk as $permission)
                                <div class="form-check mb-2">
                                    <input class="form-check-input permission-checkbox" 
                                           type="checkbox" 
                                           name="permissions[]" 
                                           value="{{ $permission->id }}" 
                                           id="perm_{{ $permission->id }}"
                                           {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
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
                            <span id="selectedCount">0</span> permissions selected
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('roles.index') }}" class="btn btn-ju-outline">
                            <i class="fas fa-arrow-left me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-ju">
                            <i class="fas fa-save me-2"></i>Create Role
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Role Templates -->
        <div class="ju-card mb-4">
            <div class="ju-card-header">
                <h5 class="ju-card-title mb-0"><i class="fas fa-copy me-2"></i>Role Templates</h5>
            </div>
            <div class="ju-card-body">
                <div class="alert alert-info alert-ju">
                    <h6><i class="fas fa-magic me-2"></i>Quick Templates:</h6>
                    <p class="mb-2">Click to apply template permissions</p>
                    
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-sm btn-outline-success" onclick="applyTemplate('admin')">
                            <i class="fas fa-user-shield me-2"></i>Administrator
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="applyTemplate('manager')">
                            <i class="fas fa-user-tie me-2"></i>Manager
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-info" onclick="applyTemplate('editor')">
                            <i class="fas fa-edit me-2"></i>Editor
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="applyTemplate('viewer')">
                            <i class="fas fa-eye me-2"></i>Viewer
                        </button>
                    </div>
                </div>
                
                <h6 class="mt-3">Template Descriptions:</h6>
                <div class="list-group list-group-flush">
                    <div class="list-group-item px-0">
                        <small><strong>Administrator:</strong> Full system access</small>
                    </div>
                    <div class="list-group-item px-0">
                        <small><strong>Manager:</strong> Can manage users and content</small>
                    </div>
                    <div class="list-group-item px-0">
                        <small><strong>Editor:</strong> Can create and edit content</small>
                    </div>
                    <div class="list-group-item px-0">
                        <small><strong>Viewer:</strong> Read-only access</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Stats -->
        <div class="ju-card">
            <div class="ju-card-header">
                <h5 class="ju-card-title mb-0"><i class="fas fa-chart-bar me-2"></i>Quick Stats</h5>
            </div>
            <div class="ju-card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Total Permissions:</span>
                    <strong>{{ $permissions->count() }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Average per Role:</span>
                    <strong>{{ number_format(\App\Models\Role::withCount('permissions')->get()->avg('permissions_count') ?? 0, 1) }}</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Most Used Permission:</span>
                    <strong>view_dashboard</strong>
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
        const checkboxes = document.querySelectorAll('.permission-checkbox');
        const selectedCount = document.getElementById('selectedCount');
        
        // Auto-generate slug from name
        nameInput.addEventListener('input', function() {
            const slug = this.value
                .toLowerCase()
                .replace(/[^a-z0-9\s]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .replace(/^-+|-+$/g, '');
            slugInput.value = slug;
        });
        
        // Update selected count
        function updateSelectedCount() {
            const selected = document.querySelectorAll('.permission-checkbox:checked').length;
            selectedCount.textContent = selected;
        }
        
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedCount);
        });
        
        // Initial count
        updateSelectedCount();
        
        // Template functions
        window.applyTemplate = function(template) {
            // First deselect all
            checkboxes.forEach(cb => cb.checked = false);
            
            // Define template permissions
            const templates = {
                'admin': ['view_', 'create_', 'edit_', 'delete_', 'manage_'],
                'manager': ['view_', 'create_', 'edit_'],
                'editor': ['view_', 'create_', 'edit_'],
                'viewer': ['view_']
            };
            
            // Select permissions based on template
            checkboxes.forEach(checkbox => {
                const permissionName = checkbox.nextElementSibling.querySelector('strong').textContent.toLowerCase();
                templates[template].forEach(prefix => {
                    if (permissionName.includes(prefix)) {
                        checkbox.checked = true;
                    }
                });
            });
            
            updateSelectedCount();
            alert(`${template.charAt(0).toUpperCase() + template.slice(1)} template applied!`);
        };
        
        // Bulk selection functions
        window.selectAllPermissions = function() {
            checkboxes.forEach(cb => cb.checked = true);
            updateSelectedCount();
        };
        
        window.deselectAllPermissions = function() {
            checkboxes.forEach(cb => cb.checked = false);
            updateSelectedCount();
        };
        
        window.selectCategory = function(category) {
            checkboxes.forEach(checkbox => {
                const permissionName = checkbox.nextElementSibling.querySelector('strong').textContent.toLowerCase();
                if (permissionName.startsWith(category)) {
                    checkbox.checked = true;
                }
            });
            updateSelectedCount();
        };
        
        // Form validation
        document.getElementById('roleForm').addEventListener('submit', function(e) {
            const selectedPermissions = document.querySelectorAll('.permission-checkbox:checked').length;
            if (selectedPermissions === 0) {
                e.preventDefault();
                alert('Please select at least one permission for this role.');
            }
        });
    });
</script>
@endpush