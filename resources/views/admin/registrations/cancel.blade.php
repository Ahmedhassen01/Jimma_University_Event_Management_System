{{-- resources/views/admin/registrations/cancel.blade.php --}}
@extends('layouts.app')

@section('title', 'Cancel Registration | Jimma University')
@section('page-title', 'Cancel Registration')
@section('page-subtitle', 'Cancel participant registration')

@section('breadcrumb-items')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.registrations.index') }}">Registrations</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.registrations.show', $registration->id) }}">#{{ $registration->registration_number }}</a></li>
<li class="breadcrumb-item active">Cancel</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-danger text-white py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-exclamation-triangle me-2"></i>Cancel Registration
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning mb-4">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Warning:</strong> This action cannot be undone. The user will be notified of this cancellation.
                </div>

                <div class="mb-4 p-3 bg-light rounded">
                    <h6 class="fw-bold mb-3" style="color: #002789;">Registration Details</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Event:</strong> {{ $registration->event->title }}</p>
                            <p class="mb-1"><strong>Registrant:</strong> {{ $registration->user->name }}</p>
                            <p class="mb-1"><strong>Email:</strong> {{ $registration->user->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Registration #:</strong> {{ $registration->registration_number }}</p>
                            <p class="mb-1"><strong>Guests:</strong> {{ $registration->guest_count }}</p>
                            <p class="mb-1"><strong>Status:</strong> 
                                <span class="badge bg-{{ $registration->status == 'pending' ? 'warning' : ($registration->status == 'confirmed' ? 'success' : 'secondary') }}">
                                    {{ ucfirst($registration->status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.registrations.cancel', $registration->id) }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="cancellation_reason" class="form-label fw-bold">Reason for Cancellation <span class="text-danger">*</span></label>
                        <textarea name="cancellation_reason" id="cancellation_reason" class="form-control @error('cancellation_reason') is-invalid @enderror" rows="4" required placeholder="Please provide a detailed reason for cancelling this registration...">{{ old('cancellation_reason') }}</textarea>
                        @error('cancellation_reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">This reason will be visible to the user and saved in registration history.</div>
                    </div>

                    <div class="mb-4 form-check">
                        <input type="checkbox" class="form-check-input" id="confirm_cancel" required>
                        <label class="form-check-label fw-bold" for="confirm_cancel">
                            I understand that this action cannot be undone and the user will be notified.
                        </label>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.registrations.show', $registration->id) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Go Back
                        </a>
                        <button type="submit" class="btn btn-danger" id="submitBtn">
                            <i class="fas fa-times-circle me-1"></i> Confirm Cancellation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('confirm_cancel').addEventListener('change', function() {
        document.getElementById('submitBtn').disabled = !this.checked;
    });
</script>
@endpush