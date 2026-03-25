@extends('layouts.app')

@section('title', 'My Profile - JU Event Management')
@section('page-title', 'My Profile')
@section('page-subtitle', 'View and manage your account information')

@section('breadcrumb-items')
    <li class="breadcrumb-item active">Profile</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-4">
        <!-- Profile Card -->
        <div class="ju-card mb-4">
            <div class="ju-card-body text-center">
                <div class="profile-image-container mb-3">
                    @if($user->profile_photo_url)
                        <img src="{{ $user->profile_photo_url }}" 
                             alt="{{ $user->name }}" 
                             class="profile-image">
                    @else
                        <div class="profile-initial">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    
                    <a href="{{ route('profile.edit') }}" class="profile-image-edit" data-bs-toggle="tooltip" title="Edit Profile">
                        <i class="fas fa-camera"></i>
                    </a>
                </div>
                
                <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                <p class="text-muted mb-2">{{ $user->email }}</p>
                
                @if($user->role)
                    <span class="badge badge-ju fs-6 px-3 py-2 mb-3">{{ $user->role->name }}</span>
                @endif
                
                <div class="mb-3">
                    @if($user->status == 'active')
                        <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Active Account</span>
                    @elseif($user->status == 'pending')
                        <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Pending Approval</span>
                    @else
                        <span class="badge bg-danger"><i class="fas fa-exclamation-circle me-1"></i>Suspended</span>
                    @endif
                    
                    @if($user->email_verified_at)
                        <span class="badge bg-info ms-1"><i class="fas fa-envelope me-1"></i>Verified</span>
                    @endif
                </div>
                
                <hr class="my-3">
                
                <div class="profile-stats d-flex justify-content-around">
                    <div class="stat-item">
                        <h5 class="fw-bold text-ju">{{ $stats['total_events'] }}</h5>
                        <small class="text-muted">Total Events</small>
                    </div>
                    <div class="stat-item">
                        <h5 class="fw-bold text-ju">{{ $stats['upcoming_events'] }}</h5>
                        <small class="text-muted">Upcoming</small>
                    </div>
                    <div class="stat-item">
                        <h5 class="fw-bold text-ju">{{ $stats['notifications'] }}</h5>
                        <small class="text-muted">Notifications</small>
                    </div>
                </div>
                
                <hr class="my-3">
                
                <div class="profile-actions">
                    <a href="{{ route('profile.edit') }}" class="btn btn-ju w-100 mb-2">
                        <i class="fas fa-edit me-2"></i>Edit Profile
                    </a>
                    <a href="{{ route('my-events.index') }}" class="btn btn-ju-outline w-100">
                        <i class="fas fa-calendar-alt me-2"></i>My Events
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Contact Info Card -->
        <div class="ju-card">
            <div class="ju-card-header" style="background: linear-gradient(135deg, #0a1929 0%, #0d2b4b 100%);">
                <h5 class="ju-card-title mb-0 text-white"><i class="fas fa-address-card me-2"></i>Contact Information</h5>
            </div>
            <div class="ju-card-body">
                <div class="contact-item mb-3 d-flex align-items-center">
                    <div class="contact-icon me-3">
                        <i class="fas fa-phone fa-fw" style="color: #0d2b4b;"></i>
                    </div>
                    <div class="contact-detail">
                        <small class="text-muted d-block">Phone Number</small>
                        <span class="fw-bold">{{ $user->phone ?? 'Not provided' }}</span>
                    </div>
                </div>
                
                <div class="contact-item mb-3 d-flex align-items-center">
                    <div class="contact-icon me-3">
                        <i class="fas fa-map-marker-alt fa-fw" style="color: #0d2b4b;"></i>
                    </div>
                    <div class="contact-detail">
                        <small class="text-muted d-block">Office Address</small>
                        <span class="fw-bold">{{ $user->office_address ?? 'Not provided' }}</span>
                    </div>
                </div>
                
                <div class="contact-item mb-3 d-flex align-items-center">
                    <div class="contact-icon me-3">
                        <i class="fas fa-calendar-alt fa-fw" style="color: #0d2b4b;"></i>
                    </div>
                    <div class="contact-detail">
                        <small class="text-muted d-block">Member Since</small>
                        <span class="fw-bold">{{ $user->created_at->format('F d, Y') }}</span>
                    </div>
                </div>
                
                <div class="contact-item d-flex align-items-center">
                    <div class="contact-icon me-3">
                        <i class="fas fa-clock fa-fw" style="color: #0d2b4b;"></i>
                    </div>
                    <div class="contact-detail">
                        <small class="text-muted d-block">Last Updated</small>
                        <span class="fw-bold">{{ $user->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Newsletter Card -->
        <div class="ju-card mt-4">
            <div class="ju-card-header" style="background: linear-gradient(135deg, #0a1929 0%, #0d2b4b 100%);">
                <h5 class="ju-card-title mb-0 text-white"><i class="fas fa-newspaper me-2"></i>Newsletter</h5>
            </div>
            <div class="ju-card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="mb-1">Newsletter Subscription</h6>
                        <p class="text-muted small mb-0">Receive updates and announcements</p>
                    </div>
                    <div>
                        @if($user->newsletter_subscription)
                            <span class="badge bg-success p-2"><i class="fas fa-check me-1"></i>Subscribed</span>
                        @else
                            <span class="badge bg-secondary p-2"><i class="fas fa-times me-1"></i>Not Subscribed</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <!-- Profile Details Tabs -->
        <div class="ju-card">
            <div class="ju-card-header" style="background: linear-gradient(135deg, #0a1929 0%, #0d2b4b 100%);">
                <ul class="nav nav-tabs card-header-tabs" id="profileTabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" 
                                data-bs-target="#personal" type="button" role="tab">
                            <i class="fas fa-user me-2"></i>Personal Info
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="professional-tab" data-bs-toggle="tab" 
                                data-bs-target="#professional" type="button" role="tab">
                            <i class="fas fa-briefcase me-2"></i>Professional
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="events-tab" data-bs-toggle="tab" 
                                data-bs-target="#events" type="button" role="tab">
                            <i class="fas fa-calendar-alt me-2"></i>My Events
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="preferences-tab" data-bs-toggle="tab" 
                                data-bs-target="#preferences" type="button" role="tab">
                            <i class="fas fa-sliders-h me-2"></i>Preferences
                        </button>
                    </li>
                </ul>
            </div>
            
            <div class="ju-card-body">
                <div class="tab-content" id="profileTabsContent">
                    <!-- Personal Info Tab -->
                    <div class="tab-pane fade show active" id="personal" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-3" style="color: #0d2b4b; font-weight: 600;">
                                    <i class="fas fa-info-circle me-2"></i>Basic Information
                                </h6>
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="text-muted ps-0" width="120">Full Name:</td>
                                        <td class="fw-bold">{{ $user->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted ps-0">Email:</td>
                                        <td class="fw-bold">{{ $user->email }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted ps-0">Phone:</td>
                                        <td class="fw-bold">{{ $user->phone ?? 'Not provided' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted ps-0">Date of Birth:</td>
                                        <td>
                                            @if($user->date_of_birth)
                                                <span class="fw-bold">{{ $user->date_of_birth->format('F d, Y') }}</span>
                                                <br><small class="text-muted">{{ $user->date_of_birth->age }} years old</small>
                                            @else
                                                <span class="text-muted">Not provided</span>
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
                                <h6 class="mb-3" style="color: #0d2b4b; font-weight: 600;">
                                    <i class="fas fa-shield-alt me-2"></i>Account Status
                                </h6>
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="text-muted ps-0" width="120">Status:</td>
                                        <td>
                                            @if($user->status == 'active')
                                                <span class="badge bg-success">Active</span>
                                            @elseif($user->status == 'pending')
                                                <span class="badge bg-warning text-dark">Pending Approval</span>
                                            @else
                                                <span class="badge bg-danger">Suspended</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted ps-0">Email Verified:</td>
                                        <td>
                                            @if($user->email_verified_at)
                                                <span class="badge bg-success">Verified</span>
                                                <small class="d-block text-muted">{{ $user->email_verified_at->format('M d, Y') }}</small>
                                            @else
                                                <span class="badge bg-warning text-dark">Not Verified</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted ps-0">Member Since:</td>
                                        <td class="fw-bold">{{ $user->created_at->format('M d, Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted ps-0">Last Login:</td>
                                        <td class="fw-bold">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        @if($user->office_address)
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="alert alert-info alert-ju mb-0">
                                    <i class="fas fa-map-marker-alt me-2" style="color: #0d2b4b;"></i>
                                    <strong>Office Address:</strong> {{ $user->office_address }}
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Professional Info Tab -->
                    <div class="tab-pane fade" id="professional" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-3" style="color: #0d2b4b; font-weight: 600;">
                                    <i class="fas fa-briefcase me-2"></i>Professional Details
                                </h6>
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="text-muted ps-0" width="130">Expertise:</td>
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
                                        <td class="text-muted ps-0">Department:</td>
                                        <td class="fw-bold">{{ $user->department ?? 'Not provided' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted ps-0">Faculty:</td>
                                        <td class="fw-bold">{{ $user->faculty ?? 'Not provided' }}</td>
                                    </tr>
                                </table>
                            </div>
                            
                            <div class="col-md-6">
                                <h6 class="mb-3" style="color: #0d2b4b; font-weight: 600;">
                                    <i class="fas fa-id-card me-2"></i>Identification
                                </h6>
                                <table class="table table-borderless">
                                    @if($user->expertise == 'student')
                                    <tr>
                                        <td class="text-muted ps-0" width="130">Student ID:</td>
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
                                    @elseif(in_array($user->expertise, ['employee', 'faculty', 'staff']))
                                    <tr>
                                        <td class="text-muted ps-0" width="130">Employee ID:</td>
                                        <td class="fw-bold">{{ $user->employee_id ?? 'Not provided' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted ps-0">Position:</td>
                                        <td class="fw-bold">{{ $user->position ?? 'Not provided' }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                        
                        @if($user->expertise)
                        <div class="alert alert-ju mt-3" style="background-color: #e3f2fd; border-left: 4px solid #0d2b4b;">
                            <i class="fas fa-info-circle me-2" style="color: #0d2b4b;"></i>
                            <span>You are registered as <strong>{{ $user->user_type_label }}</strong>
                            @if($user->expertise == 'student' && $user->student_id)
                                with Student ID: <strong>{{ $user->student_id }}</strong>
                            @elseif(in_array($user->expertise, ['employee', 'faculty', 'staff']) && $user->employee_id)
                                with Employee ID: <strong>{{ $user->employee_id }}</strong>
                            @endif
                            </span>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Events Tab -->
                    <div class="tab-pane fade" id="events" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h6 class="mb-0" style="color: #0d2b4b; font-weight: 600;">
                                <i class="fas fa-calendar-check me-2"></i>Recent Event Registrations
                            </h6>
                            <a href="{{ route('my-events.index') }}" class="btn btn-sm btn-ju-outline">
                                View All <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                        
                        @if($user->registrations->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead style="background-color: #e9ecef;">
                                        <tr>
                                            <th>Event</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Registered On</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($user->registrations as $registration)
                                        <tr>
                                            <td>
                                                <a href="{{ route('events.guest.show', $registration->event->slug) }}" class="text-decoration-none fw-bold" style="color: #0d2b4b;">
                                                    {{ $registration->event->title }}
                                                </a>
                                            </td>
                                            <td>
                                                {{ $registration->event->start_date->format('M d, Y') }}
                                                <br><small class="text-muted">{{ $registration->event->start_date->format('h:i A') }}</small>
                                            </td>
                                            <td>
                                                @if($registration->status == 'confirmed')
                                                    <span class="badge bg-success">Confirmed</span>
                                                @elseif($registration->status == 'pending')
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @elseif($registration->status == 'cancelled')
                                                    <span class="badge bg-danger">Cancelled</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($registration->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $registration->created_at->format('M d, Y') }}</small>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                                <h5 class="fw-bold">No Event Registrations</h5>
                                <p class="text-muted mb-3">You haven't registered for any events yet.</p>
                                <a href="{{ route('event-registration.index') }}" class="btn btn-ju">
                                    <i class="fas fa-calendar-plus me-2"></i>Browse Events
                                </a>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Preferences Tab -->
                    <div class="tab-pane fade" id="preferences" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-3" style="color: #0d2b4b; font-weight: 600;">
                                    <i class="fas fa-bell me-2"></i>Notification Preferences
                                </h6>
                                
                                <div class="preference-item mb-3 p-3 border rounded">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">Email Notifications</h6>
                                            <p class="text-muted small mb-0">Receive email updates about events</p>
                                        </div>
                                        <div>
                                            @if($user->email_notifications)
                                                <span class="badge bg-success">Enabled</span>
                                            @else
                                                <span class="badge bg-secondary">Disabled</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="preference-item mb-3 p-3 border rounded">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">Event Reminders</h6>
                                            <p class="text-muted small mb-0">Get reminders before events start</p>
                                        </div>
                                        <div>
                                            @if($user->event_reminders)
                                                <span class="badge bg-success">Enabled</span>
                                            @else
                                                <span class="badge bg-secondary">Disabled</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="preference-item p-3 border rounded">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">Newsletter</h6>
                                            <p class="text-muted small mb-0">Subscribe to monthly newsletter</p>
                                        </div>
                                        <div>
                                            @if($user->newsletter_subscription)
                                                <span class="badge bg-success">Subscribed</span>
                                            @else
                                                <span class="badge bg-secondary">Not Subscribed</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h6 class="mb-3" style="color: #0d2b4b; font-weight: 600;">
                                    <i class="fas fa-palette me-2"></i>Display Preferences
                                </h6>
                                
                                <div class="preference-item mb-3 p-3 border rounded">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">Theme Preference</h6>
                                            <p class="text-muted small mb-0">Choose your preferred theme</p>
                                        </div>
                                        <div>
                                            <span class="badge badge-ju">
                                                {{ ucfirst($user->theme_preference ?? 'system') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="preference-item p-3 border rounded">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">Language</h6>
                                            <p class="text-muted small mb-0">Select your preferred language</p>
                                        </div>
                                        <div>
                                            <span class="badge badge-ju">
                                                {{ $user->language == 'en' ? 'English' : 'Amharic' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-4">
                                    <a href="{{ route('profile.edit') }}#preferences" class="btn btn-ju-outline w-100">
                                        <i class="fas fa-edit me-2"></i>Edit Preferences
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="ju-card text-center h-100 hover-lift">
                    <div class="ju-card-body">
                        <div class="quick-action-icon mb-3">
                            <i class="fas fa-key fa-2x" style="color: #0d2b4b;"></i>
                        </div>
                        <h6>Change Password</h6>
                        <p class="small text-muted mb-3">Update your password regularly</p>
                        <a href="{{ route('profile.edit') }}#account-security" class="btn btn-sm btn-ju-outline">
                            <i class="fas fa-edit me-1"></i>Change
                        </a>
                    </div>
                </div>
            </div>
            
           {{-- <div class="col-md-4">
                <div class="ju-card text-center h-100 hover-lift">
                    <div class="ju-card-body">
                        <div class="quick-action-icon mb-3">
                            <i class="fas fa-download fa-2x" style="color: #0d2b4b;"></i>
                        </div>
                        <h6>Export Data</h6>
                        <p class="small text-muted mb-3">Download your personal information</p>
                        <form action="{{ route('profile.export') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-ju-outline">
                                <i class="fas fa-file-export me-1"></i>Export
                            </button>
                        </form>
                    </div>
                </div>
            </div>--}}
            
            <div class="col-md-4">
                <div class="ju-card text-center h-100 hover-lift">
                    <div class="ju-card-body">
                        <div class="quick-action-icon mb-3">
                            <i class="fas fa-shield-alt fa-2x" style="color: #0d2b4b;"></i>
                        </div>
                        <h6>Deactivate Account</h6>
                        <p class="small text-muted mb-3">Disable login but keep your records safely stored</p>
                        <a href="{{ route('profile.edit') }}#account-security" class="btn btn-sm btn-danger">
                            <i class="fas fa-user-slash me-1"></i>Deactivate
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #0a1929 0%, #0d2b4b 100%);">
                <h5 class="modal-title text-white"><i class="fas fa-key me-2"></i>Change Password</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('profile.password') }}" method="POST" id="changePasswordForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Current Password -->
                    <div class="mb-3">
                        <label class="form-label">Current Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock" style="color: #0d2b4b;"></i></span>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                   name="current_password" id="current_password" required>
                        </div>
                        @error('current_password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- New Password -->
                    <div class="mb-3">
                        <label class="form-label">New Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-key" style="color: #0d2b4b;"></i></span>
                            <input type="password" class="form-control @error('new_password') is-invalid @enderror" 
                                   name="new_password" id="new_password" required>
                        </div>
                        <div class="password-strength-meter mt-2">
                            <div class="progress" style="height: 5px;">
                                <div class="progress-bar" id="passwordStrengthBar" style="width: 0%;"></div>
                            </div>
                            <small class="text-muted" id="passwordStrengthText">
                                Minimum 8 characters with mixed case, numbers & symbols
                            </small>
                        </div>
                        @error('new_password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Confirm New Password -->
                    <div class="mb-3">
                        <label class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-check-circle" style="color: #0d2b4b;"></i></span>
                            <input type="password" class="form-control" name="new_password_confirmation" id="new_password_confirmation" required>
                        </div>
                        <div class="invalid-feedback" id="passwordMatchFeedback" style="display: none;">
                            Passwords do not match
                        </div>
                    </div>
                    
                    <!-- Password Requirements -->
                    <div class="alert alert-info alert-ju mt-3">
                        <i class="fas fa-info-circle me-2" style="color: #0d2b4b;"></i>
                        <strong>Password Requirements:</strong>
                        <ul class="mb-0 mt-2 small">
                            <li id="req-length" class="text-muted">✓ At least 8 characters</li>
                            <li id="req-uppercase" class="text-muted">✓ At least one uppercase letter</li>
                            <li id="req-lowercase" class="text-muted">✓ At least one lowercase letter</li>
                            <li id="req-number" class="text-muted">✓ At least one number</li>
                            <li id="req-symbol" class="text-muted">✓ At least one special character</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-ju-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-ju" id="submitPasswordBtn">
                        <i class="fas fa-save me-2"></i>Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Deactivate Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #dc3545 0%, #b02a37 100%);">
                <h5 class="modal-title text-white"><i class="fas fa-exclamation-triangle me-2"></i>Deactivate Account</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Warning:</strong> You will immediately lose access until an admin reactivates you.
                </div>
                
                <p>Deactivating your account will:</p>
                <ul class="text-muted small">
                    <li>Block you from logging in</li>
                    <li>Keep your feedback, event requests, registrations, and history</li>
                    <li>Preserve your data for reports and audit trail</li>
                    <li>Allow later reactivation by administrator or super administrator</li>
                </ul>
                
                <form action="{{ route('profile.destroy') }}" method="POST" id="deleteAccountForm">
                    @csrf
                    @method('DELETE')
                    <div class="mb-3">
                        <label class="form-label">Please enter your password to confirm:</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock" style="color: #0d2b4b;"></i></span>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   name="password" id="confirmDeletePassword" required>
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="confirmDeleteCheck" required>
                        <label class="form-check-label" for="confirmDeleteCheck">
                            I understand my account will be deactivated and login will be blocked
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ju-outline" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn" disabled>
                    <i class="fas fa-user-slash me-2"></i>Deactivate Account
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .profile-image-container {
        position: relative;
        width: 150px;
        height: 150px;
        margin: 0 auto;
    }
    
    .profile-image {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #0d2b4b;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .profile-initial {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: linear-gradient(135deg, #0a1929 0%, #0d2b4b 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
        font-weight: bold;
        border: 4px solid #0d2b4b;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .profile-image-edit {
        position: absolute;
        bottom: 5px;
        right: 5px;
        width: 40px;
        height: 40px;
        background: #0d2b4b;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        border: 3px solid white;
        box-shadow: 0 3px 10px rgba(0,0,0,0.2);
    }
    
    .profile-image-edit:hover {
        background: #1a3a5f;
        transform: scale(1.15) rotate(10deg);
        color: white;
    }
    
    .profile-stats {
        padding: 0.5rem 0;
    }
    
    .stat-item {
        text-align: center;
    }
    
    .stat-item h5 {
        margin-bottom: 0;
        font-size: 1.5rem;
    }
    
    .contact-icon {
        width: 36px;
        height: 36px;
        background: #f0f0f0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .preference-item {
        transition: all 0.3s ease;
    }
    
    .preference-item:hover {
        background-color: #f8f9fa;
        transform: translateX(5px);
    }
    
    .quick-action-icon {
        transition: all 0.3s ease;
    }
    
    .ju-card:hover .quick-action-icon {
        transform: scale(1.2);
    }
    
    .nav-tabs .nav-link {
        color: rgba(255,255,255,0.8);
        border: none;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
    }
    
    .nav-tabs .nav-link:hover {
        color: white;
        border-bottom: 2px solid white;
    }
    
    .nav-tabs .nav-link.active {
        color: white;
        border-bottom: 2px solid white;
        background: transparent;
    }
    
    .badge-ju {
        background: linear-gradient(135deg, #0a1929 0%, #0d2b4b 100%);
        color: white;
    }
    
    .btn-ju {
        background: linear-gradient(135deg, #0a1929 0%, #0d2b4b 100%);
        color: white;
        border: none;
        transition: all 0.3s ease;
    }
    
    .btn-ju:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(13,43,75,0.3);
    }
    
    .btn-ju-outline {
        border: 2px solid #0d2b4b;
        color: #0d2b4b;
        background: transparent;
        transition: all 0.3s ease;
    }
    
    .btn-ju-outline:hover {
        background: #0d2b4b;
        color: white;
        transform: translateY(-2px);
    }
    
    .alert-ju {
        border-left: 4px solid #0d2b4b;
        background-color: #f8f9fa;
    }
    
    /* Password strength meter */
    .password-strength-meter .progress {
        background-color: #e9ecef;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .password-strength-meter .progress-bar {
        transition: width 0.3s ease;
    }
    
    .password-strength-meter .progress-bar.weak {
        background-color: #dc3545;
    }
    
    .password-strength-meter .progress-bar.fair {
        background-color: #ffc107;
    }
    
    .password-strength-meter .progress-bar.good {
        background-color: #17a2b8;
    }
    
    .password-strength-meter .progress-bar.strong {
        background-color: #28a745;
    }
    
    /* Requirement list */
    #req-length.valid,
    #req-uppercase.valid,
    #req-lowercase.valid,
    #req-number.valid,
    #req-symbol.valid {
        color: #28a745 !important;
    }
    
    #req-length.valid::before,
    #req-uppercase.valid::before,
    #req-lowercase.valid::before,
    #req-number.valid::before,
    #req-symbol.valid::before {
        content: "✓ ";
        font-weight: bold;
    }
    
    #req-length.invalid::before,
    #req-uppercase.invalid::before,
    #req-lowercase.invalid::before,
    #req-number.invalid::before,
    #req-symbol.invalid::before {
        content: "✗ ";
        font-weight: bold;
    }
    
    @media (max-width: 768px) {
        .profile-image-container {
            width: 120px;
            height: 120px;
        }
        
        .profile-initial {
            font-size: 3rem;
        }
        
        .nav-tabs .nav-link {
            padding: 0.5rem 0.75rem;
            font-size: 0.85rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // ============================================
    // CHANGE PASSWORD FUNCTIONALITY
    // ============================================
    const currentPassword = document.getElementById('current_password');
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('new_password_confirmation');
    const submitBtn = document.getElementById('submitPasswordBtn');
    const passwordForm = document.getElementById('changePasswordForm');
    
    // Password strength elements
    const strengthBar = document.getElementById('passwordStrengthBar');
    const strengthText = document.getElementById('passwordStrengthText');
    
    // Requirement elements
    const reqLength = document.getElementById('req-length');
    const reqUppercase = document.getElementById('req-uppercase');
    const reqLowercase = document.getElementById('req-lowercase');
    const reqNumber = document.getElementById('req-number');
    const reqSymbol = document.getElementById('req-symbol');
    const passwordMatchFeedback = document.getElementById('passwordMatchFeedback');
    
    if (newPassword) {
        // Check password strength on input
        newPassword.addEventListener('input', function() {
            checkPasswordStrength(this.value);
            checkPasswordMatch();
            updateRequirements(this.value);
        });
    }
    
    if (confirmPassword) {
        confirmPassword.addEventListener('input', checkPasswordMatch);
    }
    
    function checkPasswordStrength(password) {
        let strength = 0;
        let barWidth = 0;
        let barClass = '';
        let message = '';
        
        // Check length
        if (password.length >= 8) strength++;
        if (password.length >= 12) strength++;
        
        // Check for uppercase
        if (/[A-Z]/.test(password)) strength++;
        
        // Check for lowercase
        if (/[a-z]/.test(password)) strength++;
        
        // Check for numbers
        if (/[0-9]/.test(password)) strength++;
        
        // Check for special characters
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        
        // Calculate bar width (max 100%)
        barWidth = Math.min(strength * 16.67, 100);
        
        // Determine class and message
        if (strength <= 2) {
            barClass = 'weak';
            message = 'Weak password';
        } else if (strength <= 4) {
            barClass = 'fair';
            message = 'Fair password';
        } else if (strength <= 5) {
            barClass = 'good';
            message = 'Good password';
        } else {
            barClass = 'strong';
            message = 'Strong password';
        }
        
        // Update UI
        strengthBar.style.width = barWidth + '%';
        strengthBar.className = 'progress-bar ' + barClass;
        strengthText.textContent = message + ' - ' + getPasswordFeedback(strength);
    }
    
    function getPasswordFeedback(strength) {
        if (strength <= 2) {
            return 'Try adding uppercase, numbers, and symbols';
        } else if (strength <= 4) {
            return 'Getting better, but could be stronger';
        } else {
            return 'Excellent password!';
        }
    }
    
    function updateRequirements(password) {
        // Length check
        if (password.length >= 8) {
            reqLength.classList.add('valid');
            reqLength.classList.remove('text-muted', 'invalid');
        } else {
            reqLength.classList.remove('valid');
            reqLength.classList.add('text-muted');
        }
        
        // Uppercase check
        if (/[A-Z]/.test(password)) {
            reqUppercase.classList.add('valid');
            reqUppercase.classList.remove('text-muted', 'invalid');
        } else {
            reqUppercase.classList.remove('valid');
            reqUppercase.classList.add('text-muted');
        }
        
        // Lowercase check
        if (/[a-z]/.test(password)) {
            reqLowercase.classList.add('valid');
            reqLowercase.classList.remove('text-muted', 'invalid');
        } else {
            reqLowercase.classList.remove('valid');
            reqLowercase.classList.add('text-muted');
        }
        
        // Number check
        if (/[0-9]/.test(password)) {
            reqNumber.classList.add('valid');
            reqNumber.classList.remove('text-muted', 'invalid');
        } else {
            reqNumber.classList.remove('valid');
            reqNumber.classList.add('text-muted');
        }
        
        // Symbol check
        if (/[^A-Za-z0-9]/.test(password)) {
            reqSymbol.classList.add('valid');
            reqSymbol.classList.remove('text-muted', 'invalid');
        } else {
            reqSymbol.classList.remove('valid');
            reqSymbol.classList.add('text-muted');
        }
    }
    
    function checkPasswordMatch() {
        if (!newPassword || !confirmPassword) return;
        
        if (confirmPassword.value.length > 0) {
            if (newPassword.value === confirmPassword.value) {
                confirmPassword.classList.remove('is-invalid');
                passwordMatchFeedback.style.display = 'none';
                return true;
            } else {
                confirmPassword.classList.add('is-invalid');
                passwordMatchFeedback.style.display = 'block';
                return false;
            }
        } else {
            confirmPassword.classList.remove('is-invalid');
            passwordMatchFeedback.style.display = 'none';
            return false;
        }
    }
    
    // Form validation before submit
    if (passwordForm) {
        passwordForm.addEventListener('submit', function(e) {
            const passwordsMatch = checkPasswordMatch();
            
            if (!passwordsMatch) {
                e.preventDefault();
                alert('Please make sure your passwords match.');
                return;
            }
            
            // Check password strength
            if (newPassword.value.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters long.');
                return;
            }
        });
    }
    
    // ============================================
    // DELETE ACCOUNT FUNCTIONALITY
    // ============================================
    const deleteCheck = document.getElementById('confirmDeleteCheck');
    const deleteBtn = document.getElementById('confirmDeleteBtn');
    const deletePassword = document.getElementById('confirmDeletePassword');
    const deleteForm = document.getElementById('deleteAccountForm');
    
    if (deleteCheck && deleteBtn) {
        deleteCheck.addEventListener('change', function() {
            deleteBtn.disabled = !this.checked;
        });
    }
    
    if (deleteBtn && deleteForm) {
        deleteBtn.addEventListener('click', function() {
            if (deletePassword && deletePassword.value.length >= 8) {
                if (confirm('Deactivate your account now? You can only access it again after admin reactivation.')) {
                    deleteForm.submit();
                }
            } else {
                alert('Please enter your password to confirm deactivation.');
                deletePassword.focus();
            }
        });
    }
    
    // ============================================
    // AUTO-HIDE TOASTS
    // ============================================
    setTimeout(function() {
        document.querySelectorAll('.ju-toast').forEach(toast => {
            toast.style.transition = 'opacity 0.5s ease';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 500);
        });
    }, 5000);
});
</script>
@endpush
