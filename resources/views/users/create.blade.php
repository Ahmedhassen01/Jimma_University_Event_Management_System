@extends('layouts.app')

@section('title', 'Add User - JU Event Management')
@section('page-title', 'Add New User')
@section('page-subtitle', 'Create a new user account')

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
    <li class="breadcrumb-item active">Add User</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="ju-card">
            <div class="ju-card-header">
                <h5 class="ju-card-title mb-0"><i class="fas fa-user-plus me-2"></i>User Information</h5>
            </div>
            <div class="ju-card-body">
                <form method="POST" action="{{ route('users.store') }}" id="userForm">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" 
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
                                       id="email" name="email" value="{{ old('email') }}" 
                                       placeholder="user@ju.edu.et" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="form-text text-muted">Use Jimma University email if available</small>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" placeholder="Create password" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="password-strength mt-2">
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar" id="passwordStrength" role="progressbar" style="width: 0%"></div>
                                </div>
                                <small class="form-text text-muted" id="passwordFeedback"></small>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" 
                                       placeholder="Confirm password" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <small class="form-text text-muted" id="passwordMatch"></small>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="role_id" class="form-label">Assign Role *</label>
                            <select class="form-select @error('role_id') is-invalid @enderror" 
                                    id="role_id" name="role_id" required>
                                <option value="">Select a role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
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
                                <option value="active" selected>Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="pending">Pending</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Additional Permissions</label>
                        <div class="form-text text-muted mb-2">
                            Permissions are inherited from the assigned role. Additional permissions can be added here:
                        </div>
                        <div class="border rounded p-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="sendWelcomeEmail" name="send_welcome_email">
                                <label class="form-check-label" for="sendWelcomeEmail">
                                    Send welcome email with login instructions
                                </label>
                            </div>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" id="requirePasswordChange" name="require_password_change">
                                <label class="form-check-label" for="requirePasswordChange">
                                    Require password change on first login
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('users.index') }}" class="btn btn-ju-outline">
                            <i class="fas fa-arrow-left me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-ju">
                            <i class="fas fa-save me-2"></i>Create User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Role Information -->
        <div class="ju-card mb-4">
            <div class="ju-card-header">
                <h5 class="ju-card-title mb-0"><i class="fas fa-info-circle me-2"></i>Role Information</h5>
            </div>
            <div class="ju-card-body">
                <div id="roleInfo">
                    <p class="text-muted">Select a role to view its details and permissions.</p>
                </div>
            </div>
        </div>
        
        <!-- Quick Tips -->
        <div class="ju-card">
            <div class="ju-card-header">
                <h5 class="ju-card-title mb-0"><i class="fas fa-lightbulb me-2"></i>Quick Tips</h5>
            </div>
            <div class="ju-card-body">
                <div class="alert alert-info alert-ju">
                    <h6><i class="fas fa-check-circle me-2"></i>Best Practices:</h6>
                    <ul class="mb-0 ps-3">
                        <li>Use strong passwords (min. 8 characters)</li>
                        <li>Assign appropriate roles based on responsibilities</li>
                        <li>Send welcome emails for new accounts</li>
                        <li>Verify email addresses before activation</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role_id');
        const roleInfo = document.getElementById('roleInfo');
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');
        const strengthBar = document.getElementById('passwordStrength');
        const feedback = document.getElementById('passwordFeedback');
        const matchText = document.getElementById('passwordMatch');
        
        // Role information display
        roleSelect.addEventListener('change', function() {
            const roleId = this.value;
            
            if (!roleId) {
                roleInfo.innerHTML = '<p class="text-muted">Select a role to view its details and permissions.</p>';
                return;
            }
            
            // In a real application, you would fetch this data via AJAX
            const roles = {
                @foreach($roles as $role)
                "{{ $role->id }}": {
                    name: "{{ $role->name }}",
                    description: "{{ $role->description }}",
                    permissions: @json($role->permissions->pluck('name'))
                },
                @endforeach
            };
            
            const role = roles[roleId];
            if (role) {
                let html = `
                    <h6>${role.name}</h6>
                    <p class="text-muted">${role.description || 'No description available.'}</p>
                    <h6 class="mt-3">Permissions:</h6>
                `;
                
                if (role.permissions.length > 0) {
                    html += '<ul class="list-unstyled">';
                    role.permissions.forEach(permission => {
                        html += `<li><i class="fas fa-check text-success me-2"></i>${permission}</li>`;
                    });
                    html += '</ul>';
                } else {
                    html += '<p class="text-muted">No specific permissions assigned.</p>';
                }
                
                roleInfo.innerHTML = html;
            }
        });
        
        // Password strength checker
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            let feedbackText = '';
            let barColor = '';
            
            // Check password strength
            if (password.length >= 8) strength += 25;
            if (/[A-Z]/.test(password)) strength += 25;
            if (/[0-9]/.test(password)) strength += 25;
            if (/[^A-Za-z0-9]/.test(password)) strength += 25;
            
            // Set feedback
            if (strength < 25) {
                feedbackText = 'Very Weak';
                barColor = '#dc3545';
            } else if (strength < 50) {
                feedbackText = 'Weak';
                barColor = '#ffc107';
            } else if (strength < 75) {
                feedbackText = 'Good';
                barColor = '#17a2b8';
            } else {
                feedbackText = 'Strong';
                barColor = '#28a745';
            }
            
            // Update UI
            strengthBar.style.width = strength + '%';
            strengthBar.style.backgroundColor = barColor;
            feedback.textContent = feedbackText;
            feedback.style.color = barColor;
        });
        
        // Password confirmation check
        confirmInput.addEventListener('input', function() {
            if (passwordInput.value !== this.value) {
                matchText.textContent = 'Passwords do not match';
                matchText.style.color = '#dc3545';
            } else {
                matchText.textContent = 'Passwords match';
                matchText.style.color = '#28a745';
            }
        });
        
        // Form validation
        document.getElementById('userForm').addEventListener('submit', function(e) {
            if (passwordInput.value !== confirmInput.value) {
                e.preventDefault();
                alert('Passwords do not match. Please check and try again.');
                passwordInput.focus();
            }
        });
    });
</script>
@endpush