@extends('layouts.app')

@section('title', 'Event Dashboard - JU Event Management')
@section('page-title', 'Event Dashboard')
@section('page-subtitle', 'Discover and explore university events')

@section('breadcrumb-items')
    <li class="breadcrumb-item active">Event Dashboard</li>
@endsection

@section('content')
<!-- Hero Section -->
<div class="ju-card mb-4">
    <div class="ju-card-header">
        <h5 class="ju-card-title mb-0"><i class="fas fa-calendar-alt me-2"></i>Welcome to JU Events</h5>
    </div>
    <div class="ju-card-body">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="text-success">Discover Amazing Events at Jimma University</h2>
                <p class="lead">From academic conferences to cultural celebrations, there's always something happening on campus.</p>
                <div class="d-flex gap-2 mt-4">
                    <a href="#upcoming-events" class="btn btn-ju">
                        <i class="fas fa-rocket me-2"></i>View Upcoming Events
                    </a>
                    <a href="{{ route('events.guest.index') }}" class="btn btn-ju-outline">
                        <i class="fas fa-list me-2"></i>Browse All Events
                    </a>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <i class="fas fa-university fa-6x text-success opacity-25"></i>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="ju-card h-100">
            <div class="ju-card-body">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h6 class="text-muted mb-2">Total Events</h6>
                        <h3 class="mb-0">{{ $stats['total_events'] }}</h3>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon-circle bg-success text-white">
                            <i class="fas fa-calendar fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="ju-card h-100">
            <div class="ju-card-body">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h6 class="text-muted mb-2">Upcoming Events</h6>
                        <h3 class="mb-0">{{ $stats['upcoming_events'] }}</h3>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon-circle bg-info text-white">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="ju-card h-100">
            <div class="ju-card-body">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h6 class="text-muted mb-2">Ongoing Events</h6>
                        <h3 class="mb-0">{{ $stats['ongoing_events'] }}</h3>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon-circle bg-warning text-white">
                            <i class="fas fa-play-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="ju-card h-100">
            <div class="ju-card-body">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h6 class="text-muted mb-2">Featured Events</h6>
                        <h3 class="mb-0">{{ $stats['featured_events'] }}</h3>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon-circle bg-danger text-white">
                            <i class="fas fa-star fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Featured Events -->
@if($featuredEvents->count() > 0)
<div class="ju-card mb-4">
    <div class="ju-card-header d-flex justify-content-between align-items-center">
        <h5 class="ju-card-title mb-0"><i class="fas fa-star me-2 text-warning"></i>Featured Events</h5>
        <a href="{{ route('events.guest.index') }}?sort=start_date&order=asc" class="btn btn-sm btn-ju-outline">
            View All <i class="fas fa-arrow-right ms-1"></i>
        </a>
    </div>
    <div class="ju-card-body">
        <div class="row">
            @foreach($featuredEvents as $event)
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm hover-shadow">
                    <div class="position-relative">
                        @if($event->image)
                        <img src="{{ $event->image_url }}" class="card-img-top" alt="{{ $event->title }}" style="height: 200px; object-fit: cover;">
                        @else
                        <div class="card-img-top bg-success bg-opacity-10 d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-calendar-day fa-4x text-success opacity-50"></i>
                        </div>
                        @endif
                        <span class="position-absolute top-0 start-0 m-3">
                            <span class="badge bg-{{ $event->type_color }}">{{ ucfirst($event->event_type) }}</span>
                        </span>
                        @if($event->is_featured)
                        <span class="position-absolute top-0 end-0 m-3">
                            <span class="badge bg-warning"><i class="fas fa-star me-1"></i>Featured</span>
                        </span>
                        @endif
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $event->title }}</h5>
                        <p class="card-text text-muted small">{{ Str::limit($event->description, 100) }}</p>
                        
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-calendar text-success me-2"></i>
                            <small>{{ $event->start_date->format('M d, Y h:i A') }}</small>
                        </div>
                        
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-map-marker-alt text-success me-2"></i>
                            <small>{{ $event->venue }}, {{ $event->campus }}</small>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top-0">
                        <a href="{{ route('events.guest.show', $event->slug) }}" class="btn btn-sm btn-ju w-100">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Upcoming Events -->
