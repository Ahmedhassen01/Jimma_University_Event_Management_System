{{-- resources/views/events/my-events.blade.php --}}
@extends('layouts.app')

@section('title', 'My Events | Jimma University')
@section('page-title', 'My Events')
@section('page-subtitle', 'View and manage your event registrations')

@section('breadcrumb-items')
    <li class="breadcrumb-item">
        <a href="{{ route('home') }}" class="d-flex align-items-center text-decoration-none hover-lift">
            <i class="fas fa-home me-2" style="color: var(--ju-gold);"></i>Home
        </a>
    </li>
    <li class="breadcrumb-item active">
        <span class="fw-semibold" style="color: var(--ju-blue-dark);">My Events</span>
    </li>
@endsection

@section('content')
<style>
    :root {
        --ju-blue: #002789;
        --ju-blue-dark: #001a5c;
        --ju-gold: #C4A747;
        --ju-gold-dark: #a5862e;
        --ju-success: #28a745;
        --ju-warning: #ffc107;
        --ju-danger: #dc3545;
        --ju-info: #17a2b8;
        --ju-gray-100: #f8f9fa;
        --ju-gray-200: #e9ecef;
        --ju-gray-600: #6c757d;
        --ju-gray-800: #343a40;
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 30px;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
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

    .status-attended {
        background: #cce5ff;
        color: #004085;
        border: 1px solid #b8daff;
    }

    .event-card {
        transition: all 0.3s ease;
        border: 1px solid var(--ju-gray-200);
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 1rem;
    }

    .event-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,39,137,0.1);
        border-color: var(--ju-blue);
    }

    .countdown-timer {
        background: linear-gradient(145deg, #002789, #001a5c);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 30px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--ju-blue);
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--ju-gold);
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        background: white;
        border-radius: 12px;
        border: 2px dashed var(--ju-gray-200);
    }

    .empty-state i {
        font-size: 4rem;
        color: var(--ju-gray-600);
        margin-bottom: 1rem;
    }

    .empty-state h3 {
        color: var(--ju-gray-800);
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: var(--ju-gray-600);
        margin-bottom: 1.5rem;
    }
</style>

