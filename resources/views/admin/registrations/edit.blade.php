{{-- resources/views/admin/registrations/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Registration | Jimma University')
@section('page-title', 'Edit Registration')
@section('page-subtitle', 'Update registration information')

@section('breadcrumb-items')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.registrations.index') }}">Registrations</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.registrations.show', $registration->id) }}">#{{ $registration->registration_number }}</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<style>
    :root {
        --ju-blue: #002789;
        --ju-blue-dark: #001a5c;
        --ju-gold: #C4A747;
        --ju-gray-100: #f8f9fa;
        --ju-gray-200: #e9ecef;
        --ju-gray-600: #6c757d;
        --ju-gray-800: #343a40;
    }

    .form-section {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid var(--ju-gray-200);
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }

    .form-section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--ju-blue);
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--ju-gold);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-section-title i {
        color: var(--ju-gold);
    }

    .info-card {
        background: linear-gradient(145deg, #f8f9fa, #ffffff);
        border-radius: 12px;
        padding: 1.25rem;
        border: 1px solid var(--ju-gray-200);
        margin-bottom: 1rem;
    }

    .info-label {
        font-size: 0.85rem;
        color: var(--ju-gray-600);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }

    .info-value {
        font-size: 1rem;
        font-weight: 600;
        color: var(--ju-gray-800);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 1rem;
        border-radius: 30px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffeeba;
    }

    .status-confirmed {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .status-cancelled {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .status-waitlisted {
        background: #d1ecf1;
        color: #0c5460;
        border: 1px solid #bee5eb;
    }

    .form-control, .form-select {
        border: 2px solid var(--ju-gray-200);
        border-radius: 10px;
        padding: 0.6rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--ju-blue);
        box-shadow: 0 0 0 4px rgba(0,39,137,0.1);
        outline: none;
    }

    .form-control.is-invalid, .form-select.is-invalid {
        border-color: #dc3545;
    }

    .btn-save {
        background: linear-gradient(145deg, #28a745, #1e7e34);
        color: white;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 30px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(40,167,69,0.3);
    }

    .btn-cancel {
        background: white;
        color: var(--ju-gray-600);
        border: 2px solid var(--ju-gray-200);
        padding: 0.75rem 2rem;
        border-radius: 30px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-cancel:hover {
        background: var(--ju-gray-100);
        border-color: var(--ju-gray-400);
    }
</style>

<div class="container-fluid px-lg-4">
    <div class="row">
        <div class="col-lg-8">
            <!-- Main Edit Form -->
            <div class="form-section">
                <h5 class="form-section-title">
                    <i class="fas fa-edit"></i>
                    Edit Registration Details
                </h5>

                <form method="POST" action="{{ route('admin.registrations.update', $registration->id) }}" id="editForm">
                    @csrf
                    @method('PUT')

                    <!-- Registration Number (Read Only) -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Registration Number</label>
                            <input type="text" class="form-control" value="{{ $registration->registration_number }}" readonly disabled>
                            <small class="text-muted">Registration number cannot be changed</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Current Status</label>
                            <div>
                                @php
                                $statusClass = match($registration->status) {
                                    'pending' => 'status-pending',
                                    'confirmed' => 'status-confirmed',
                                    'cancelled' => 'status-cancelled',
                                    'waitlisted' => 'status-waitlisted',
                                    default => 'bg-secondary text-white'
                                };
                                @endphp
                                <span class="status-badge {{ $statusClass }}">
                                    <i class="fas fa-{{ $registration->status === 'confirmed' ? 'check-circle' : ($registration->status === 'pending' ? 'clock' : ($registration->status === 'cancelled' ? 'times-circle' : 'list-ol')) }} me-2"></i>
                                    {{ ucfirst($registration->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Event Selection -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">
                                <i class="fas fa-calendar-alt me-1" style="color: var(--ju-gold);"></i>
                                Event <span class="text-danger">*</span>
                            </label>
                            <select name="event_id" class="form-select @error('event_id') is-invalid @enderror" required>
                                <option value="">Select Event</option>
                                @foreach($events as $event)
                                <option value="{{ $event->id }}" {{ old('event_id', $registration->event_id) == $event->id ? 'selected' : '' }}>
                                    {{ $event->title }} ({{ $event->start_date->format('M d, Y') }})
                                </option>
                                @endforeach
                            </select>
                            @error('event_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- User Selection -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">
                                <i class="fas fa-user me-1" style="color: var(--ju-gold);"></i>
                                User <span class="text-danger">*</span>
                            </label>
                            <select name="user_id" class="form-select select2 @error('user_id') is-invalid @enderror" required>
                                <option value="">Select User</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id', $registration->user_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Guest Count and Status -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                <i class="fas fa-users me-1" style="color: var(--ju-gold);"></i>
                                Guest Count <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   name="guest_count" 
                                   class="form-control @error('guest_count') is-invalid @enderror" 
                                   value="{{ old('guest_count', $registration->guest_count) }}" 
                                   min="1" 
                                   max="10" 
                                   required>
                            @error('guest_count')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Maximum 10 guests allowed</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                <i class="fas fa-tag me-1" style="color: var(--ju-gold);"></i>
                                Status <span class="text-danger">*</span>
                            </label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="pending" {{ old('status', $registration->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ old('status', $registration->status) == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="cancelled" {{ old('status', $registration->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="waitlisted" {{ old('status', $registration->status) == 'waitlisted' ? 'selected' : '' }}>Waitlisted</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Attendance and Check-in -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-check mt-4">
                                <input type="checkbox" 
                                       name="attended" 
                                       class="form-check-input @error('attended') is-invalid @enderror" 
                                       id="attended" 
                                       value="1" 
                                       {{ old('attended', $registration->attended) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="attended">
                                    <i class="fas fa-user-check me-1" style="color: var(--ju-gold);"></i>
                                    Has Attended the Event
                                </label>
                                @error('attended')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                <i class="fas fa-clock me-1" style="color: var(--ju-gold);"></i>
                                Check-in Time
                            </label>
                            <input type="datetime-local" 
                                   name="check_in_time" 
                                   class="form-control @error('check_in_time') is-invalid @enderror" 
                                   value="{{ old('check_in_time', $registration->check_in_time ? $registration->check_in_time->format('Y-m-d\TH:i') : '') }}">
                            @error('check_in_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">
                                <i class="fas fa-info-circle me-1" style="color: var(--ju-gold);"></i>
                                Additional Information
                            </label>
                            <textarea name="additional_info" 
                                      class="form-control @error('additional_info') is-invalid @enderror" 
                                      rows="3" 
                                      placeholder="Dietary restrictions, special needs, etc.">{{ old('additional_info', $registration->additional_info) }}</textarea>
                            @error('additional_info')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Admin Notes -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">
                                <i class="fas fa-sticky-note me-1" style="color: var(--ju-gold);"></i>
                                Admin Notes
                            </label>
                            <textarea name="notes" 
                                      class="form-control @error('notes') is-invalid @enderror" 
                                      rows="3" 
                                      placeholder="Internal notes about this registration">{{ old('notes', $registration->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">These notes are only visible to administrators</small>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('admin.registrations.show', $registration->id) }}" class="btn-cancel">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn-save">
                            <i class="fas fa-save me-2"></i>Update Registration
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Registration Info Card -->
            <div class="info-card">
                <h6 class="fw-bold mb-3" style="color: var(--ju-blue);">
                    <i class="fas fa-info-circle me-2" style="color: var(--ju-gold);"></i>
                    Registration Information
                </h6>
                
                <div class="mb-3">
                    <div class="info-label">Created</div>
                    <div class="info-value">{{ $registration->created_at->format('M d, Y H:i') }}</div>
                </div>

                @if($registration->confirmed_at)
                <div class="mb-3">
                    <div class="info-label">Confirmed</div>
                    <div class="info-value">{{ $registration->confirmed_at->format('M d, Y H:i') }}</div>
                </div>
                @endif

                @if($registration->cancelled_at)
                <div class="mb-3">
                    <div class="info-label">Cancelled</div>
                    <div class="info-value">{{ $registration->cancelled_at->format('M d, Y H:i') }}</div>
                </div>
                @endif

                <hr>

                <div class="mb-2">
                    <div class="info-label">Event Date</div>
                    <div class="info-value">{{ $registration->event->start_date->format('M d, Y h:i A') }}</div>
                </div>

                <div class="mb-2">
                    <div class="info-label">Venue</div>
                    <div class="info-value">{{ $registration->event->venue_name }}</div>
                </div>
            </div>

            <!-- Warning Card -->
            <div class="info-card" style="border-left: 4px solid #ffc107;">
                <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-exclamation-triangle fs-4 me-2" style="color: #ffc107;"></i>
                    <h6 class="fw-bold mb-0" style="color: #856404;">Important Notes</h6>
                </div>
                <ul class="small text-muted mb-0 ps-3">
                    <li class="mb-1">Changing the event or user will affect registration history</li>
                    <li class="mb-1">Status changes may affect attendee counts</li>
                    <li class="mb-1">All changes are logged in registration history</li>
                    <li class="mb-1">Consider notifying the user of significant changes</li>
                </ul>
            </div>

            <!-- Quick Actions -->
            <div class="info-card">
                <h6 class="fw-bold mb-3" style="color: var(--ju-blue);">
                    <i class="fas fa-bolt me-2" style="color: var(--ju-gold);"></i>
                    Quick Actions
                </h6>
                
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.registrations.show', $registration->id) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-eye me-2"></i>View Registration
                    </a>
                    
                    @if($registration->status == 'pending')
                    <a href="{{ route('admin.registrations.confirm-form', $registration->id) }}" class="btn btn-success btn-sm">
                        <i class="fas fa-check me-2"></i>Confirm Registration
                    </a>
                    @endif
                    
                    @if($registration->status != 'cancelled')
                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#cancelModal">
                        <i class="fas fa-times-circle me-2"></i>Cancel Registration
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(145deg, #dc3545, #b02a37); color: white;">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>Cancel Registration
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.registrations.cancel', $registration->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Are you sure you want to cancel this registration for:</p>
                    <h6 class="fw-bold mb-3" style="color: var(--ju-blue);">{{ $registration->event->title }}</h6>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Reason for Cancellation</label>
                        <textarea name="cancellation_reason" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Confirm Cancellation</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2 for better dropdowns
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Select an option',
            allowClear: true
        });

        // Warn before leaving with unsaved changes
        let formChanged = false;
        
        $('#editForm input, #editForm select, #editForm textarea').change(function() {
            formChanged = true;
        });

        $('#editForm').submit(function() {
            formChanged = false;
        });

        window.addEventListener('beforeunload', function(e) {
            if (formChanged) {
                e.preventDefault();
                e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
            }
        });

        // Guest count validation
        $('#guest_count').on('input', function() {
            const val = parseInt($(this).val());
            if (val < 1) $(this).val(1);
            if (val > 10) $(this).val(10);
        });

        // Status change warning
        let originalStatus = '{{ $registration->status }}';
        $('select[name="status"]').change(function() {
            const newStatus = $(this).val();
            if (originalStatus !== newStatus) {
                if (!confirm('Changing the status may affect attendee counts. Continue?')) {
                    $(this).val(originalStatus);
                }
            }
        });

        // Initialize tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>
@endpush

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endpush