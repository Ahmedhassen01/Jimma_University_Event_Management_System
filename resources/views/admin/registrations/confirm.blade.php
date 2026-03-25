{{-- resources/views/admin/registrations/confirm.blade.php --}}
@extends('layouts.app')

@section('title', 'Confirm Registration | Jimma University')
@section('page-title', 'Confirm Registration')
@section('page-subtitle', 'Review and confirm participant registration')

@section('breadcrumb-items')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.registrations.index') }}">Registrations</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.registrations.show', $registration->id) }}">#{{ $registration->registration_number }}</a></li>
<li class="breadcrumb-item active">Confirm</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold" style="color: #002789;">
                    <i class="fas fa-check-circle me-2"></i>Confirm Registration
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.registrations.confirm', $registration->id) }}">
                    @csrf
                    
                    <!-- Registration Summary -->
                    <div class="alert alert-info">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle fa-2x"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="fw-bold mb-2">Registration Summary</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <small class="text-muted d-block">Registrant:</small>
                                        <strong>{{ $registration->user->name }}</strong> ({{ $registration->user->email }})
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted d-block">Event:</small>
                                        <strong>{{ $registration->event->title }}</strong>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <small class="text-muted d-block">Date:</small>
                                        <strong>{{ $registration->event->start_date->format('l, F j, Y g:i A') }}</strong>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <small class="text-muted d-block">Guests:</small>
                                        <strong>{{ $registration->guest_count }}</strong> person(s)
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Event Capacity Warning -->
                    @if($registration->event->is_full)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Warning:</strong> This event is at full capacity ({{ $registration->event->max_attendees }} seats). 
                        Confirming this registration will exceed the limit.
                    </div>
                    @endif

                    <!-- Guest Count Adjustment -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Guest Count Adjustment</label>
                        <input type="number" name="guest_count_adjustment" class="form-control @error('guest_count_adjustment') is-invalid @enderror" 
                               value="{{ old('guest_count_adjustment', $registration->guest_count) }}" min="1" max="10">
                        <div class="form-text">You can adjust the number of guests if needed.</div>
                        @error('guest_count_adjustment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Admin Notes</label>
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
                        <div class="form-text">Add any notes about this confirmation (will be saved in registration history).</div>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Send Email -->
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="send_email" id="sendEmail" value="1" checked>
                            <label class="form-check-label fw-bold" for="sendEmail">
                                <i class="fas fa-envelope me-1"></i> Send Confirmation Email to User
                            </label>
                        </div>
                        <div class="form-text ms-4">The user will receive a confirmation email with registration details.</div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.registrations.show', $registration->id) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check-circle me-1"></i> Confirm Registration
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Event Details Card -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold" style="color: #002789;">
                    <i class="fas fa-calendar-alt me-2"></i>Event Details
                </h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-calendar me-2" style="color: #002789;"></i>
                        {{ $registration->event->start_date->format('M d, Y') }}
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-clock me-2" style="color: #002789;"></i>
                        {{ $registration->event->start_date->format('h:i A') }} - {{ $registration->event->end_date->format('h:i A') }}
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-map-marker-alt me-2" style="color: #002789;"></i>
                        {{ $registration->event->venue_name }}, {{ $registration->event->campus_name }}
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-users me-2" style="color: #002789;"></i>
                        Capacity: {{ $registration->event->max_attendees ?? 'Unlimited' }}
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-user-check me-2" style="color: #002789;"></i>
                        Registered: {{ $registration->event->registered_attendees ?? 0 }}
                    </li>
                </ul>
            </div>
        </div>

        <!-- User Details Card -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold" style="color: #002789;">
                    <i class="fas fa-user me-2"></i>Registrant Details
                </h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="avatar-lg bg-primary text-white rounded-circle mx-auto d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 32px;">
                        {{ strtoupper(substr($registration->user->name, 0, 1)) }}
                    </div>
                    <h6 class="mt-2 mb-0">{{ $registration->user->name }}</h6>
                    <small class="text-muted">{{ $registration->user->email }}</small>
                </div>
                <hr>
                <div class="small">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">User ID:</span>
                        <span class="fw-bold">{{ $registration->user->id }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Registered:</span>
                        <span class="fw-bold">{{ $registration->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Status:</span>
                        <span class="badge bg-warning">{{ ucfirst($registration->status) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection