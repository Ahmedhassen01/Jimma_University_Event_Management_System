{{-- resources/views/feedback/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Submit Feedback')
@section('page-title', 'Submit Feedback')

@push('styles')
<style>
    .feedback-shell {
        max-width: 920px;
        margin: 0 auto;
    }

    .feedback-card {
        border: 0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 14px 32px rgba(0, 39, 137, 0.12);
    }

    .feedback-head {
        background: linear-gradient(135deg, #002789 0%, #1a3a9a 100%);
        color: #fff;
        padding: 1.4rem 1.5rem;
    }

    .feedback-head h5 {
        margin-bottom: 0.35rem;
    }

    .feedback-head p {
        margin: 0;
        opacity: 0.92;
        font-size: 0.92rem;
    }

    .feedback-body {
        padding: 1.4rem;
        background: #fff;
    }

    .field-card {
        border: 1px solid #e9eef7;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
        background: #fbfdff;
    }

    .field-card h6 {
        font-weight: 700;
        color: #002789;
        margin-bottom: 0.9rem;
    }

    .star-rating {
        direction: rtl;
        display: inline-flex;
        gap: 0.15rem;
    }

    .star-rating input[type=radio] {
        display: none;
    }

    .star-rating label {
        color: #c8ced9;
        font-size: 1.9rem;
        cursor: pointer;
        line-height: 1;
        transition: transform 0.15s ease, color 0.15s ease;
    }

    .star-rating label:hover,
    .star-rating label:hover ~ label,
    .star-rating input[type=radio]:checked ~ label {
        color: #f4b400;
    }

    .star-rating label:hover {
        transform: translateY(-2px);
    }

    .message-meta {
        font-size: 0.82rem;
        color: #6b7280;
        margin-top: 0.45rem;
    }

    .switch-group .form-check {
        padding: 0.85rem 1rem 0.85rem 2.8rem;
        border: 1px solid #e9eef7;
        border-radius: 10px;
        background: #fff;
        margin-bottom: 0.7rem;
    }

    .switch-group .form-check-input {
        margin-top: 0.35rem;
    }

    .form-footer {
        border-top: 1px solid #e9eef7;
        margin-top: 1.25rem;
        padding-top: 1rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="feedback-shell">
        <div class="card feedback-card">
            <div class="feedback-head">
                <h5><i class="fas fa-comment-dots me-2"></i>Feedback Form</h5>
                <p>Share your experience so we can improve events and system quality.</p>
            </div>

            <div class="feedback-body">
                @if($errors->any())
                <div class="alert alert-danger">
                    <div class="fw-semibold mb-1">Please fix the following:</div>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('feedback.store') }}" method="POST" id="feedbackForm" novalidate>
                    @csrf

                    @if($event)
                    <div class="alert alert-info">
                        <i class="fas fa-calendar-alt me-2"></i>
                        You are providing feedback for: <strong>{{ $event->title }}</strong>
                    </div>
                    <input type="hidden" name="event_id" value="{{ $event->id }}">
                    @endif

                    <div class="field-card">
                        <h6>1. Feedback Details</h6>
                        <div class="row g-3">
                            <div class="col-md-7">
                                <label for="type" class="form-label">Feedback Type *</label>
                                <select id="type" name="type" class="form-select @error('type') is-invalid @enderror" required>
                                    <option value="">Select type</option>
                                    <option value="event" {{ old('type') === 'event' ? 'selected' : '' }}>Event Feedback</option>
                                    <option value="system" {{ old('type') === 'system' ? 'selected' : '' }}>System Feedback</option>
                                    <option value="general" {{ old('type') === 'general' ? 'selected' : '' }}>General Feedback</option>
                                    <option value="suggestion" {{ old('type') === 'suggestion' ? 'selected' : '' }}>Suggestion</option>
                                    <option value="complaint" {{ old('type') === 'complaint' ? 'selected' : '' }}>Complaint</option>
                                </select>
                                @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-5">
                                <label class="form-label d-block mb-2">Rating (Optional)</label>
                                <div class="star-rating">
                                    @for($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" {{ (string) old('rating') === (string) $i ? 'checked' : '' }}>
                                    <label for="star{{ $i }}" aria-label="{{ $i }} star">&#9733;</label>
                                    @endfor
                                </div>
                                @error('rating')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-3">
                            <label for="subject" class="form-label">Subject (Optional)</label>
                            <input type="text" id="subject" name="subject"
                                class="form-control @error('subject') is-invalid @enderror"
                                value="{{ old('subject') }}"
                                placeholder="Short summary of your feedback">
                            @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="field-card">
                        <h6>2. Message</h6>
                        <label for="message" class="form-label">Your Feedback *</label>
                        <textarea id="message" name="message"
                            class="form-control @error('message') is-invalid @enderror"
                            rows="7" maxlength="2000"
                            placeholder="Please provide detailed and constructive feedback..."
                            required>{{ old('message') }}</textarea>
                        @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="message-meta d-flex justify-content-between">
                            <span>Minimum 10 characters, maximum 2000.</span>
                            <span id="messageCounter">0 / 2000</span>
                        </div>
                    </div>

                    @guest
                    <div class="field-card">
                        <h6>3. Contact (Optional)</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Your Name</label>
                                <input type="text" id="name" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}"
                                    placeholder="Enter your name">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Your Email</label>
                                <input type="email" id="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}"
                                    placeholder="Enter your email">
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    @endguest

                    <div class="field-card">
                        <h6>4. Preferences</h6>
                        <div class="switch-group">
                            <input type="hidden" name="allow_contact" value="0">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="allowContact" name="allow_contact" value="1" {{ old('allow_contact') ? 'checked' : '' }}>
                                <label class="form-check-label" for="allowContact">
                                    Allow us to contact you regarding this feedback
                                </label>
                            </div>

                            <input type="hidden" name="is_public" value="0">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="isPublic" name="is_public" value="1" {{ old('is_public') ? 'checked' : '' }}>
                                <label class="form-check-label" for="isPublic">
                                    Share this feedback publicly (anonymized for testimonials)
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-footer d-flex flex-wrap gap-2 justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-2"></i>Submit Feedback
                        </button>
                        <button type="reset" class="btn btn-outline-secondary" id="resetFeedbackForm">
                            <i class="fas fa-rotate-left me-2"></i>Reset
                        </button>
                        <a href="{{ route('feedback.testimonials') }}" class="btn btn-outline-primary">
                            <i class="fas fa-star me-2"></i>View Testimonials
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const messageInput = document.getElementById('message');
    const messageCounter = document.getElementById('messageCounter');
    const resetBtn = document.getElementById('resetFeedbackForm');

    if (!messageInput || !messageCounter) {
        return;
    }

    const updateCounter = () => {
        messageCounter.textContent = `${messageInput.value.length} / 2000`;
    };

    updateCounter();
    messageInput.addEventListener('input', updateCounter);

    if (resetBtn) {
        resetBtn.addEventListener('click', function () {
            setTimeout(updateCounter, 0);
        });
    }
});
</script>
@endpush
