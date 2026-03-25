@extends('layouts.app')

@section('title', 'Roles - JU Event Management')
@section('page-title', 'Role Management')
@section('page-subtitle', 'Manage user roles and permissions')

@section('breadcrumb-items')
    <li class="breadcrumb-item active">Roles</li>
@endsection

@section('content')
<div class="ju-card">
    <div class="ju-card-header d-flex justify-content-between align-items-center">
        <h5 class="ju-card-title mb-0"><i class="fas fa-user-tag me-2"></i>Roles List</h5>
        
        <a href="{{ route('roles.create') }}" class="btn btn-ju">
            <i class="fas fa-plus-circle me-2"></i>Create Role
        </a>
    </div>
    
    <div class="ju-card-body">
        <!-- Search and Filters -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" placeholder="Search roles..." id="searchInput">
                    <button class="btn btn-outline-secondary" type="button" onclick="searchTable()">
                        Search
                    </button>
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="userFilter">
                    <option value="">All Roles</option>
                    <option value="with_users">With Users</option>
                    <option value="without_users">Without Users</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="permissionFilter">
                    <option value="">Any Permissions</option>
                    <option value="with_perms">With Permissions</option>
                    <option value="without_perms">Without Permissions</option>
                </select>
            </div>
        </div>
        
        <!-- Roles Table -->
        <div class="table-responsive">
            <table class="table table-ju" id="rolesTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Role Name</th>
                        <th>Slug</th>
                        <th>Description</th>
                        <th>Users</th>
                        <th>Permissions</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <strong>{{ $role->name }}</strong>
                        </td>
                        <td>
                            <code class="bg-light p-1 rounded">{{ $role->slug }}</code>
                        </td>
                        <td>
                            @if($role->description)
                                {{ Str::limit($role->description, 50) }}
                            @else
                                <span class="text-muted">No description</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $role->users_count ?? $role->users->count() }}</span>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $role->permissions_count ?? $role->permissions->count() }}</span>
                        </td>
                        <td>
                            <div class="small">
                                {{ $role->created_at->format('M d, Y') }}
                            </div>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('roles.show', $role) }}" class="btn btn-outline-success" 
                                   data-bs-toggle="tooltip" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <a href="{{ route('roles.edit', $role) }}" class="btn btn-outline-primary" 
                                   data-bs-toggle="tooltip" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                @if(($role->users_count ?? $role->users->count()) == 0)
                                <form method="POST" action="{{ route('roles.destroy', $role) }}" 
                                      class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" 
                                            data-bs-toggle="tooltip" title="Delete"
                                            onclick="return confirm('Delete this role?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-user-tag fa-3x mb-3"></i>
                                <h5>No roles found</h5>
                                <p>Start by creating your first role.</p>
                                <a href="{{ route('roles.create') }}" class="btn btn-ju">
                                    <i class="fas fa-plus-circle me-2"></i>Create Role
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($roles->hasPages())
        <div class="row mt-4">
            <div class="col-md-12">
                <nav aria-label="Page navigation">
                    {{ $roles->links() }}
                </nav>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Stats -->
<div class="row mt-4">
    <div class="col-md-3 col-sm-6">
        <div class="ju-card">
            <div class="ju-card-body text-center">
                <h3 class="mb-0">{{ $roles->total() }}</h3>
                <p class="text-muted mb-0">Total Roles</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="ju-card">
            <div class="ju-card-body text-center">
                <h3 class="mb-0">{{ \App\Models\User::count() }}</h3>
                <p class="text-muted mb-0">Total Users</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="ju-card">
            <div class="ju-card-body text-center">
                @php
                    $avgPermissions = $roles->avg('permissions_count') ?? 0;
                @endphp
                <h3 class="mb-0">{{ number_format($avgPermissions, 1) }}</h3>
                <p class="text-muted mb-0">Avg Permissions per Role</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="ju-card">
            <div class="ju-card-body text-center">
                @php
                    $mostUsersRole = $roles->sortByDesc('users_count')->first();
                @endphp
                <h3 class="mb-0">{{ $mostUsersRole ? $mostUsersRole->users_count : 0 }}</h3>
                <p class="text-muted mb-0">Most Popular Role</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));
        
        // Search function
        window.searchTable = function() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('#rolesTable tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        };
        
        // Filter functions
        document.getElementById('userFilter').addEventListener('change', filterTable);
        document.getElementById('permissionFilter').addEventListener('change', filterTable);
        
        function filterTable() {
            const userFilter = document.getElementById('userFilter').value;
            const permFilter = document.getElementById('permissionFilter').value;
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            
            const rows = document.querySelectorAll('#rolesTable tbody tr');
            
            rows.forEach(row => {
                const userCell = row.querySelector('td:nth-child(5)');
                const permCell = row.querySelector('td:nth-child(6)');
                const text = row.textContent.toLowerCase();
                
                const userCount = parseInt(userCell.textContent);
                const permCount = parseInt(permCell.textContent);
                
                let userMatch = true;
                let permMatch = true;
                
                if (userFilter === 'with_users' && userCount === 0) userMatch = false;
                if (userFilter === 'without_users' && userCount > 0) userMatch = false;
                if (permFilter === 'with_perms' && permCount === 0) permMatch = false;
                if (permFilter === 'without_perms' && permCount > 0) permMatch = false;
                
                const searchMatch = !searchTerm || text.includes(searchTerm);
                
                row.style.display = (userMatch && permMatch && searchMatch) ? '' : 'none';
            });
        }
    });
</script>
@endpush