<div class="ju-card mb-4" id="upcoming-events">
    <div class="ju-card-header d-flex justify-content-between align-items-center">
        <h5 class="ju-card-title mb-0"><i class="fas fa-rocket me-2 text-info"></i>Upcoming Events</h5>
        <a href="{{ route('events.guest.index') }}?date=upcoming" class="btn btn-sm btn-ju-outline">
            View All <i class="fas fa-arrow-right ms-1"></i>
        </a>
    </div>
    <div class="ju-card-body">
        @if($upcomingEvents->count() > 0)
        <div class="row">
            @foreach($upcomingEvents as $event)
            <div class="col-lg-6 mb-3">
                <div class="card border-start border-info border-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">{{ $event->title }}</h6>
                                <p class="text-muted small mb-2">{{ Str::limit($event->description, 80) }}</p>
                                
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-{{ $event->type_color }}">{{ ucfirst($event->event_type) }}</span>
                                    <span class="badge bg-secondary">{{ $event->campus }}</span>
                                    @if($event->requires_registration)
                                    <span class="badge bg-warning">Registration Required</span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="text-muted small">{{ $event->start_date->format('M d') }}</div>
                                <div class="text-muted small">{{ $event->start_date->format('h:i A') }}</div>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-6">
                                <small><i class="fas fa-map-marker-alt text-success me-1"></i> {{ $event->venue }}</small>
                            </div>
                            <div class="col-6 text-end">
                                <a href="{{ route('events.guest.show', $event->slug) }}" class="btn btn-sm btn-ju-outline">
                                    Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-4">
            <i class="fas fa-calendar-plus fa-3x text-muted mb-3"></i>
            <h5>No Upcoming Events</h5>
            <p class="text-muted">Check back later for upcoming events.</p>
        </div>
        @endif
    </div>
</div>

<!-- Ongoing Events -->
@if($ongoingEvents->count() > 0)
<div class="ju-card mb-4">
    <div class="ju-card-header d-flex justify-content-between align-items-center">
        <h5 class="ju-card-title mb-0"><i class="fas fa-play-circle me-2 text-warning"></i>Ongoing Events</h5>
        <a href="{{ route('events.guest.index') }}?date=ongoing" class="btn btn-sm btn-ju-outline">
            View All <i class="fas fa-arrow-right ms-1"></i>
        </a>
    </div>
    <div class="ju-card-body">
        <div class="row">
            @foreach($ongoingEvents as $event)
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card border-start border-warning border-3">
                    <div class="card-body">
                        <h6 class="card-title">{{ $event->title }}</h6>
                        <p class="text-muted small mb-2">{{ Str::limit($event->description, 60) }}</p>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-warning">Happening Now</span>
                            <small class="text-muted">{{ $event->venue }}</small>
                        </div>
                        
                        <div class="mt-3">
                            <a href="{{ route('events.guest.show', $event->slug) }}" class="btn btn-sm btn-warning w-100">
                                <i class="fas fa-external-link-alt me-1"></i> Join Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Quick Links -->
<div class="ju-card">
    <div class="ju-card-header">
        <h5 class="ju-card-title mb-0"><i class="fas fa-bolt me-2"></i>Quick Access</h5>
    </div>
    <div class="ju-card-body">
        <div class="row">
            <div class="col-md-3 col-sm-6 mb-3">
                <a href="{{ route('events.calendar') }}" class="btn btn-ju-outline w-100 h-100 py-4">
                    <i class="fas fa-calendar-alt fa-2x mb-2"></i><br>
                    Event Calendar
                </a>
            </div>
            
            <div class="col-md-3 col-sm-6 mb-3">
                <a href="{{ route('events.guest.index') }}?event_type=cultural" class="btn btn-ju-outline w-100 h-100 py-4">
                    <i class="fas fa-music fa-2x mb-2"></i><br>
                    Cultural Events
                </a>
            </div>
            
            <div class="col-md-3 col-sm-6 mb-3">
                <a href="{{ route('events.guest.index') }}?event_type=academic" class="btn btn-ju-outline w-100 h-100 py-4">
                    <i class="fas fa-graduation-cap fa-2x mb-2"></i><br>
                    Academic Events
                </a>
            </div>
            
            <div class="col-md-3 col-sm-6 mb-3">
                <a href="{{ route('events.guest.index') }}?event_type=sports" class="btn btn-ju-outline w-100 h-100 py-4">
                    <i class="fas fa-futbol fa-2x mb-2"></i><br>
                    Sports Events
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .hover-shadow {
        transition: transform 0.3s, box-shadow 0.3s;
    }
    
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .icon-circle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .border-start {
        border-left-width: 4px !important;
    }
</style>
@endpush
