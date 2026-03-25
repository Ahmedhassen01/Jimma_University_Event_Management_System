@extends('layouts.app')

@section('title', 'Edit User - JU Event Management')
@section('page-title', 'Edit User')
@section('page-subtitle', 'Update user information')

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
    <li class="breadcrumb-item"><a href="{{ route('users.show', $user) }}">{{ $user->name }}</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="ju-card">
            <div class="ju-card-header">
                <h5 class="ju-card-title mb-0"><i class="fas fa-user-edit me-2"></i>Edit User: {{ $user->name }}</h5>
            </div>
            <div class="ju-card-body">
                <form method="POST" action="{{ route('users.update', $user) }}" id="editUserForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $user->name) }}" 
                                       placeholder="Enter full name" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $user->email) }}" 
                                       placeholder="user@ju.edu.et" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password (Leave blank to keep current)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" placeholder="Enter new password">
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" 
                                       placeholder="Confirm new password">
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="role_id" class="form-label">Assign Role *</label>
                            <select class="form-select @error('role_id') is-invalid @enderror" 
                                    id="role_id" name="role_id" required>
                                <option value="">Select a role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Account Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="pending" {{ old('status', $user->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="suspended" {{ old('status', $user->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="alert alert-warning alert-ju mb-4">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Note:</strong> Changing the user's role will affect their permissions. 
                        Password fields should only be filled if you want to change the password.
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <div>
                            <a href="{{ route('users.show', $user) }}" class="btn btn-ju-outline me-2">
                                <i class="fas fa-eye me-2"></i>View
                            </a>
                            <a href="{{ route('users.index') }}" class="btn btn-ju-outline">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-ju">
                                <i class="fas fa-save me-2"></i>Update User
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- User Information -->
        <div class="ju-card mb-4">
            <div class="ju-card-header">
                <h5 class="ju-card-title mb-0"><i class="fas fa-info-circle me-2"></i>User Details</h5>
            </div>
            <div class="ju-card-body">
                <div class="text-center mb-3">
                    <div class="user-avatar-lg mx-auto mb-3">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <h5>{{ $user->name }}</h5>
                    <p class="text-muted">{{ $user->email }}</p>
                </div>
                
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between px-0">
                        <span>User ID:</span>
                        <strong>#{{ $user->id }}</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between px-0">
                        <span>Current Role:</span>
                        <strong>
                            @if($user->role)
                                {{ $user->role->name }}
                            @else
                                No Role
                            @endif
                        </strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between px-0">
                        <span>Member Since:</span>
                        <strong>{{ $user->created_at->format('M d, Y') }}</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between px-0">
                        <span>Last Updated:</span>
                        <strong>{{ $user->updated_at->format('M d, Y') }}</strong>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Danger Zone -->
        @if(auth()->user()->hasPermission('delete_users') && $user->id !== auth()->id())
        <div class="ju-card">
            <div class="ju-card-header bg-danger text-white">
                <h5 class="ju-card-title mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Danger Zone</h5>
            </div>
            <div class="ju-card-body">
                <div class="alert alert-danger">
                    <h6><i class="fas fa-user-slash me-2"></i>Account Deactivation</h6>
                    <p class="mb-3">This will deactivate the account and keep all related data for audit/history.</p>
                    
                    <form method="POST" action="{{ route('users.destroy', $user) }}" 
                          id="deleteForm" class="text-center">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-outline-danger btn-sm" 
                                onclick="confirmDelete()">
                            <i class="fas fa-user-slash me-2"></i>Deactivate This User
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .user-avatar-lg {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--ju-green) 0%, var(--ju-dark-green) 100%);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 2rem;
    }
</style>
@endpush

@push('scripts')
<script>
    function confirmDelete() {
        if (confirm('Deactivate this user account? They will not be able to login until reactivated.')) {
            document.getElementById('deleteForm').submit();
        }
    }
    
    // Password confirmation check
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');
        
        confirmInput.addEventListener('input', function() {
            const matchText = this.nextElementSibling;
            if (passwordInput.value !== this.value && this.value !== '') {
                matchText.textContent = 'Passwords do not match';
                matchText.style.color = '#dc3545';
            } else {
                matchText.textContent = '';
            }
        });
    });
</script>
@endpush
