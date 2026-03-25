{{-- resources/views/feedback/testimonials.blade.php --}}
@extends('layouts.app')

@section('title', 'Event Testimonials - Real Feedback from Attendees')
@section('page-title', 'Event Testimonials')

@section('content')
<style>
    :root {
        --primary: #4361ee;
        --secondary: #3a0ca3;
        --accent: #f72585;
        --light: #f8f9fa;
        --dark: #212529;
    }
    
    .hero-section {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        padding: 5rem 0;
        color: white;
        position: relative;
        overflow: hidden;
        margin-top: -20px;
    }
    
    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
        background-size: cover;
    }
    
    .testimonial-card {
        background: white;
        border-radius: 20px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        overflow: hidden;
        height: 100%;
    }
    
    .testimonial-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.12);
    }
    
    .rating-stars {
        color: #FFD700;
        font-size: 1.1rem;
        letter-spacing: 2px;
    }
    
    .event-badge {
        background: linear-gradient(135deg, var(--accent) 0%, #ff6b6b 100%);
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.85rem;
        display: inline-block;
    }
    
    .user-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 1.5rem;
        margin-right: 15px;
    }
    
    .stats-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.06);
        text-align: center;
        transition: transform 0.3s ease;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
    }
    
    .stats-number {
        font-size: 3rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 10px;
    }
    
    .quote-icon {
        color: var(--primary);
        opacity: 0.2;
        font-size: 4rem;
        position: absolute;
        top: 10px;
        right: 20px;
    }
    
    .category-tag {
        background: #e9ecef;
        color: var(--dark);
        padding: 4px 12px;
        border-radius: 15px;
        font-size: 0.8rem;
        margin-right: 5px;
        margin-bottom: 5px;
        display: inline-block;
        transition: all 0.3s ease;
    }
    
    .category-tag:hover {
        background: var(--primary);
        color: white;
        transform: scale(1.05);
    }
    
    .testimonial-text {
        line-height: 1.7;
        color: #555;
        font-size: 1.05rem;
        position: relative;
        z-index: 1;
    }
    
    .empty-state {
        padding: 80px 20px;
        text-align: center;
    }
    
    .empty-state-icon {
        font-size: 5rem;
        color: #dee2e6;
        margin-bottom: 20px;
    }
</style>

