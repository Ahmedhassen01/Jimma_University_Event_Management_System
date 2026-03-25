@extends('layouts.app')

@section('title', 'Share Event - ' . $event->title)
@section('page-title', 'Share Event')
@section('page-subtitle', $event->title)

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('events.guest.browse') }}">Events</a></li>
    <li class="breadcrumb-item"><a href="{{ route('events.guest.show', $event->slug) }}">{{ Str::limit($event->title, 30) }}</a></li>
    <li class="breadcrumb-item active">Share</li>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="ju-card animate__animated animate__fadeInUp">
                <div class="card-body p-5">
                    <!-- Header -->
                    <div class="text-center mb-5">
                        <div class="icon-circle bg-primary-ju text-white mx-auto mb-4" style="width: 90px; height: 90px; font-size: 2.2rem;">
                            <i class="fas fa-share-alt"></i>
                        </div>
                        <h2 class="fw-bold mb-2" style="color: var(--ju-blue);">Share This Event</h2>
                        <p class="text-muted">Help spread the word about this exciting event at Jimma University</p>
                    </div>
                    
                    <!-- Event Info Card -->
                    <div class="event-info-card bg-light p-4 rounded-4 mb-5">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5 class="fw-bold mb-2" style="color: var(--ju-blue);">{{ $event->title }}</h5>
                                <div class="d-flex flex-wrap gap-3 text-muted small">
                                    <span><i class="fas fa-calendar me-1" style="color: var(--ju-green);"></i> {{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y - g:i A') }}</span>
                                    <span><i class="fas fa-map-marker-alt me-1" style="color: var(--ju-red);"></i> {{ $event->venue ?? 'TBD' }}, {{ $event->campus ?? 'Main Campus' }}</span>
                                </div>
                            </div>
                            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                <a href="{{ route('events.guest.show', $event->slug) }}" class="btn btn-outline-primary rounded-pill px-4">
                                    <i class="fas fa-eye me-2"></i>View Event
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Share Link Section -->
                    <div class="share-section mb-5">
                        <h5 class="fw-bold mb-3" style="color: var(--ju-blue);">
                            <i class="fas fa-link me-2"></i>Share Link
                        </h5>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control form-control-lg bg-light" id="shareLink" value="{{ route('events.guest.show', $event->slug) }}" readonly>
                            <button class="btn btn-primary-ju px-4" type="button" onclick="copyShareLink()">
                                <i class="fas fa-copy me-2"></i>Copy Link
                            </button>
                        </div>
                        <div class="alert alert-success small d-none" id="copySuccess">
                            <i class="fas fa-check-circle me-2"></i>Link copied to clipboard!
                        </div>
                    </div>
                    
                    <!-- Social Media Share -->
                    <div class="share-section mb-5">
                        <h5 class="fw-bold mb-3" style="color: var(--ju-blue);">
                            <i class="fas fa-share-alt me-2"></i>Share on Social Media
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-3 col-6">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('events.guest.show', $event->slug)) }}" 
                                   target="_blank" 
                                   class="social-share-btn facebook w-100">
                                    <i class="fab fa-facebook-f"></i>
                                    <span>Facebook</span>
                                </a>
                            </div>
                            <div class="col-md-3 col-6">
                                <a href="https://twitter.com/intent/tweet?text={{ urlencode($event->title) }}&url={{ urlencode(route('events.guest.show', $event->slug)) }}" 
                                   target="_blank" 
                                   class="social-share-btn twitter w-100">
                                    <i class="fab fa-twitter"></i>
                                    <span>Twitter</span>
                                </a>
                            </div>
                            <div class="col-md-3 col-6">
                                <a href="https://wa.me/?text={{ urlencode($event->title . ' - ' . route('events.guest.show', $event->slug)) }}" 
                                   target="_blank" 
                                   class="social-share-btn whatsapp w-100">
                                    <i class="fab fa-whatsapp"></i>
                                    <span>WhatsApp</span>
                                </a>
                            </div>
                            <div class="col-md-3 col-6">
                                <a href="https://t.me/share/url?url={{ urlencode(route('events.guest.show', $event->slug)) }}&text={{ urlencode($event->title) }}" 
                                   target="_blank" 
                                   class="social-share-btn telegram w-100">
                                    <i class="fab fa-telegram-plane"></i>
                                    <span>Telegram</span>
                                </a>
                            </div>
                            <div class="col-md-3 col-6">
                                <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(route('events.guest.show', $event->slug)) }}" 
                                   target="_blank" 
                                   class="social-share-btn linkedin w-100">
                                    <i class="fab fa-linkedin-in"></i>
                                    <span>LinkedIn</span>
                                </a>
                            </div>
                            <div class="col-md-3 col-6">
                                <a href="mailto:?subject={{ urlencode($event->title) }}&body={{ urlencode('Check out this event at Jimma University: ' . route('events.guest.show', $event->slug)) }}" 
                                   class="social-share-btn email w-100">
                                    <i class="fas fa-envelope"></i>
                                    <span>Email</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- QR Code Section -->
                    <div class="share-section text-center">
                        <h5 class="fw-bold mb-3" style="color: var(--ju-blue);">
                            <i class="fas fa-qrcode me-2"></i>QR Code
                        </h5>
                        <div class="qr-container p-4 bg-light rounded-4 d-inline-block mb-3">
                            <div id="qrcode"></div>
                        </div>
                        <p class="text-muted small mb-0">Scan this QR code to view the event on mobile</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .social-share-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        border-radius: 16px;
        text-decoration: none;
        color: white;
        transition: all 0.3s ease;
        border: none;
    }
    
    .social-share-btn i {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }
    
    .social-share-btn span {
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .social-share-btn:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        color: white;
    }
    
    .social-share-btn.facebook {
        background: #1877f2;
    }
    .social-share-btn.twitter {
        background: #1da1f2;
    }
    .social-share-btn.whatsapp {
        background: #25d366;
    }
    .social-share-btn.telegram {
        background: #0088cc;
    }
    .social-share-btn.linkedin {
        background: #0a66c2;
    }
    .social-share-btn.email {
        background: #ea4335;
    }
    
    .qr-container {
        background: white !important;
        display: inline-block;
        padding: 1rem;
        border-radius: 16px;
    }
    
    .icon-circle {
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .bg-primary-ju {
        background-color: #0A3D62 !important;
    }
    
    .btn-primary-ju {
        background-color: #0A3D62;
        color: white;
        border: none;
        transition: all 0.3s ease;
    }
    
    .btn-primary-ju:hover {
        background-color: #083452;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 14px rgba(10, 61, 98, 0.3);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
    // Copy share link function
    function copyShareLink() {
        const linkInput = document.getElementById('shareLink');
        linkInput.select();
        linkInput.setSelectionRange(0, 99999);
        document.execCommand('copy');
        
        // Show success message
        const successAlert = document.getElementById('copySuccess');
        successAlert.classList.remove('d-none');
        setTimeout(() => {
            successAlert.classList.add('d-none');
        }, 3000);
    }
    
    // Generate QR Code
    document.addEventListener('DOMContentLoaded', function() {
        const qrcode = new QRCode(document.getElementById("qrcode"), {
            text: "{{ route('events.guest.show', $event->slug) }}",
            width: 200,
            height: 200,
            colorDark: "#0A3D62",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
    });
</script>
@endpush