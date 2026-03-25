@extends('layouts.app')

@section('title', 'Permission Details - JU Event Management')
@section('page-title', 'Permission Details')
@section('page-subtitle', 'View permission information and assignments')

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('permissions.index') }}">Permissions</a></li>
    <li class="breadcrumb-item active">{{ $permission->name }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-4">
        <!-- Permission Card -->
        <div class="ju-card mb-4">
            <div class="ju-card-body text-center">
                <div class="permission-icon mb-3">
                    <i class="fas fa-key fa-3x text-success"></i>
                </div>
                <h4>{{ $permission->name }}</h4>
                <p>
                    <code class="bg-light p-2 rounded">{{ $permission->slug }}</code>
                </p>
                
                <div class="mb-4">
                    <span class="badge bg-info fs-6">
                        {{ $permission->roles->count() }} Roles
                    </span>
                </div>
                
                <div class="d-grid gap-2">
                    <a href="{{ route('permissions.edit', $permission) }}" class="btn btn-ju">
                        <i class="fas fa-edit me-2"></i>Edit Permission
                    </a>
                    
                    <a href="{{ route('permissions.index') }}" class="btn btn-ju-outline">
                        <i class="fas fa-arrow-left me-2"></i>Back to List
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Quick Stats -->
        <div class="ju-card">
            <div class="ju-card-header">
                <h5 class="ju-card-title mb-0"><i class="fas fa-chart-bar me-2"></i>Quick Stats</h5>
            </div>
            <div class="ju-card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span><i class="fas fa-calendar-alt me-2"></i>Created</span>
                        <strong>{{ $permission->created_at->format('M d, Y') }}</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span><i class="fas fa-clock me-2"></i>Last Updated</span>
                        <strong>{{ $permission->updated_at->format('M d, Y') }}</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span><i class="fas fa-users me-2"></i>Affected Users</span>
                        <strong>{{ $permission->roles->sum('users_count') }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <!-- Permission Details Tabs -->
        <div class="ju-card">
            <div class="ju-card-header">
                <ul class="nav nav-tabs card-header-tabs" id="permissionTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="details-tab" data-bs-toggle="tab" 
                                data-bs-target="#details" type="button" role="tab">
                            <i class="fas fa-info-circle me-2"></i>Details
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="roles-tab" data-bs-toggle="tab" 
                                data-bs-target="#roles" type="button" role="tab">
                            <i class="fas fa-user-tag me-2"></i>Assigned Roles
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="usage-tab" data-bs-toggle="tab" 
                                data-bs-target="#usage" type="button" role="tab">
                            <i class="fas fa-history me-2"></i>Usage
                        </button>
                    </li>
                </ul>
            </div>
            
            <div class="ju-card-body">
                <div class="tab-content" id="permissionTabsContent">
                    <!-- Details Tab -->
                    <div class="tab-pane fade show active" id="details" role="tabpanel">
                        <h6 class="mb-3">Permission Information</h6>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Permission Name</label>
                                    <p class="fs-5">{{ $permission->name }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Slug</label>
                                    <p>
                                        <code class="bg-light p-2 rounded">{{ $permission->slug }}</code>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Description</label>
                                    <p>
                                        @if($permission->description)
                                            {{ $permission->description }}
                                        @else
                                            <span class="text-muted">No description provided</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <h6 class="mb-3">Permission Type</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @php
                                    $type = '';
                                    if (str_contains($permission->slug, 'view_')) {
                                        $type = 'Read';
                                    } elseif (str_contains($permission->slug, 'create_')) {
                                        $type = 'Create';
                                    } elseif (str_contains($permission->slug, 'edit_')) {
                                        $type = 'Update';
                                    } elseif (str_contains($permission->slug, 'delete_')) {
                                        $type = 'Delete';
                                    } else {
                                        $type = 'General';
                                    }
                                @endphp
                                
                                <span class="badge bg-{{ $type == 'Read' ? 'info' : ($type == 'Create' ? 'success' : ($type == 'Update' ? 'warning' : ($type == 'Delete' ? 'danger' : 'secondary'))) }}">
                                    {{ $type }} Permission
                                </span>
                                
                                @if(str_contains($permission->name, 'Admin') || str_contains($permission->slug, 'admin'))
                                <span class="badge bg-danger">Administrative</span>
                                @endif
                                
                                @if($permission->roles->count() > 5)
                                <span class="badge bg-success">Widely Used</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Roles Tab -->
                    <div class="tab-pane fade" id="roles" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">Roles with this Permission</h6>
                            <span class="badge bg-info">{{ $permission->roles->count() }} roles</span>
                        </div>
                        
                        @if($permission->roles->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Role Name</th>
                                        <th>Description</th>
                                        <th>Users Count</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($permission->roles as $role)
                                    <tr>
                                        <td>
                                            <a href="{{ route('roles.show', $role) }}" class="text-decoration-none">
                                                <strong>{{ $role->name }}</strong>
                                            </a>
                                        </td>
                                        <td>
                                            {{ Str::limit($role->description, 50) }}
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $role->users->count() }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="fas fa-user-tag fa-3x text-muted mb-3"></i>
                            <h5>No Roles Assigned</h5>
                            <p class="text-muted">This permission is not assigned to any roles.</p>
                            <a href="{{ route('roles.index') }}" class="btn btn-ju">
                                <i class="fas fa-user-tag me-2"></i>Assign to Role
                            </a>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Usage Tab -->
                    <div class="tab-pane fade" id="usage" role="tabpanel">
                        <h6 class="mb-3">Permission Usage</h6>
                        
                        <div class="alert alert-info alert-ju mb-4">
                            <i class="fas fa-info-circle me-2"></i>
                            This permission controls access to specific features in the system.
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="ju-card">
                                    <div class="ju-card-body">
                                        <h6><i class="fas fa-code me-2"></i>Code Usage</h6>
                                        <p class="small text-muted">How to use in controllers:</p>
                                        <pre class="bg-light p-3 rounded"><code>// Middleware
Route::middleware(['permission:{{ $permission->slug }}'])-&gt;group(...);

// Controller check
if (auth()-&gt;user()-&gt;hasPermission('{{ $permission->slug }}')) {
    // Allow action
}</code></pre>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="ju-card">
                                    <div class="ju-card-body">
                                        <h6><i class="fas fa-shield-alt me-2"></i>Security Impact</h6>
                                        <ul class="mb-0">
                                            <li>Affects {{ $permission->roles->sum('users_count') }} users</li>
                                            <li>Used by {{ $permission->roles->count() }} roles</li>
                                            <li>Critical: {{ str_contains($permission->slug, 'delete') || str_contains($permission->slug, 'admin') ? 'Yes' : 'No' }}</li>
                                            <li>Last assigned: {{ $permission->updated_at->diffForHumans() }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Related Permissions -->
        <div class="ju-card mt-4">
            <div class="ju-card-header">
                <h5 class="ju-card-title mb-0"><i class="fas fa-link me-2"></i>Related Permissions</h5>
            </div>
            <div class="ju-card-body">
                <div class="row">
                    @php
                        $relatedPermissions = \App\Models\Permission::where('slug', 'like', '%' . explode('_', $permission->slug)[0] . '%')
                            ->orWhere('slug', 'like', '%_' . (explode('_', $permission->slug)[1] ?? '') . '%')
                            ->where('id', '!=', $permission->id)
                            ->take(4)
                            ->get();
                    @endphp
                    
                    @if($relatedPermissions->count() > 0)
                        @foreach($relatedPermissions as $related)
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3">
                                <h6>{{ $related->name }}</h6>
                                <p class="small text-muted mb-2">{{ Str::limit($related->description, 60) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <code class="small">{{ $related->slug }}</code>
                                    <a href="{{ route('permissions.show', $related) }}" class="btn btn-sm btn-outline-success">
                                        View
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                    <div class="col-12 text-center py-3">
                        <p class="text-muted">No related permissions found.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection