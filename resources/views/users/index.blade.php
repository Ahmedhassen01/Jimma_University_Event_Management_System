@extends('layouts.app')

@section('title', 'Users - JU Event Management')
@section('page-title', 'User Management')
@section('page-subtitle', 'Manage system users')

@section('breadcrumb-items')
    <li class="breadcrumb-item active">Users</li>
@endsection

@section('content')
<div class="ju-card">
    <div class="ju-card-header d-flex justify-content-between align-items-center">
        <h5 class="ju-card-title mb-0"><i class="fas fa-users me-2"></i>Users List</h5>
        
        @if(auth()->user()->hasPermission('create_users'))
        <a href="{{ route('users.create') }}" class="btn btn-ju">
            <i class="fas fa-user-plus me-2"></i>Add New User
        </a>
        @endif
    </div>
    
    <div class="ju-card-body">
        <!-- Search and Filters -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" placeholder="Search users..." id="searchInput">
                    <button class="btn btn-outline-secondary" type="button" onclick="searchTable()">
                        Search
                    </button>
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="roleFilter">
                    <option value="">All Roles</option>
                    @foreach(\App\Models\Role::all() as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>
        
        <!-- Users Table -->
        <div class="table-responsive">
            <table class="table table-ju" id="usersTable">
                <thead>
                    <tr>
                        <th>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAll">
                            </div>
                        </th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input row-checkbox" type="checkbox" value="{{ $user->id }}">
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="user-avatar me-3">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <strong>{{ $user->name }}</strong>
                                    <div class="text-muted small">ID: {{ $user->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->role)
                                <span class="badge badge-ju">{{ $user->role->name }}</span>
                            @else
                                <span class="badge bg-secondary">No Role</span>
                            @endif
                        </td>
                        <td>
                            <div class="small">
                                {{ $user->created_at->format('M d, Y') }}<br>
                                <span class="text-muted">{{ $user->created_at->format('h:i A') }}</span>
                            </div>
                        </td>
                        <td>
                            @if($user->isAccountActive())
                                <span class="badge bg-success">
                                    <i class="fas fa-circle me-1"></i>Active
                                </span>
                            @elseif($user->status === 'pending')
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-clock me-1"></i>Pending
                                </span>
                            @elseif($user->status === 'suspended')
                                <span class="badge bg-danger">
                                    <i class="fas fa-ban me-1"></i>Suspended
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="fas fa-user-slash me-1"></i>Inactive
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('users.show', $user) }}" class="btn btn-outline-success" 
                                   data-bs-toggle="tooltip" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                @if(auth()->user()->hasPermission('edit_users'))
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-outline-primary" 
                                   data-bs-toggle="tooltip" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
                                
                                @if(auth()->user()->hasPermission('delete_users') && $user->id !== auth()->id())
                                <form method="POST" action="{{ route('users.destroy', $user) }}" 
                                      class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" 
                                            data-bs-toggle="tooltip" title="Deactivate"
                                            onclick="return confirmDelete()">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif

                                @php
                                    $canReactivate = auth()->user()->role && in_array(auth()->user()->role->slug, ['super-admin', 'super-administrator', 'administrator', 'admin']);
                                @endphp
                                @if($canReactivate && !$user->isAccountActive())
                                <form method="POST" action="{{ route('users.reactivate', $user) }}" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-outline-success" title="Reactivate"
                                            onclick="return confirm('Reactivate this account?')">
                                        <i class="fas fa-user-check"></i>
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
                                <i class="fas fa-users fa-3x mb-3"></i>
                                <h5>No users found</h5>
                                <p>Start by adding your first user.</p>
                                @if(auth()->user()->hasPermission('create_users'))
                                <a href="{{ route('users.create') }}" class="btn btn-ju">
                                    <i class="fas fa-user-plus me-2"></i>Add User
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Bulk Actions -->
        @if($users->count() > 0)
        <div class="row mt-3">
            <div class="col-md-6">
                <div class="btn-group">
                    <button class="btn btn-outline-secondary btn-sm" type="button" 
                            onclick="selectAllRows()">
                        Select All
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" type="button" 
                            onclick="deselectAllRows()">
                        Deselect All
                    </button>
                </div>
                <span class="ms-3 text-muted" id="selectedCount">0 selected</span>
            </div>
            <div class="col-md-6 text-end">
                <div class="btn-group">
                    <button class="btn btn-outline-success btn-sm" type="button" 
                            onclick="exportSelected()">
                        <i class="fas fa-download me-1"></i>Export
                    </button>
                    <button class="btn btn-outline-danger btn-sm" type="button" 
                            onclick="deleteSelected()">
                        <i class="fas fa-trash me-1"></i>Delete Selected
                    </button>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Pagination -->
        @if($users->hasPages())
        <div class="row mt-4">
            <div class="col-md-12">
                <nav aria-label="Page navigation">
                    {{ $users->links() }}
                </nav>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- User Stats -->
