@extends('layouts.app')

@section('title', 'Role Details - JU Event Management')
@section('page-title', 'Role Details')
@section('page-subtitle', 'View role information and permissions')

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
    <li class="breadcrumb-item active">{{ $role->name }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-4">
        <!-- Role Card -->
        <div class="ju-card mb-4">
            <div class="ju-card-body text-center">
                <div class="role-icon mb-3">
                    <i class="fas fa-user-tag fa-3x text-success"></i>
                </div>
                <h4>{{ $role->name }}</h4>
                <p>
                    <code class="bg-light p-2 rounded">{{ $role->slug }}</code>
                </p>
                
                <div class="mb-4">
                    <div class="d-flex justify-content-center gap-3">
                        <span class="badge bg-secondary">
                            <i class="fas fa-users me-1"></i>
                            {{ $role->users->count() }} Users
                        </span>
                        <span class="badge bg-info">
                            <i class="fas fa-key me-1"></i>
                            {{ $role->permissions->count() }} Permissions
                        </span>
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <a href="{{ route('roles.edit', $role) }}" class="btn btn-ju">
                        <i class="fas fa-edit me-2"></i>Edit Role
                    </a>
                    
                    <a href="{{ route('roles.index') }}" class="btn btn-ju-outline">
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
                        <strong>{{ $role->created_at->format('M d, Y') }}</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span><i class="fas fa-clock me-2"></i>Last Updated</span>
                        <strong>{{ $role->updated_at->format('M d, Y') }}</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span><i class="fas fa-user-plus me-2"></i>New Users (30d)</span>
                        <strong>0</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <!-- Role Details Tabs -->
        <div class="ju-card">
            <div class="ju-card-header">
                <ul class="nav nav-tabs card-header-tabs" id="roleTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="details-tab" data-bs-toggle="tab" 
                                data-bs-target="#details" type="button" role="tab">
                            <i class="fas fa-info-circle me-2"></i>Details
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="permissions-tab" data-bs-toggle="tab" 
                                data-bs-target="#permissions" type="button" role="tab">
                            <i class="fas fa-key me-2"></i>Permissions
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="users-tab" data-bs-toggle="tab" 
                                data-bs-target="#users" type="button" role="tab">
                            <i class="fas fa-users me-2"></i>Users
                        </button>
                    </li>
                </ul>
            </div>
            
            <div class="ju-card-body">
                <div class="tab-content" id="roleTabsContent">
                    <!-- Details Tab -->
                    <div class="tab-pane fade show active" id="details" role="tabpanel">
                        <h6 class="mb-3">Role Information</h6>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Role Name</label>
                                    <p class="fs-5">{{ $role->name }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Slug</label>
                                    <p>
                                        <code class="bg-light p-2 rounded">{{ $role->slug }}</code>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Description</label>
                                    <p>
                                        @if($role->description)
                                            {{ $role->description }}
                                        @else
                                            <span class="text-muted">No description provided</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <h6 class="mb-3">Role Type</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @php
                                    $type = 'Custom';
                                    if (str_contains($role->slug, 'admin')) {
                                        $type = 'Administrative';
                                    } elseif (str_contains($role->slug, 'manager')) {
                                        $type = 'Managerial';
                                    } elseif (str_contains($role->slug, 'user')) {
                                        $type = 'User';
                                    } elseif (str_contains($role->slug, 'guest')) {
                                        $type = 'Guest';
                                    }
                                @endphp
                                
                                <span class="badge bg-{{ $type == 'Administrative' ? 'danger' : ($type == 'Managerial' ? 'warning' : ($type == 'User' ? 'info' : ($type == 'Guest' ? 'secondary' : 'success'))) }}">
                                    {{ $type }} Role
                                </span>
                                
                                @if($role->users->count() > 10)
                                <span class="badge bg-success">Popular</span>
                                @endif
                                
                                @if($role->permissions->count() > 15)
                                <span class="badge bg-info">High Privilege</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Permissions Tab -->
                    <div class="tab-pane fade" id="permissions" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">Role Permissions</h6>
                            <span class="badge bg-info">{{ $role->permissions->count() }} permissions</span>
                        </div>
                        
                        @if($role->permissions->count() > 0)
                        <!-- Permission Categories -->
                        <div class="mb-4">
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-success active" onclick="filterPermissions('all')">
                                    All
                                </button>
                                <button type="button" class="btn btn-outline-info" onclick="filterPermissions('view')">
                                    View
                                </button>
                                <button type="button" class="btn btn-outline-warning" onclick="filterPermissions('edit')">
                                    Edit
                                </button>
                                <button type="button" class="btn btn-outline-danger" onclick="filterPermissions('delete')">
                                    Delete
                                </button>
                                <button type="button" class="btn btn-outline-primary" onclick="filterPermissions('manage')">
                                    Manage
                                </button>
                            </div>
                        </div>
                        
                        <!-- Permissions Grid -->
                        <div class="row" id="permissionsGrid">
                            @foreach($role->permissions as $permission)
                            <div class="col-md-6 mb-3 permission-card" data-category="{{ explode('_', $permission->slug)[0] }}">
                                <div class="card h-100 border-start border-{{ explode('_', $permission->slug)[0] == 'view' ? 'info' : (explode('_', $permission->slug)[0] == 'edit' ? 'warning' : (explode('_', $permission->slug)[0] == 'delete' ? 'danger' : (explode('_', $permission->slug)[0] == 'create' ? 'success' : 'primary'))) }}">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-key me-2"></i>
                                            {{ $permission->name }}
                                        </h6>
                                        <p class="card-text small text-muted mb-2">
                                            {{ $permission->description }}
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <code class="small">{{ $permission->slug }}</code>
                                            <a href="{{ route('permissions.show', $permission) }}" class="btn btn-sm btn-outline-success">
                                                View
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="fas fa-key fa-3x text-muted mb-3"></i>
                            <h5>No Permissions Assigned</h5>
                            <p class="text-muted">This role has no permissions assigned.</p>
                            <a href="{{ route('roles.edit', $role) }}" class="btn btn-ju">
                                <i class="fas fa-key me-2"></i>Assign Permissions
                            </a>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Users Tab -->
                    <div class="tab-pane fade" id="users" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">Users with this Role</h6>
                            <span class="badge bg-secondary">{{ $role->users->count() }} users</span>
                        </div>
                        
                        @if($role->users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Email</th>
                                        <th>Joined</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($role->users as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="user-avatar-sm me-2">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                {{ $user->name }}
                                            </div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <small>{{ $user->created_at->format('M d, Y') }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">Active</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5>No Users Assigned</h5>
                            <p class="text-muted">This role is not assigned to any users.</p>
                            <a href="{{ route('users.create') }}" class="btn btn-ju">
                                <i class="fas fa-user-plus me-2"></i>Create User with this Role
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Role Analysis -->
        <div class="ju-card mt-4">
            <div class="ju-card-header">
                <h5 class="ju-card-title mb-0"><i class="fas fa-chart-line me-2"></i>Role Analysis</h5>
            </div>
            <div class="ju-card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Permission Distribution</h6>
                        <canvas id="permissionChart" height="200"></canvas>
                    </div>
                    <div class="col-md-6">
                        <h6>Security Level</h6>
                        <div class="progress mb-3" style="height: 20px;">
                            @php
                                $securityScore = min(100, $role->permissions->count() * 5);
                                $barColor = $securityScore > 70 ? 'bg-danger' : ($securityScore > 40 ? 'bg-warning' : 'bg-success');
                            @endphp
                            <div class="progress-bar {{ $barColor }}" role="progressbar" 
                                 style="width: {{ $securityScore }}%">
                                {{ $securityScore }}%
                            </div>
                        </div>
                        <p class="small text-muted">
                            @if($securityScore > 70)
                                High security risk - Many permissions granted
                            @elseif($securityScore > 40)
                                Moderate security level
                            @else
                                Low security risk - Minimal permissions
                            @endif
                        </p>
                        
                        <h6 class="mt-4">Recommendations</h6>
                        <ul class="small">
                            @if($role->permissions->count() > 20)
                            <li>Consider splitting into multiple specialized roles</li>
                            @endif
                            @if($role->users->count() > 50)
                            <li>Review user assignments regularly</li>
                            @endif
                            @if($securityScore > 70)
                            <li>Implement additional security checks</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .user-avatar-sm {
        width: 30px;
        height: 30px;
        background: linear-gradient(135deg, var(--ju-green) 0%, var(--ju-dark-green) 100%);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.9rem;
    }
    
    .role-icon {
        color: var(--ju-green);
    }
    
    .permission-card {
        transition: transform 0.3s;
    }
    
    .permission-card:hover {
        transform: translateY(-2px);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Permission filtering
        window.filterPermissions = function(category) {
            const cards = document.querySelectorAll('.permission-card');
            const buttons = document.querySelectorAll('#permissions .btn-group .btn');
            
            // Update active button
            buttons.forEach(btn => {
                btn.classList.remove('active');
                if (btn.textContent.toLowerCase().includes(category)) {
                    btn.classList.add('active');
                }
            });
            
            // Show/hide cards
            cards.forEach(card => {
                if (category === 'all' || card.dataset.category === category) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        };
        
        // Permission distribution chart
        const ctx = document.getElementById('permissionChart').getContext('2d');
        
        // Get the permission counts from the controller via JSON
        // This uses the $permissionTypes variable that we added to the controller
        const permissionTypes = @json($permissionTypes);
        
        const chart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['View', 'Create', 'Edit', 'Delete', 'Manage', 'Other'],
                datasets: [{
                    data: [
                        permissionTypes.view,
                        permissionTypes.create,
                        permissionTypes.edit,
                        permissionTypes.delete,
                        permissionTypes.manage,
                        permissionTypes.other
                    ],
                    backgroundColor: [
                        '#17a2b8', // View - Info
                        '#28a745', // Create - Success
                        '#ffc107', // Edit - Warning
                        '#dc3545', // Delete - Danger
                        '#007bff', // Manage - Primary
                        '#6c757d'  // Other - Secondary
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    });
</script>
@endpush