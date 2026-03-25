@extends('layouts.app')

@section('title', 'User Details - JU Event Management')
@section('page-title', 'User Details')
@section('page-subtitle', 'View user information and permissions')

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
    <li class="breadcrumb-item active">{{ $user->name }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-4">
        <!-- User Profile Card -->
        <div class="ju-card mb-4">
            <div class="ju-card-body text-center">
                @if($user->profile_photo_url)
                    <div class="user-profile-image mb-3">
                        <img src="{{ $user->profile_photo_url }}" 
                             alt="{{ $user->name }}" class="rounded-circle" width="100" height="100" style="object-fit: cover;">
                    </div>
                @else
                    <div class="user-profile-avatar mb-3">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                
                <h4>{{ $user->name }}</h4>
                <p class="text-muted">{{ $user->email }}</p>
                
                <div class="mb-3">
                    @if($user->role)
                        <span class="badge badge-ju fs-6 px-3 py-2">{{ $user->role->name }}</span>
                    @else
                        <span class="badge bg-secondary fs-6 px-3 py-2">No Role Assigned</span>
                    @endif
                </div>
                
                <div class="mb-3">
                    @if($user->isAccountActive())
                        <span class="badge bg-success">Active</span>
                    @elseif($user->status == 'pending')
                        <span class="badge bg-warning text-dark">Pending Approval</span>
                    @elseif($user->status == 'suspended')
                        <span class="badge bg-danger">Suspended</span>
                    @else
                        <span class="badge bg-secondary">Inactive</span>
                    @endif
                </div>
                
                <div class="d-grid gap-2">
                    @if(auth()->user()->hasPermission('edit_users'))
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-ju">
                        <i class="fas fa-edit me-2"></i>Edit User
                    </a>
                    @endif
                    
                    @if($user->status == 'pending' && auth()->user()->hasPermission('approve_users'))
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveUserModal">
                        <i class="fas fa-check-circle me-2"></i>Approve Account
                    </button>
                    @endif

                    @php
                        $canReactivate = auth()->user()->role && in_array(auth()->user()->role->slug, ['super-admin', 'super-administrator', 'administrator', 'admin']);
                    @endphp
                    @if($canReactivate && !$user->isAccountActive())
                    <form method="POST" action="{{ route('users.reactivate', $user) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success w-100" onclick="return confirm('Reactivate this account?')">
                            <i class="fas fa-user-check me-2"></i>Reactivate Account
                        </button>
                    </form>
                    @endif
                    
                    <button type="button" class="btn btn-ju-outline" data-bs-toggle="modal" data-bs-target="#sendMessageModal">
                        <i class="fas fa-envelope me-2"></i>Send Message
                    </button>
                </div>
            </div>
        </div>
        
        <!-- User Stats -->
        <div class="ju-card">
            <div class="ju-card-header">
                <h5 class="ju-card-title mb-0"><i class="fas fa-chart-bar me-2"></i>User Statistics</h5>
            </div>
            <div class="ju-card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span><i class="fas fa-calendar-alt me-2"></i>Joined</span>
                        <strong>{{ $user->created_at->format('M d, Y') }}</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span><i class="fas fa-clock me-2"></i>Last Updated</span>
                        <strong>{{ $user->updated_at->format('M d, Y') }}</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span><i class="fas fa-phone me-2"></i>Contact</span>
                        <strong>{{ $user->phone ?? 'Not provided' }}</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span><i class="fas fa-calendar-day me-2"></i>Date of Birth</span>
                        <strong>{{ $user->date_of_birth ? $user->date_of_birth->format('M d, Y') : 'Not provided' }}</strong>
                    </div>
                    @if($user->approved_at)
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span><i class="fas fa-check-circle me-2 text-success"></i>Approved On</span>
                        <strong>{{ $user->approved_at->format('M d, Y') }}</strong>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Newsletter Subscription -->
        <div class="ju-card mt-4">
            <div class="ju-card-header">
                <h5 class="ju-card-title mb-0"><i class="fas fa-newspaper me-2"></i>Newsletter</h5>
            </div>
            <div class="ju-card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        @if($user->newsletter_subscription)
                            <span class="badge bg-success p-2"><i class="fas fa-check"></i></span>
                        @else
                            <span class="badge bg-secondary p-2"><i class="fas fa-times"></i></span>
                        @endif
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0">Newsletter Subscription</h6>
                        <small class="text-muted">
                            @if($user->newsletter_subscription)
                                Subscribed to newsletter
                            @else
                                Not subscribed to newsletter
                            @endif
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <!-- User Details Tabs -->
        <div class="ju-card">
            <div class="ju-card-header">
                <ul class="nav nav-tabs card-header-tabs" id="userTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="details-tab" data-bs-toggle="tab" 
                                data-bs-target="#details" type="button" role="tab">
                            <i class="fas fa-info-circle me-2"></i>Personal Details
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="professional-tab" data-bs-toggle="tab" 
                                data-bs-target="#professional" type="button" role="tab">
                            <i class="fas fa-briefcase me-2"></i>Professional Info
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="permissions-tab" data-bs-toggle="tab" 
                                data-bs-target="#permissions" type="button" role="tab">
                            <i class="fas fa-key me-2"></i>Permissions
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="activity-tab" data-bs-toggle="tab" 
                                data-bs-target="#activity" type="button" role="tab">
                            <i class="fas fa-history me-2"></i>Activity
                        </button>
                    </li>
                </ul>
            </div>
            
            <div class="ju-card-body">
                <div class="tab-content" id="userTabsContent">
                    <!-- Personal Details Tab -->
                    <div class="tab-pane fade show active" id="details" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-3" style="color: #0d2b4b;">Basic Information</h6>
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="text-muted ps-0">Full Name:</td>
                                        <td class="fw-bold">{{ $user->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted ps-0">Email Address:</td>
                                        <td class="fw-bold">{{ $user->email }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted ps-0">Phone Number:</td>
                                        <td class="fw-bold">{{ $user->phone ?? 'Not provided' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted ps-0">Date of Birth:</td>
                                        <td class="fw-bold">
                                            {{ $user->date_of_birth ? $user->date_of_birth->format('F d, Y') : 'Not provided' }}
                                            @if($user->date_of_birth)
                                                <br><small class="text-muted">{{ $user->date_of_birth->age }} years old</small>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted ps-0">User ID:</td>
                                        <td class="fw-bold">#{{ $user->id }}</td>
                                    </tr>
                                </table>
                            </div>
                            
                            <div class="col-md-6">
                                <h6 class="mb-3" style="color: #0d2b4b;">Account Status</h6>
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="text-muted ps-0">Account Status:</td>
                                        <td>
                                            @if($user->isAccountActive())
                                                <span class="badge bg-success">Active</span>
                                            @elseif($user->status == 'pending')
                                                <span class="badge bg-warning text-dark">Pending Approval</span>
                                            @elseif($user->status == 'suspended')
                                                <span class="badge bg-danger">Suspended</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted ps-0">Email Verified:</td>
                                        <td>
                                            @if($user->email_verified_at)
                                                <span class="badge bg-success">Verified</span>
                                                <small class="text-muted d-block">{{ $user->email_verified_at->format('M d, Y') }}</small>
                                            @else
                                                <span class="badge bg-warning text-dark">Not Verified</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @if($user->approved_by)
                                    <tr>
                                        <td class="text-muted ps-0">Approved By:</td>
                                        <td class="fw-bold">{{ $user->approver->name ?? 'Unknown' }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6 class="mb-3" style="color: #0d2b4b;">Contact Information</h6>
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="text-muted ps-0" width="200">Office Address:</td>
                                        <td class="fw-bold">{{ $user->office_address ?? 'Not provided' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Professional Info Tab -->
                    <div class="tab-pane fade" id="professional" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-3" style="color: #0d2b4b;">Professional Details</h6>
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="text-muted ps-0">Expertise/Role:</td>
                                        <td>
                                            @if($user->expertise)
                                                <span class="badge badge-ju p-2">
                                                    {{ ucfirst(str_replace('_', ' ', $user->expertise)) }}
                                                </span>
                                            @else
                                                <span class="text-muted">Not specified</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted ps-0">Department/Unit:</td>
                                        <td class="fw-bold">{{ $user->department ?? 'Not provided' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted ps-0">Faculty/College:</td>
                                        <td class="fw-bold">{{ $user->faculty ?? 'Not provided' }}</td>
                                    </tr>
                                </table>
                            </div>
                            
                            <div class="col-md-6">
                                <h6 class="mb-3" style="color: #0d2b4b;">Identification</h6>
                                <table class="table table-borderless">
                                    @if($user->expertise == 'student')
                                    <tr>
                                        <td class="text-muted ps-0">Student ID:</td>
                                        <td class="fw-bold">{{ $user->student_id ?? 'Not provided' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted ps-0">Year of Study:</td>
                                        <td class="fw-bold">
                                            @if($user->year_of_study)
                                                @if($user->year_of_study == 'graduate')
                                                    Graduate Student
                                                @else
                                                    {{ $user->year_of_study }} {{ $user->year_of_study > 1 ? 'Years' : 'Year' }}
                                                @endif
                                            @else
                                                Not provided
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                    
                                    @if(in_array($user->expertise, ['employee', 'faculty', 'staff']))
                                    <tr>
                                        <td class="text-muted ps-0">Employee ID:</td>
                                        <td class="fw-bold">{{ $user->employee_id ?? 'Not provided' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted ps-0">Position/Title:</td>
                                        <td class="fw-bold">{{ $user->position ?? 'Not provided' }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                        
                        @if($user->expertise)
                        <div class="alert alert-info alert-ju mt-3" style="background-color: #e3f2fd; border-left: 4px solid #0d2b4b;">
                            <i class="fas fa-info-circle me-2" style="color: #0d2b4b;"></i>
                            <strong style="color: #0d2b4b;">User Type:</strong> 
                            This user is registered as 
                            <span class="fw-bold">{{ ucfirst(str_replace('_', ' ', $user->expertise)) }}</span>
                            @if($user->expertise == 'student' && $user->student_id)
                                with Student ID: {{ $user->student_id }}
                            @elseif(in_array($user->expertise, ['employee', 'faculty', 'staff']) && $user->employee_id)
                                with Employee ID: {{ $user->employee_id }}
                            @endif
                        </div>
                        @endif
                    </div>
                    
                    <!-- Permissions Tab -->
                    <div class="tab-pane fade" id="permissions" role="tabpanel">
                        @if($user->role && $user->role->permissions->count() > 0)
                        <h6 class="mb-3" style="color: #0d2b4b;">Role-Based Permissions</h6>
                        <p class="text-muted mb-4">Permissions inherited from the <strong>{{ $user->role->name }}</strong> role:</p>
                        
                        <div class="row">
                            @foreach($user->role->permissions as $permission)
                            <div class="col-md-6 mb-3">
                                <div class="card border-success border-start border-3">
                                    <div class="card-body py-2">
                                        <h6 class="card-title mb-1">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            {{ $permission->name }}
                                        </h6>
                                        <p class="card-text text-muted small mb-0">
                                            {{ $permission->description ?? 'No description' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <div class="alert alert-info alert-ju mt-4">
                            <i class="fas fa-info-circle me-2"></i>
                            To modify these permissions, edit the <a href="{{ route('roles.edit', $user->role) }}" class="alert-link">{{ $user->role->name }}</a> role.
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="fas fa-key fa-3x text-muted mb-3"></i>
                            <h5>No Permissions Assigned</h5>
                            <p class="text-muted">This user has no permissions. Assign a role to grant permissions.</p>
                            @if(auth()->user()->hasPermission('edit_users'))
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-ju">
                                <i class="fas fa-user-tag me-2"></i>Assign Role
                            </a>
                            @endif
                        </div>
                        @endif
                    </div>
                    
                    <!-- Activity Tab -->
                    <div class="tab-pane fade" id="activity" role="tabpanel">
                        <h6 class="mb-3" style="color: #0d2b4b;">Recent Activity</h6>
                        
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Account Created</h6>
                                    <p class="text-muted mb-0">{{ $user->created_at->format('M d, Y h:i A') }}</p>
                                </div>
                            </div>
                            
                            <div class="timeline-item">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Last Profile Update</h6>
                                    <p class="text-muted mb-0">{{ $user->updated_at->format('M d, Y h:i A') }}</p>
                                </div>
                            </div>
                            
                            @if($user->email_verified_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Email Verified</h6>
                                    <p class="text-muted mb-0">{{ $user->email_verified_at->format('M d, Y h:i A') }}</p>
                                </div>
                            </div>
                            @endif
                            
                            @if($user->approved_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Account Approved</h6>
                                    <p class="text-muted mb-0">{{ $user->approved_at->format('M d, Y h:i A') }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        <div class="text-center mt-4">
                            <button class="btn btn-ju-outline">
                                <i class="fas fa-history me-2"></i>View Full Activity Log
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="ju-card text-center h-100">
                    <div class="ju-card-body">
                        <i class="fas fa-redo-alt fa-2x text-primary mb-3"></i>
                        <h6>Reset Password</h6>
                        <p class="small text-muted">Send password reset email</p>
                        <button class="btn btn-sm btn-ju-outline">Reset</button>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="ju-card text-center h-100">
                    <div class="ju-card-body">
                        <i class="fas fa-user-lock fa-2x text-warning mb-3"></i>
                        <h6>Lock Account</h6>
                        <p class="small text-muted">Temporarily disable access</p>
                        <button class="btn btn-sm btn-ju-outline">Lock</button>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="ju-card text-center h-100">
                    <div class="ju-card-body">
                        <i class="fas fa-file-export fa-2x text-success mb-3"></i>
                        <h6>Export Data</h6>
                        <p class="small text-muted">Download user information</p>
                        <button class="btn btn-sm btn-ju-outline">Export</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Send Message Modal -->
<div class="modal fade" id="sendMessageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Message to {{ $user->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Subject</label>
                        <input type="text" class="form-control" placeholder="Enter message subject">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea class="form-control" rows="4" placeholder="Type your message here..."></textarea>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="sendCopy">
                        <label class="form-check-label" for="sendCopy">
                            Send a copy to my email
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ju-outline" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-ju">Send Message</button>
            </div>
        </div>
    </div>
</div>

<!-- Approve User Modal -->
@if($user->status == 'pending')
<div class="modal fade" id="approveUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #0a1929 0%, #0d2b4b 100%);">
                <h5 class="modal-title text-white">Approve User Account</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to approve <strong>{{ $user->name }}</strong>'s account?</p>
                <p class="text-muted small">This will activate the user account and send a notification email.</p>
                
                <form action="{{ route('users.approve', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Assign Role (Optional)</label>
                        <select class="form-select" name="role_id">
                            <option value="">Select a role</option>
                            @foreach($roles ?? [] as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">You can assign a role now or later</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Welcome Message (Optional)</label>
                        <textarea class="form-control" name="welcome_message" rows="3" 
                                  placeholder="Add a personal welcome message..."></textarea>
                    </div>
                    
                    <div class="modal-footer px-0 pb-0">
                        <button type="button" class="btn btn-ju-outline" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check-circle me-2"></i>Approve Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('styles')
<style>
    .user-profile-avatar {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, var(--ju-green) 0%, var(--ju-dark-green) 100%);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 2.5rem;
        margin: 0 auto;
    }
    
    .user-profile-image {
        width: 100px;
        height: 100px;
        margin: 0 auto;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid #0d2b4b;
    }
    
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }
    
    .timeline-marker {
        position: absolute;
        left: -30px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid white;
    }
    
    .timeline-content {
        padding-left: 10px;
    }
    
    .nav-tabs .nav-link {
        color: #666;
        border: none;
        padding: 10px 20px;
    }
    
    .nav-tabs .nav-link.active {
        color: #0d2b4b;
        border-bottom: 2px solid #0d2b4b;
        background: transparent;
        font-weight: 500;
    }
    
    .nav-tabs .nav-link:hover {
        color: #0d2b4b;
        border-bottom: 2px solid #0d2b4b;
    }
    
    .table-borderless td {
        padding: 0.5rem 0;
    }
    
    .badge-ju {
        background: linear-gradient(135deg, #0a1929 0%, #0d2b4b 100%);
        color: white;
    }
    
    .btn-ju {
        background: linear-gradient(135deg, #0a1929 0%, #0d2b4b 100%);
        color: white;
        border: none;
    }
    
    .btn-ju:hover {
        background: linear-gradient(135deg, #0d2b4b 0%, #1a3a5f 100%);
        color: white;
    }
    
    .btn-ju-outline {
        border: 1px solid #0d2b4b;
        color: #0d2b4b;
        background: transparent;
    }
    
    .btn-ju-outline:hover {
        background: #0d2b4b;
        color: white;
    }
    
    .alert-ju {
        border-left: 4px solid #0d2b4b;
        background-color: #f8f9fa;
    }
</style>
@endpush