<div class="row mt-4">
    <div class="col-md-3 col-sm-6">
        <div class="ju-card">
            <div class="ju-card-body text-center">
                <h3 class="mb-0">{{ $users->total() }}</h3>
                <p class="text-muted mb-0">Total Users</p>
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
                <h3 class="mb-0">{{ \App\Models\User::whereDate('created_at', today())->count() }}</h3>
                <p class="text-muted mb-0">New Today</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="ju-card">
            <div class="ju-card-body text-center">
                <h3 class="mb-0">{{ \App\Models\User::whereMonth('created_at', now()->month)->count() }}</h3>
                <p class="text-muted mb-0">This Month</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .user-avatar {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--ju-green) 0%, var(--ju-dark-green) 100%);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.1rem;
    }
    
    .form-check-input:checked {
        background-color: var(--ju-green);
        border-color: var(--ju-green);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Select All checkbox
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSelectedCount();
        });
        
        // Update selected count
        document.querySelectorAll('.row-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedCount);
        });
        
        // Filters
        document.getElementById('roleFilter').addEventListener('change', filterTable);
        document.getElementById('statusFilter').addEventListener('change', filterTable);
        
        function updateSelectedCount() {
            const selected = document.querySelectorAll('.row-checkbox:checked').length;
            document.getElementById('selectedCount').textContent = selected + ' selected';
        }
        
        function filterTable() {
            const roleFilter = document.getElementById('roleFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            
            const rows = document.querySelectorAll('#usersTable tbody tr');
            
            rows.forEach(row => {
                const roleCell = row.querySelector('td:nth-child(4)');
                const statusCell = row.querySelector('td:nth-child(6)');
                const text = row.textContent.toLowerCase();
                
                const roleMatch = !roleFilter || roleCell.textContent.includes(roleFilter);
                const statusMatch = !statusFilter || statusCell.textContent.toLowerCase().includes(statusFilter);
                const searchMatch = !searchTerm || text.includes(searchTerm);
                
                row.style.display = (roleMatch && statusMatch && searchMatch) ? '' : 'none';
            });
        }
        
        function searchTable() {
            filterTable();
        }
        
        window.selectAllRows = function() {
            document.querySelectorAll('.row-checkbox').forEach(checkbox => {
                checkbox.checked = true;
            });
            document.getElementById('selectAll').checked = true;
            updateSelectedCount();
        };
        
        window.deselectAllRows = function() {
            document.querySelectorAll('.row-checkbox').forEach(checkbox => {
                checkbox.checked = false;
            });
            document.getElementById('selectAll').checked = false;
            updateSelectedCount();
        };
        
        window.confirmDelete = function() {
            return confirm('Deactivate this user account? They will not be able to login until reactivated.');
        };
        
        window.exportSelected = function() {
            const selected = Array.from(document.querySelectorAll('.row-checkbox:checked'))
                .map(cb => cb.value);
            
            if (selected.length === 0) {
                alert('Please select at least one user to export.');
                return;
            }
            
            alert('Exporting ' + selected.length + ' users...');
            // Implement export functionality here
        };
        
        window.deleteSelected = function() {
            const selected = Array.from(document.querySelectorAll('.row-checkbox:checked'))
                .map(cb => cb.value);
            
            if (selected.length === 0) {
                alert('Please select at least one user to delete.');
                return;
            }
            
            if (confirm('Are you sure you want to delete ' + selected.length + ' selected users? This action cannot be undone.')) {
                // Implement bulk delete functionality here
                alert('Deleting ' + selected.length + ' users...');
            }
        };
    });
</script>
@endpush
