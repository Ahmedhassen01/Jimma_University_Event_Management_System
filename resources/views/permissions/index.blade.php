@extends('layouts.app')

@section('title', 'Permissions - JU Event Management')
@section('page-title', 'Permission Management')
@section('page-subtitle', 'Manage system permissions')

@section('breadcrumb-items')
    <li class="breadcrumb-item active">Permissions</li>
@endsection

@section('content')
<div class="ju-card">
    <div class="ju-card-header d-flex justify-content-between align-items-center">
        <h5 class="ju-card-title mb-0"><i class="fas fa-key me-2"></i>Permissions List</h5>
        
        <a href="{{ route('permissions.create') }}" class="btn btn-ju">
            <i class="fas fa-plus-circle me-2"></i>Add Permission
        </a>
    </div>
    
    <div class="ju-card-body">
        <!-- Search and Filters -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" placeholder="Search permissions..." id="searchInput">
                    <button class="btn btn-outline-secondary" type="button" onclick="searchTable()">
                        Search
                    </button>
                </div>
            </div>
            <div class="col-md-4">
                <select class="form-select" id="sortFilter">
                    <option value="newest">Newest First</option>
                    <option value="oldest">Oldest First</option>
                    <option value="name_asc">Name A-Z</option>
                    <option value="name_desc">Name Z-A</option>
                </select>
            </div>
        </div>
        
        <!-- Permissions Table -->
        <div class="table-responsive">
            <table class="table table-ju" id="permissionsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Permission Name</th>
                        <th>Slug</th>
                        <th>Description</th>
                        <th>Roles Count</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permissions as $permission)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <strong>{{ $permission->name }}</strong>
                        </td>
                        <td>
                            <code class="bg-light p-1 rounded">{{ $permission->slug }}</code>
                        </td>
                        <td>
                            @if($permission->description)
                                {{ Str::limit($permission->description, 60) }}
                            @else
                                <span class="text-muted">No description</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $permission->roles_count ?? $permission->roles->count() }}</span>
                        </td>
                        <td>
                            <div class="small">
                                {{ $permission->created_at->format('M d, Y') }}
                            </div>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('permissions.show', $permission) }}" class="btn btn-outline-success" 
                                   data-bs-toggle="tooltip" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <a href="{{ route('permissions.edit', $permission) }}" class="btn btn-outline-primary" 
                                   data-bs-toggle="tooltip" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                @if($permission->roles_count == 0)
                                <form method="POST" action="{{ route('permissions.destroy', $permission) }}" 
                                      class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" 
                                            data-bs-toggle="tooltip" title="Delete"
                                            onclick="return confirm('Delete this permission?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-key fa-3x mb-3"></i>
                                <h5>No permissions found</h5>
                                <p>Start by adding your first permission.</p>
                                <a href="{{ route('permissions.create') }}" class="btn btn-ju">
                                    <i class="fas fa-plus-circle me-2"></i>Add Permission
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($permissions->hasPages())
        <div class="row mt-4">
            <div class="col-md-12">
                <nav aria-label="Page navigation">
                    {{ $permissions->links() }}
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
                <h3 class="mb-0">{{ $permissions->total() }}</h3>
                <p class="text-muted mb-0">Total Permissions</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="ju-card">
            <div class="ju-card-body text-center">
                <h3 class="mb-0">{{ $permissions->where('description', '!=', null)->count() }}</h3>
                <p class="text-muted mb-0">With Description</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="ju-card">
            <div class="ju-card-body text-center">
                <h3 class="mb-0">{{ \App\Models\Role::count() }}</h3>
                <p class="text-muted mb-0">Total Roles</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="ju-card">
            <div class="ju-card-body text-center">
                <h3 class="mb-0">{{ $permissions->where('slug', 'like', '%view%')->count() }}</h3>
                <p class="text-muted mb-0">View Permissions</p>
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
            const rows = document.querySelectorAll('#permissionsTable tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        };
        
        // Sort function
        document.getElementById('sortFilter').addEventListener('change', function() {
            // This would typically make an AJAX call to sort
            // For now, we'll just show an alert
            alert('Sorting by: ' + this.value);
        });
    });
</script>
@endpush