<!-- Hero Section -->
<div class="hero-section">
    <div class="container position-relative">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-3 fw-bold mb-4">Event Testimonials</h1>
                <p class="lead mb-5 opacity-90">Real experiences shared by our amazing event attendees. See what they loved about our events!</p>
                
                <div class="row mt-5">
                    <div class="col-md-4 mb-4">
                        <div class="stats-card">
                            <div class="stats-number">{{ number_format($averageRating, 1) }}</div>
                            <h6 class="text-muted">AVERAGE RATING</h6>
                            <div class="rating-stars mt-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($averageRating))
                                        <i class="fas fa-star"></i>
                                    @elseif($i - 0.5 <= $averageRating)
                                        <i class="fas fa-star-half-alt"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="stats-card">
                            <div class="stats-number">{{ $totalTestimonials }}</div>
                            <h6 class="text-muted">TOTAL TESTIMONIALS</h6>
                            <p class="text-muted mb-0 small">From satisfied attendees</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="stats-card">
                            <div class="stats-number">{{ $testimonials->total() }}</div>
                            <h6 class="text-muted">SHARED EXPERIENCES</h6>
                            <a href="{{ route('feedback.create') }}" class="btn btn-primary btn-sm mt-3">
                                <i class="fas fa-plus me-2"></i>Share Yours
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Testimonials Section -->
<div class="container py-5">
    @if($testimonials->count() > 0)
        <!-- Filter/Sort Options -->
        <div class="row mb-5">
            <div class="col-md-8">
                <div class="nav-buttons bg-white p-3 rounded shadow-sm">
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('events.guest.dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-calendar-alt me-2"></i>Browse Events
                        </a>
                        @auth
                            @can('create_events')
                            <a href="{{ route('admin.events.create') }}" class="btn btn-outline-primary">
                                <i class="fas fa-plus-circle me-2"></i>Create Event
                            </a>
                            @endcan
                        @endauth
                        <a href="{{ route('feedback.create') }}" class="btn btn-success">
                            <i class="fas fa-comment-medical me-2"></i>Share Feedback
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <div class="d-inline-block bg-white p-3 rounded shadow-sm">
                    <small class="text-muted">
                        <i class="fas fa-filter me-1"></i>
                        Showing {{ $testimonials->firstItem() }}-{{ $testimonials->lastItem() }} of {{ $testimonials->total() }} testimonials
                    </small>
                </div>
            </div>
        </div>

        <!-- Testimonials Grid -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($testimonials as $testimonial)
            <div class="col">
                <div class="testimonial-card h-100">
                    <div class="card-body p-4">
                        <i class="fas fa-quote-right quote-icon"></i>
                        
                        <!-- User Info -->
                        <div class="d-flex align-items-center mb-4">
                            <div class="user-avatar">
                                {{ substr($testimonial->getSubmitterName(), 0, 1) }}
                            </div>
                            <div>
                                <h5 class="mb-1 fw-bold">{{ $testimonial->getSubmitterName() }}</h5>
                                <div class="d-flex align-items-center">
                                    @if($testimonial->rating)
                                    <div class="rating-stars me-3">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $testimonial->rating)
                                                <i class="fas fa-star"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <small class="text-muted">{{ $testimonial->rating }}/5</small>
                                    @else
                                    <small class="text-muted">No rating</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Event Info -->
                        @if($testimonial->event)
                        <div class="mb-4">
                            <span class="event-badge">
                                <i class="fas fa-ticket-alt me-2"></i>{{ $testimonial->event->title }}
                            </span>
                        </div>
                        @endif
                        
                        <!-- Testimonial Text -->
                        <p class="testimonial-text mb-4">
                            {{ $testimonial->message }}
                        </p>
                        
                        <!-- Categories -->
                        @if($testimonial->categories->count() > 0)
                            <div class="mb-4">
                                @foreach($testimonial->categories as $category)
                                    <span class="category-tag">
                                        <i class="fas fa-tag me-1"></i>{{ $category->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                        
                        <!-- Date -->
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <small class="text-muted">
                                <i class="far fa-calendar me-1"></i>
                                {{ $testimonial->created_at->format('M d, Y') }}
                            </small>
                            @if($testimonial->featured)
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-star me-1"></i>Featured
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-5">
            <nav aria-label="Testimonials pagination">
                {{ $testimonials->links() }}
            </nav>
        </div>
        
    @else
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-comments"></i>
            </div>
            <h3 class="mb-3">No testimonials yet</h3>
            <p class="text-muted mb-4">Be the first to share your event experience!</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('events.guest.dashboard') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-calendar-alt me-2"></i>Browse Events
                </a>
                <a href="{{ route('feedback.create') }}" class="btn btn-success btn-lg">
                    <i class="fas fa-comment-medical me-2"></i>Share Your Experience
                </a>
            </div>
        </div>
    @endif

    <!-- Call to Action -->
    <div class="mt-5 pt-5 text-center">
        <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #f8f9ff 0%, #eef1ff 100%);">
            <div class="card-body py-5">
                <h3 class="mb-3">Attended an event recently?</h3>
                <p class="lead text-muted mb-4">Share your experience and help others choose the best events!</p>
                <a href="{{ route('feedback.create') }}" class="btn btn-primary btn-lg px-5">
                    <i class="fas fa-comment-dots me-2"></i>Write a Testimonial
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Add subtle animations to cards on hover
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.testimonial-card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px)';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    });
</script>
@endpush