<div class="container-fluid px-lg-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h2 class="section-title mb-0">
                        <i class="fas fa-calendar-check me-2" style="color: var(--ju-gold);"></i>
                        My Event Registrations
                    </h2>
                </div>
                <div>
                    <a href="{{ route('event-registration.index') }}" class="btn btn-primary" style="background: linear-gradient(145deg, #002789, #001a5c); border: none;">
                        <i class="fas fa-calendar-plus me-2"></i>Browse Events
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Total Registrations</h6>
                            <h3 class="mb-0">{{ $registrations->total() }}</h3>
                        </div>
                        <i class="fas fa-ticket-alt fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Pending Approval</h6>
                            <h3 class="mb-0">{{ $registrations->where('status', 'pending')->count() }}</h3>
                        </div>
                        <i class="fas fa-clock fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Confirmed</h6>
                            <h3 class="mb-0">{{ $registrations->where('status', 'confirmed')->count() }}</h3>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Attended</h6>
                            <h3 class="mb-0">{{ $attendedCount ?? 0 }}</h3>
                        </div>
                        <i class="fas fa-user-check fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs mb-4" id="eventTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab">
                <i class="fas fa-list me-2"></i>All Events
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab">
                <i class="fas fa-clock me-2" style="color: #ffc107;"></i>Pending Approval
                @if($registrations->where('status', 'pending')->count() > 0)
                <span class="badge bg-warning text-dark ms-2">{{ $registrations->where('status', 'pending')->count() }}</span>
                @endif
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="confirmed-tab" data-bs-toggle="tab" data-bs-target="#confirmed" type="button" role="tab">
                <i class="fas fa-check-circle me-2" style="color: #28a745;"></i>Confirmed
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="waitlist-tab" data-bs-toggle="tab" data-bs-target="#waitlist" type="button" role="tab">
                <i class="fas fa-list-ol me-2" style="color: #17a2b8;"></i>Waitlist
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="past-tab" data-bs-toggle="tab" data-bs-target="#past" type="button" role="tab">
                <i class="fas fa-history me-2"></i>Past Events
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="eventTabsContent">
        <!-- All Events Tab -->
        <div class="tab-pane fade show active" id="all" role="tabpanel">
            @if($registrations->count() > 0)
                @foreach($registrations as $registration)
                <div class="event-card bg-white">
                    <div class="row g-0">
                        <div class="col-md-2" style="background: linear-gradient(145deg, #002789, #001a5c);">
                            <div class="h-100 d-flex align-items-center justify-content-center p-3">
                                @if($registration->event->image)
                                    <img src="{{ $registration->event->image_url }}" 
                                         alt="{{ $registration->event->title }}" 
                                         class="img-fluid rounded" 
                                         style="max-height: 100px; object-fit: cover;"
                                         onerror="this.onerror=null; this.parentElement.innerHTML='<i class=\'fas fa-calendar-alt fa-3x text-white opacity-50\'></i>';">
                                @else
                                    <i class="fas fa-calendar-alt fa-3x text-white opacity-50"></i>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-10 p-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h5 class="fw-bold mb-2" style="color: var(--ju-blue);">
                                            <a href="{{ route('events.guest.show', $registration->event) }}" class="text-decoration-none" style="color: var(--ju-blue);">
                                                {{ $registration->event->title }}
                                            </a>
                                        </h5>
                                        
                                        <div class="mb-2">
                                            <span class="badge bg-light text-dark me-2">
                                                <i class="fas fa-calendar me-1" style="color: var(--ju-blue);"></i>
                                                {{ $registration->event->start_date->format('M d, Y') }}
                                            </span>
                                            <span class="badge bg-light text-dark me-2">
                                                <i class="fas fa-clock me-1" style="color: var(--ju-blue);"></i>
                                                {{ $registration->event->start_date->format('h:i A') }}
                                            </span>
                                            <span class="badge bg-light text-dark me-2">
                                                <i class="fas fa-map-marker-alt me-1" style="color: var(--ju-blue);"></i>
                                                {{ $registration->event->campus ?? 'Main Campus' }}
                                            </span>
                                        </div>
                                        
                                        <div class="mb-2">
                                            <small class="text-muted">
                                                <i class="fas fa-users me-1"></i>
                                                Guests: <strong>{{ $registration->guest_count }}</strong> person(s)
                                            </small>
                                            <small class="text-muted ms-3">
                                                <i class="fas fa-hashtag me-1"></i>
                                                Reg #: {{ $registration->registration_number }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex flex-column align-items-end">
                                            <!-- Status Badge -->
                                            @if($registration->status == 'pending')
                                                <span class="status-badge status-pending mb-2">
                                                    <i class="fas fa-clock"></i> Pending Approval
                                                </span>
                                            @elseif($registration->status == 'confirmed')
                                                <span class="status-badge status-confirmed mb-2">
                                                    <i class="fas fa-check-circle"></i> Confirmed
                                                </span>
                                            @elseif($registration->status == 'cancelled')
                                                <span class="status-badge status-cancelled mb-2">
                                                    <i class="fas fa-times-circle"></i> Cancelled
                                                </span>
                                            @elseif($registration->status == 'waitlisted')
                                                <span class="status-badge status-waitlisted mb-2">
                                                    <i class="fas fa-list-ol"></i> Waitlisted
                                                </span>
                                            @endif
                                            
                                            <!-- Countdown Timer for Upcoming Events -->
                                            @if($registration->status == 'confirmed' && $registration->event->start_date > now())
                                                <div class="countdown-timer mb-2" data-date="{{ $registration->event->start_date->format('Y-m-d H:i:s') }}">
                                                    <i class="fas fa-hourglass-half"></i>
                                                    <span class="countdown-text">Starting soon</span>
                                                </div>
                                            @endif
                                            
                                            <!-- Attendance Status -->
                                            @if($registration->attended)
                                                <span class="status-badge status-attended mb-2">
                                                    <i class="fas fa-user-check"></i> Attended
                                                </span>
                                            @endif
                                            
                                            <!-- Action Buttons -->
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('event-registration.show', $registration->id) }}" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   data-bs-toggle="tooltip" 
                                                   title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                @if($registration->status == 'confirmed' && $registration->event->start_date > now() && !$registration->attended)
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#cancelModal{{ $registration->id }}"
                                                        title="Cancel Registration">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                @endif
                                                
                                                @if($registration->status == 'pending')
                                                <span class="btn btn-sm btn-outline-warning" 
                                                      data-bs-toggle="tooltip" 
                                                      title="Awaiting admin approval">
                                                    <i class="fas fa-hourglass-half"></i>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Cancel Modal for each registration -->
                @if($registration->status == 'confirmed' && $registration->event->start_date > now())
                <div class="modal fade" id="cancelModal{{ $registration->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header" style="background: linear-gradient(145deg, #dc3545, #b02a37); color: white;">
                                <h5 class="modal-title">
                                    <i class="fas fa-exclamation-triangle me-2"></i>Cancel Registration
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('event-registration.cancel', $registration->event) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <p>Are you sure you want to cancel your registration for:</p>
                                    <h6 class="fw-bold mb-3" style="color: var(--ju-blue);">{{ $registration->event->title }}</h6>
                                    
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Your seat will be offered to someone on the waitlist.
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
                @endif
                @endforeach
                
                <!-- Pagination -->
                @if($registrations->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $registrations->withQueryString()->links() }}
                </div>
                @endif
            @else
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <h3>No Registrations Yet</h3>
                    <p>You haven't registered for any events. Browse available events and register today!</p>
                    <a href="{{ route('event-registration.index') }}" class="btn btn-primary" style="background: linear-gradient(145deg, #002789, #001a5c); border: none;">
                        <i class="fas fa-calendar-plus me-2"></i>Browse Events
                    </a>
                </div>
            @endif
        </div>
        
        <!-- Pending Tab -->
        <div class="tab-pane fade" id="pending" role="tabpanel">
            @php $pendingRegistrations = $registrations->where('status', 'pending'); @endphp
            @if($pendingRegistrations->count() > 0)
                @foreach($pendingRegistrations as $registration)
                <div class="event-card bg-white">
                    <div class="row g-0">
                        <div class="col-md-2" style="background: linear-gradient(145deg, #ffc107, #d39e00);">
                            <div class="h-100 d-flex align-items-center justify-content-center p-3">
                                <i class="fas fa-clock fa-3x text-white"></i>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h5 class="fw-bold mb-2" style="color: var(--ju-blue);">
                                            {{ $registration->event->title }}
                                        </h5>
                                        <div class="mb-2">
                                            <span class="badge bg-light text-dark me-2">
                                                <i class="fas fa-calendar me-1" style="color: var(--ju-blue);"></i>
                                                {{ $registration->event->start_date->format('M d, Y') }}
                                            </span>
                                            <span class="badge bg-light text-dark me-2">
                                                <i class="fas fa-clock me-1" style="color: var(--ju-blue);"></i>
                                                {{ $registration->event->start_date->format('h:i A') }}
                                            </span>
                                        </div>
                                        <p class="text-muted mb-0">
                                            <i class="fas fa-users me-1"></i>Guests: {{ $registration->guest_count }}
                                        </p>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <span class="status-badge status-pending mb-2 d-inline-block">
                                            <i class="fas fa-clock"></i> Pending Approval
                                        </span>
                                        <p class="text-muted small mt-2">
                                            <i class="fas fa-info-circle"></i>
                                            Awaiting admin confirmation
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="empty-state">
                    <i class="fas fa-check-circle" style="color: #28a745;"></i>
                    <h3>No Pending Registrations</h3>
                    <p>All your registrations have been processed.</p>
                </div>
            @endif
        </div>
        
        <!-- Confirmed Tab -->
        <div class="tab-pane fade" id="confirmed" role="tabpanel">
            @php $confirmedRegistrations = $registrations->where('status', 'confirmed'); @endphp
            @if($confirmedRegistrations->count() > 0)
                @foreach($confirmedRegistrations as $registration)
                <div class="event-card bg-white">
                    <div class="row g-0">
                        <div class="col-md-2" style="background: linear-gradient(145deg, #28a745, #1e7e34);">
                            <div class="h-100 d-flex align-items-center justify-content-center p-3">
                                <i class="fas fa-check-circle fa-3x text-white"></i>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h5 class="fw-bold mb-2" style="color: var(--ju-blue);">
                                            {{ $registration->event->title }}
                                        </h5>
                                        <div class="mb-2">
                                            <span class="badge bg-light text-dark me-2">
                                                <i class="fas fa-calendar me-1" style="color: var(--ju-blue);"></i>
                                                {{ $registration->event->start_date->format('M d, Y') }}
                                            </span>
                                            <span class="badge bg-light text-dark me-2">
                                                <i class="fas fa-clock me-1" style="color: var(--ju-blue);"></i>
                                                {{ $registration->event->start_date->format('h:i A') }}
                                            </span>
                                        </div>
                                        @if($registration->event->start_date > now())
                                        <div class="countdown-timer mt-2" data-date="{{ $registration->event->start_date->format('Y-m-d H:i:s') }}">
                                            <i class="fas fa-hourglass-half"></i>
                                            <span class="countdown-text">Calculating...</span>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <span class="status-badge status-confirmed mb-2 d-inline-block">
                                            <i class="fas fa-check-circle"></i> Confirmed
                                        </span>
                                        <div class="mt-2">
                                            <a href="{{ route('event-registration.show', $registration->id) }}" class="btn btn-sm btn-outline-primary">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="empty-state">
                    <i class="fas fa-ticket-alt"></i>
                    <h3>No Confirmed Registrations</h3>
                    <p>You don't have any confirmed event registrations yet.</p>
                </div>
            @endif
        </div>
        
        <!-- Waitlist Tab -->
        <div class="tab-pane fade" id="waitlist" role="tabpanel">
            @if(isset($waitlists) && $waitlists->count() > 0)
                @foreach($waitlists as $waitlist)
                <div class="event-card bg-white">
                    <div class="row g-0">
                        <div class="col-md-2" style="background: linear-gradient(145deg, #17a2b8, #117a8b);">
                            <div class="h-100 d-flex align-items-center justify-content-center p-3">
                                <i class="fas fa-list-ol fa-3x text-white"></i>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h5 class="fw-bold mb-2" style="color: var(--ju-blue);">
                                            {{ $waitlist->event->title }}
                                        </h5>
                                        <div class="mb-2">
                                            <span class="badge bg-light text-dark me-2">
                                                <i class="fas fa-calendar me-1" style="color: var(--ju-blue);"></i>
                                                {{ $waitlist->event->start_date->format('M d, Y') }}
                                            </span>
                                            <span class="badge bg-light text-dark me-2">
                                                <i class="fas fa-clock me-1" style="color: var(--ju-blue);"></i>
                                                {{ $waitlist->event->start_date->format('h:i A') }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <span class="status-badge status-waitlisted mb-2 d-inline-block">
                                            <i class="fas fa-list-ol"></i> Position #{{ $waitlist->position }}
                                        </span>
                                        <p class="text-muted small mt-2">
                                            <i class="fas fa-info-circle"></i>
                                            You'll be notified if a spot opens
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="empty-state">
                    <i class="fas fa-list-ol"></i>
                    <h3>No Waitlist Positions</h3>
                    <p>You're not on any event waitlists.</p>
                </div>
            @endif
        </div>
        
        <!-- Past Events Tab -->
        <div class="tab-pane fade" id="past" role="tabpanel">
            @php $pastRegistrations = $registrations->where('event.end_date', '<', now()); @endphp
            @if($pastRegistrations->count() > 0)
                @foreach($pastRegistrations as $registration)
                <div class="event-card bg-white">
                    <div class="row g-0">
                        <div class="col-md-2" style="background: linear-gradient(145deg, #6c757d, #545b62);">
                            <div class="h-100 d-flex align-items-center justify-content-center p-3">
                                <i class="fas fa-history fa-3x text-white"></i>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h5 class="fw-bold mb-2" style="color: var(--ju-blue);">
                                            {{ $registration->event->title }}
                                        </h5>
                                        <div class="mb-2">
                                            <span class="badge bg-light text-dark me-2">
                                                <i class="fas fa-calendar me-1" style="color: var(--ju-blue);"></i>
                                                {{ $registration->event->start_date->format('M d, Y') }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        @if($registration->attended)
                                        <span class="status-badge status-attended mb-2 d-inline-block">
                                            <i class="fas fa-user-check"></i> Attended
                                        </span>
                                        @else
                                        <span class="status-badge bg-secondary text-white mb-2 d-inline-block">
                                            <i class="fas fa-times-circle"></i> Not Attended
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="empty-state">
                    <i class="fas fa-calendar-check"></i>
                    <h3>No Past Events</h3>
                    <p>You haven't attended any events yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();
        
        // Countdown timers
        function updateCountdowns() {
            $('.countdown-timer').each(function() {
                const eventDateStr = $(this).data('date');
                if (!eventDateStr) return;
                
                const eventDate = new Date(eventDateStr).getTime();
                const now = new Date().getTime();
                const distance = eventDate - now;
                
                if (distance < 0) {
                    $(this).find('.countdown-text').text('Event started');
                    return;
                }
                
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                
                let countdownText = '';
                if (days > 0) {
                    countdownText = days + 'd ' + hours + 'h ' + minutes + 'm';
                } else if (hours > 0) {
                    countdownText = hours + 'h ' + minutes + 'm';
                } else {
                    countdownText = minutes + 'm';
                }
                
                $(this).find('.countdown-text').text(countdownText);
            });
        }
        
        // Update countdowns every minute
        updateCountdowns();
        setInterval(updateCountdowns, 60000);
        
        // Tab persistence
        const activeTab = localStorage.getItem('activeEventTab');
        if (activeTab) {
            $('#eventTabs button[data-bs-target="' + activeTab + '"]').tab('show');
        }
        
        $('#eventTabs button').on('shown.bs.tab', function(e) {
            localStorage.setItem('activeEventTab', $(e.target).data('bs-target'));
        });
        
        // Print registration details
        window.printRegistration = function(registrationId) {
            window.open('/my-registrations/' + registrationId + '/print', '_blank');
        };
    });
</script>
@endpush
@endsection
