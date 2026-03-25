@extends('layouts.app')

@section('title', 'Edit Profile - JU Event Management')
@section('page-title', 'Edit Profile')
@section('page-subtitle', 'Update your personal information and settings')

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('profile.show') }}">Profile</a></li>
    <li class="breadcrumb-item active">Edit Profile</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-4">
        <!-- Profile Picture Card -->
        <div class="ju-card mb-4">
            <div class="ju-card-header" style="background: linear-gradient(135deg, #0a1929 0%, #0d2b4b 100%);">
                <h5 class="ju-card-title mb-0 text-white"><i class="fas fa-camera me-2"></i>Profile Picture</h5>
            </div>
            <div class="ju-card-body text-center">
                <div class="profile-picture-container mb-4">
                    @if($user->profile_photo_url)
                        <img src="{{ $user->profile_photo_url }}" 
                             alt="{{ $user->name }}" 
                             class="profile-picture" 
                             id="profilePreview">
                    @else
                        <div class="profile-picture-initial" id="profilePreview">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    
                    <div class="profile-picture-overlay" onclick="document.getElementById('profile_photo').click();">
                        <i class="fas fa-camera"></i>
                        <span>Change Photo</span>
                    </div>
                </div>
                
                <form action="{{ route('profile.photo.update') }}" method="POST" enctype="multipart/form-data" id="photoUploadForm">
                    @csrf
                    @method('PUT')
                    <input type="file" name="profile_photo" id="profile_photo" class="d-none" accept="image/*">
                </form>
                
                <div class="photo-upload-info">
                    <p class="small text-muted mb-2">
                        <i class="fas fa-info-circle me-1"></i>
                        Allowed: JPG, PNG, GIF (Max 2MB)
                    </p>
                    
                    @if($user->profile_photo)
                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deletePhotoModal">
                        <i class="fas fa-trash me-1"></i>Remove Photo
                    </button>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Account Status Card -->
        <div class="ju-card" id="account-security">
            <div class="ju-card-header" style="background: linear-gradient(135deg, #0a1929 0%, #0d2b4b 100%);">
                <h5 class="ju-card-title mb-0 text-white"><i class="fas fa-shield-alt me-2"></i>Account Status</h5>
            </div>
            <div class="ju-card-body">
                <div class="status-item d-flex justify-content-between align-items-center mb-3">
                    <span><i class="fas fa-circle text-success me-2" style="font-size: 0.5rem;"></i>Account Status</span>
                    <span class="badge bg-{{ $user->status_badge_class }}">{{ ucfirst($user->status) }}</span>
                </div>
                
                <div class="status-item d-flex justify-content-between align-items-center mb-3">
                    <span><i class="fas fa-envelope me-2 text-muted"></i>Email Verification</span>
                    @if($user->email_verified_at)
                        <span class="badge bg-success">Verified</span>
                    @else
                        <span class="badge bg-warning text-dark">Unverified</span>
                    @endif
                </div>
                
                <div class="status-item d-flex justify-content-between align-items-center mb-3">
                    <span><i class="fas fa-calendar me-2 text-muted"></i>Member Since</span>
                    <span>{{ $user->created_at->format('M d, Y') }}</span>
                </div>
                
                <div class="status-item d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-clock me-2 text-muted"></i>Last Updated</span>
                    <span>{{ $user->updated_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <!-- Profile Edit Form -->
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
                        <button class="nav-link" id="preferences-tab" data-bs-toggle="tab" 
                                data-bs-target="#preferences" type="button" role="tab">
                            <i class="fas fa-sliders-h me-2"></i>Preferences
                        </button>
                    </li>
                </ul>
            </div>
            
            <div class="ju-card-body">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="fas fa-exclamation-circle me-2"></i>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('profile.update') }}" id="profileForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="tab-content" id="profileTabsContent">
                        <!-- Personal Information Tab -->
                        <div class="tab-pane fade show active" id="personal" role="tabpanel">
                            <h6 class="mb-4" style="color: #0d2b4b;">Personal Information</h6>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user" style="color: #0d2b4b;"></i></span>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               name="name" value="{{ old('name', $user->name) }}" required>
                                    </div>
                                    @error('name')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope" style="color: #0d2b4b;"></i></span>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               name="email" value="{{ old('email', $user->email) }}" required>
                                    </div>
                                    @error('email')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-phone" style="color: #0d2b4b;"></i></span>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                               name="phone" value="{{ old('phone', $user->phone) }}" 
                                               placeholder="+251 XXX XXX XXX">
                                    </div>
                                    @error('phone')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Date of Birth</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar" style="color: #0d2b4b;"></i></span>
                                        <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                               name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}">
                                    </div>
                                    @error('date_of_birth')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="form-label">Office Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt" style="color: #0d2b4b;"></i></span>
                                        <input type="text" class="form-control @error('office_address') is-invalid @enderror" 
                                               name="office_address" value="{{ old('office_address', $user->office_address) }}" 
                                               placeholder="Building, Room Number">
                                    </div>
                                    @error('office_address')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Professional Information Tab -->
                        <div class="tab-pane fade" id="professional" role="tabpanel">
                            <h6 class="mb-4" style="color: #0d2b4b;">Professional Information</h6>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Expertise/Role</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user-tag" style="color: #0d2b4b;"></i></span>
                                        <select class="form-select @error('expertise') is-invalid @enderror" name="expertise" id="expertiseSelect">
                                            <option value="">Select your status</option>
                                            <option value="student" {{ old('expertise', $user->expertise) == 'student' ? 'selected' : '' }}>Student</option>
                                            <option value="employee" {{ old('expertise', $user->expertise) == 'employee' ? 'selected' : '' }}>Employee</option>
                                            <option value="faculty" {{ old('expertise', $user->expertise) == 'faculty' ? 'selected' : '' }}>Faculty Member</option>
                                            <option value="staff" {{ old('expertise', $user->expertise) == 'staff' ? 'selected' : '' }}>University Staff</option>
                                            <option value="alumni" {{ old('expertise', $user->expertise) == 'alumni' ? 'selected' : '' }}>Alumni</option>
                                            <option value="guest" {{ old('expertise', $user->expertise) == 'guest' ? 'selected' : '' }}>Guest</option>
                                            <option value="other" {{ old('expertise', $user->expertise) == 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
                                    @error('expertise')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Department/Unit</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-building" style="color: #0d2b4b;"></i></span>
                                        <input type="text" class="form-control @error('department') is-invalid @enderror" 
                                               name="department" value="{{ old('department', $user->department) }}" 
                                               placeholder="e.g., Computer Science">
                                    </div>
                                    @error('department')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Faculty/College</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-university" style="color: #0d2b4b;"></i></span>
                                        <input type="text" class="form-control @error('faculty') is-invalid @enderror" 
                                               name="faculty" value="{{ old('faculty', $user->faculty) }}" 
                                               placeholder="e.g., Faculty of Computing">
                                    </div>
                                    @error('faculty')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Conditional Student Fields -->
                                <div class="col-md-6 mb-3 student-field" style="{{ old('expertise', $user->expertise) == 'student' ? '' : 'display: none;' }}">
                                    <label class="form-label">Student ID <span class="text-danger" id="studentIdRequired" style="{{ old('expertise', $user->expertise) == 'student' ? '' : 'display: none;' }}">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-id-card" style="color: #0d2b4b;"></i></span>
                                        <input type="text" class="form-control @error('student_id') is-invalid @enderror" 
                                               name="student_id" value="{{ old('student_id', $user->student_id) }}" 
                                               placeholder="Enter your student ID">
                                    </div>
                                    @error('student_id')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3 student-field" style="{{ old('expertise', $user->expertise) == 'student' ? '' : 'display: none;' }}">
                                    <label class="form-label">Year of Study <span class="text-danger" id="yearRequired" style="{{ old('expertise', $user->expertise) == 'student' ? '' : 'display: none;' }}">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-graduation-cap" style="color: #0d2b4b;"></i></span>
                                        <select class="form-select @error('year_of_study') is-invalid @enderror" name="year_of_study">
                                            <option value="">Select year</option>
                                            <option value="1" {{ old('year_of_study', $user->year_of_study) == '1' ? 'selected' : '' }}>1st Year</option>
                                            <option value="2" {{ old('year_of_study', $user->year_of_study) == '2' ? 'selected' : '' }}>2nd Year</option>
                                            <option value="3" {{ old('year_of_study', $user->year_of_study) == '3' ? 'selected' : '' }}>3rd Year</option>
                                            <option value="4" {{ old('year_of_study', $user->year_of_study) == '4' ? 'selected' : '' }}>4th Year</option>
                                            <option value="5" {{ old('year_of_study', $user->year_of_study) == '5' ? 'selected' : '' }}>5th Year</option>
                                            <option value="graduate" {{ old('year_of_study', $user->year_of_study) == 'graduate' ? 'selected' : '' }}>Graduate</option>
                                        </select>
                                    </div>
                                    @error('year_of_study')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Conditional Employee Fields -->
                                <div class="col-md-6 mb-3 employee-field" style="{{ in_array(old('expertise', $user->expertise), ['employee', 'faculty', 'staff']) ? '' : 'display: none;' }}">
                                    <label class="form-label">Employee ID <span class="text-danger" id="employeeIdRequired" style="{{ in_array(old('expertise', $user->expertise), ['employee', 'faculty', 'staff']) ? '' : 'display: none;' }}">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-id-badge" style="color: #0d2b4b;"></i></span>
                                        <input type="text" class="form-control @error('employee_id') is-invalid @enderror" 
                                               name="employee_id" value="{{ old('employee_id', $user->employee_id) }}" 
                                               placeholder="Enter your employee ID">
                                    </div>
                                    @error('employee_id')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3 employee-field" style="{{ in_array(old('expertise', $user->expertise), ['employee', 'faculty', 'staff']) ? '' : 'display: none;' }}">
                                    <label class="form-label">Position/Job Title <span class="text-danger" id="positionRequired" style="{{ in_array(old('expertise', $user->expertise), ['employee', 'faculty', 'staff']) ? '' : 'display: none;' }}">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-briefcase" style="color: #0d2b4b;"></i></span>
                                        <input type="text" class="form-control @error('position') is-invalid @enderror" 
                                               name="position" value="{{ old('position', $user->position) }}" 
                                               placeholder="e.g., Lecturer, Administrator">
                                    </div>
                                    @error('position')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Preferences Tab -->
                        <div class="tab-pane fade" id="preferences" role="tabpanel">
                            <h6 class="mb-4" style="color: #0d2b4b;">Notification Preferences</h6>
                            
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <div class="d-flex align-items-center justify-content-between p-3 border rounded">
                                        <div>
                                            <h6 class="mb-1">Email Notifications</h6>
                                            <p class="text-muted small mb-0">Receive email updates about events</p>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="email_notifications" 
                                                   id="emailNotifications" value="1" {{ old('email_notifications', $user->email_notifications) ? 'checked' : '' }}
                                                   style="width: 3rem; height: 1.5rem; cursor: pointer;">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-12 mb-3">
                                    <div class="d-flex align-items-center justify-content-between p-3 border rounded">
                                        <div>
                                            <h6 class="mb-1">Newsletter Subscription</h6>
                                            <p class="text-muted small mb-0">Subscribe to our monthly newsletter</p>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="newsletter_subscription" 
                                                   id="newsletterSubscription" value="1" {{ old('newsletter_subscription', $user->newsletter_subscription) ? 'checked' : '' }}
                                                   style="width: 3rem; height: 1.5rem; cursor: pointer;">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-12 mb-3">
                                    <div class="d-flex align-items-center justify-content-between p-3 border rounded">
                                        <div>
                                            <h6 class="mb-1">Event Reminders</h6>
                                            <p class="text-muted small mb-0">Get reminders before events start</p>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="event_reminders" 
                                                   id="eventReminders" value="1" {{ old('event_reminders', $user->event_reminders) ? 'checked' : '' }}
                                                   style="width: 3rem; height: 1.5rem; cursor: pointer;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <hr class="my-4">
                            
                            <h6 class="mb-4" style="color: #0d2b4b;">Display Preferences</h6>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Theme Preference</label>
                                    <select class="form-select" name="theme_preference">
                                        <option value="light" {{ old('theme_preference', $user->theme_preference) == 'light' ? 'selected' : '' }}>Light Mode</option>
                                        <option value="dark" {{ old('theme_preference', $user->theme_preference) == 'dark' ? 'selected' : '' }}>Dark Mode</option>
                                        <option value="system" {{ old('theme_preference', $user->theme_preference) == 'system' ? 'selected' : '' }}>System Default</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Language</label>
                                    <select class="form-select" name="language">
                                        <option value="en" {{ old('language', $user->language) == 'en' ? 'selected' : '' }}>English</option>
                                        <option value="am" {{ old('language', $user->language) == 'am' ? 'selected' : '' }}>Amharic</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('profile.show') }}" class="btn btn-ju-outline cancel-btn">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-ju" id="saveProfileBtn">
                            <i class="fas fa-save me-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="ju-card">
            <div class="ju-card-header" style="background: linear-gradient(135deg, #0a1929 0%, #0d2b4b 100%);">
                <h5 class="ju-card-title mb-0 text-white">
                    <i class="fas fa-shield-alt me-2"></i>Account Security
                </h5>
            </div>
            <div class="ju-card-body">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="p-3 border rounded h-100">
                            <h6 class="mb-3" style="color: #0d2b4b;">
                                <i class="fas fa-key me-2"></i>Change Password
                            </h6>
                            <p class="text-muted small mb-3">Use a strong password to keep your account secure.</p>

                            <form method="POST" action="{{ route('profile.password') }}">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label class="form-label">Current Password <span class="text-danger">*</span></label>
                                    <input
                                        type="password"
                                        name="current_password"
                                        class="form-control @error('current_password') is-invalid @enderror"
                                        required
                                    >
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">New Password <span class="text-danger">*</span></label>
                                    <input
                                        type="password"
                                        name="new_password"
                                        class="form-control @error('new_password') is-invalid @enderror"
                                        required
                                    >
                                    @error('new_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                                    <input
                                        type="password"
                                        name="new_password_confirmation"
                                        class="form-control @error('new_password_confirmation') is-invalid @enderror"
                                        required
                                    >
                                    @error('new_password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="alert alert-info py-2 px-3 small mb-3">
                                    Minimum 8 characters with uppercase, lowercase, number, and symbol.
                                </div>

                                <button type="submit" class="btn btn-ju">
                                    <i class="fas fa-save me-2"></i>Update Password
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="p-3 border rounded h-100 border-danger-subtle">
                            <h6 class="mb-3 text-danger">
                                <i class="fas fa-user-slash me-2"></i>Deactivate Account
                            </h6>
                            <p class="text-muted small mb-3">
                                This will block login, but keep your profile and related data for records.
                            </p>

                            <form method="POST" action="{{ route('profile.destroy') }}" id="deleteAccountSecurityForm">
                                @csrf
                                @method('DELETE')

                                <div class="mb-3">
                                    <label class="form-label">Password <span class="text-danger">*</span></label>
                                    <input
                                        type="password"
                                        name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        required
                                    >
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="deleteAccountConfirm">
                                    <label class="form-check-label" for="deleteAccountConfirm">
                                        I understand this account will be deactivated until admin reactivation.
                                    </label>
                                </div>

                                <button type="submit" class="btn btn-danger" id="deleteAccountSecurityBtn" disabled>
                                    <i class="fas fa-user-slash me-2"></i>Deactivate Account
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Photo Confirmation Modal -->
<div class="modal fade" id="deletePhotoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #dc3545 0%, #b02a37 100%);">
                <h5 class="modal-title text-white"><i class="fas fa-exclamation-triangle me-2"></i>Remove Profile Photo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to remove your profile photo?</p>
                <p class="text-muted small">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ju-outline" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('profile.photo.destroy') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Remove Photo
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .profile-picture-container {
        position: relative;
        width: 150px;
        height: 150px;
        margin: 0 auto;
        border-radius: 50%;
        overflow: hidden;
        cursor: pointer;
        border: 4px solid #0d2b4b;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .profile-picture {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .profile-picture-initial {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #0a1929 0%, #0d2b4b 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
        font-weight: bold;
    }
    
    .profile-picture-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(13, 43, 75, 0.8);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: white;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .profile-picture-overlay i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }
    
    .profile-picture-overlay span {
        font-size: 0.9rem;
        font-weight: 500;
    }
    
    .profile-picture-container:hover .profile-picture-overlay {
        opacity: 1;
    }
    
    .status-item {
        padding: 0.5rem 0;
        border-bottom: 1px solid #e9ecef;
    }
    
    .status-item:last-child {
        border-bottom: none;
    }
    
    .form-label {
        font-weight: 500;
        color: #0a1929;
        margin-bottom: 0.5rem;
    }
    
    .input-group-text {
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }
    
    .form-control, .form-select {
        border-color: #dee2e6;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #0d2b4b;
        box-shadow: 0 0 0 0.2rem rgba(13, 43, 75, 0.25);
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
    
    .form-check-input:checked {
        background-color: #0d2b4b;
        border-color: #0d2b4b;
    }
    
    .student-field, .employee-field {
        transition: all 0.3s ease;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .student-field:not([style*="display: none"]),
    .employee-field:not([style*="display: none"]) {
        animation: slideDown 0.3s ease;
    }
    
    .alert-danger ul {
        margin-left: 1rem;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ============================================
    // PROFILE PHOTO UPLOAD
    // ============================================
    const profilePhoto = document.getElementById('profile_photo');
    if (profilePhoto) {
        profilePhoto.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                
                // Validate file size (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size must be less than 2MB');
                    return;
                }
                
                // Validate file type
                if (!file.type.match('image.*')) {
                    alert('Please select an image file');
                    return;
                }
                
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const preview = document.getElementById('profilePreview');
                    if (preview.tagName === 'IMG') {
                        preview.src = e.target.result;
                    } else {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = 'Profile Preview';
                        img.className = 'profile-picture';
                        img.id = 'profilePreview';
                        preview.parentNode.replaceChild(img, preview);
                    }
                };
                
                reader.readAsDataURL(file);
                
                // Auto submit form
                document.getElementById('photoUploadForm').submit();
            }
        });
    }
    
    // ============================================
    // CONDITIONAL FIELDS BASED ON EXPERTISE
    // ============================================
    const expertiseSelect = document.getElementById('expertiseSelect');
    const studentFields = document.querySelectorAll('.student-field');
    const employeeFields = document.querySelectorAll('.employee-field');
    
    // Required field indicators
    const studentIdRequired = document.getElementById('studentIdRequired');
    const yearRequired = document.getElementById('yearRequired');
    const employeeIdRequired = document.getElementById('employeeIdRequired');
    const positionRequired = document.getElementById('positionRequired');
    
    if (expertiseSelect) {
        function updateConditionalFields() {
            const value = expertiseSelect.value;
            
            if (value === 'student') {
                // Show student fields
                studentFields.forEach(field => field.style.display = 'block');
                employeeFields.forEach(field => field.style.display = 'none');
                
                // Show required indicators
                if (studentIdRequired) studentIdRequired.style.display = 'inline';
                if (yearRequired) yearRequired.style.display = 'inline';
                if (employeeIdRequired) employeeIdRequired.style.display = 'none';
                if (positionRequired) positionRequired.style.display = 'none';
                
                // Make fields required
                document.querySelector('input[name="student_id"]').required = true;
                document.querySelector('select[name="year_of_study"]').required = true;
                document.querySelector('input[name="employee_id"]').required = false;
                document.querySelector('input[name="position"]').required = false;
                
            } else if (['employee', 'faculty', 'staff'].includes(value)) {
                // Show employee fields
                studentFields.forEach(field => field.style.display = 'none');
                employeeFields.forEach(field => field.style.display = 'block');
                
                // Show required indicators
                if (studentIdRequired) studentIdRequired.style.display = 'none';
                if (yearRequired) yearRequired.style.display = 'none';
                if (employeeIdRequired) employeeIdRequired.style.display = 'inline';
                if (positionRequired) positionRequired.style.display = 'inline';
                
                // Make fields required
                document.querySelector('input[name="student_id"]').required = false;
                document.querySelector('select[name="year_of_study"]').required = false;
                document.querySelector('input[name="employee_id"]').required = true;
                document.querySelector('input[name="position"]').required = true;
                
            } else {
                // Hide all conditional fields
                studentFields.forEach(field => field.style.display = 'none');
                employeeFields.forEach(field => field.style.display = 'none');
                
                // Hide required indicators
                if (studentIdRequired) studentIdRequired.style.display = 'none';
                if (yearRequired) yearRequired.style.display = 'none';
                if (employeeIdRequired) employeeIdRequired.style.display = 'none';
                if (positionRequired) positionRequired.style.display = 'none';
                
                // Remove required attributes
                document.querySelector('input[name="student_id"]').required = false;
                document.querySelector('select[name="year_of_study"]').required = false;
                document.querySelector('input[name="employee_id"]').required = false;
                document.querySelector('input[name="position"]').required = false;
            }
        }
        
        expertiseSelect.addEventListener('change', updateConditionalFields);
        
        // Run on page load to set initial state
        updateConditionalFields();
    }
    
    // ============================================
    // UNSAVED CHANGES WARNING
    // ============================================
    const profileForm = document.getElementById('profileForm');
    
    if (profileForm) {
        let formChanged = false;
        
        // Store initial form data
        const initialFormData = new FormData(profileForm);
        
        // Function to check if form has changed
        function checkFormChanged() {
            const currentFormData = new FormData(profileForm);
            
            // Special handling for checkboxes
            const checkboxes = profileForm.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                const initialValue = initialFormData.get(checkbox.name) === 'on';
                const currentValue = checkbox.checked;
                if (initialValue !== currentValue) {
                    return true;
                }
            });
            
            // Check other fields
            for (let [key, value] of currentFormData.entries()) {
                if (key.includes('checkbox')) continue;
                if (value !== initialFormData.get(key)) {
                    return true;
                }
            }
            
            return false;
        }
        
        // Mark form as changed when inputs change
        profileForm.querySelectorAll('input, select, textarea').forEach(field => {
            field.addEventListener('change', function() {
                formChanged = checkFormChanged();
            });
            
            field.addEventListener('input', function() {
                formChanged = checkFormChanged();
            });
        });
        
        // Handle beforeunload event
        function handleBeforeUnload(e) {
            if (formChanged) {
                e.preventDefault();
                e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
                return e.returnValue;
            }
        }
        
        // Add event listener
        window.addEventListener('beforeunload', handleBeforeUnload);
        
        // Remove warning when form is submitted
        profileForm.addEventListener('submit', function() {
            formChanged = false;
            window.removeEventListener('beforeunload', handleBeforeUnload);
        });
        
        // Cancel button should not trigger warning
        const cancelBtn = document.querySelector('.cancel-btn');
        if (cancelBtn) {
            cancelBtn.addEventListener('click', function() {
                formChanged = false;
                window.removeEventListener('beforeunload', handleBeforeUnload);
            });
        }
    }
    
    // ============================================
    // FORM VALIDATION
    // ============================================
    const saveBtn = document.getElementById('saveProfileBtn');
    if (saveBtn) {
        saveBtn.addEventListener('click', function(e) {
            const requiredFields = profileForm.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                    
                    // Create error message if not exists
                    let errorDiv = field.nextElementSibling;
                    if (!errorDiv || !errorDiv.classList.contains('invalid-feedback')) {
                        errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback';
                        errorDiv.textContent = 'This field is required.';
                        field.parentNode.appendChild(errorDiv);
                    }
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields.');
                
                // Switch to the tab containing the first invalid field
                const firstInvalid = profileForm.querySelector('.is-invalid');
                if (firstInvalid) {
                    const tabPane = firstInvalid.closest('.tab-pane');
                    if (tabPane) {
                        const tabId = tabPane.id;
                        const tab = document.querySelector(`[data-bs-target="#${tabId}"]`);
                        if (tab) {
                            const tabInstance = new bootstrap.Tab(tab);
                            tabInstance.show();
                        }
                    }
                }
            }
        });
    }
    
    // ============================================
    // AUTO-HIDE TOASTS
    // ============================================
    const deleteAccountConfirm = document.getElementById('deleteAccountConfirm');
    const deleteAccountSecurityBtn = document.getElementById('deleteAccountSecurityBtn');

    if (deleteAccountConfirm && deleteAccountSecurityBtn) {
        deleteAccountConfirm.addEventListener('change', function() {
            deleteAccountSecurityBtn.disabled = !this.checked;
        });
    }

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
