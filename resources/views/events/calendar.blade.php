@extends('layouts.app')

@section('title', 'Event Calendar - JU Event Management')
@section('page-title', 'Event Calendar')
@section('page-subtitle', 'Browse events by date')

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('events.guest.index') }}">Events</a></li>
    <li class="breadcrumb-item active">Calendar</li>
@endsection

@section('content')
<div class="ju-card">
    <div class="ju-card-header d-flex justify-content-between align-items-center">
        <h5 class="ju-card-title mb-0"><i class="fas fa-calendar-alt me-2"></i>Event Calendar</h5>
        
        <div class="d-flex gap-2">
            <div class="btn-group">
                <a href="{{ route('events.calendar', ['month' => $month - 1, 'year' => $year]) }}" 
                   class="btn btn-outline-secondary">
                    <i class="fas fa-chevron-left"></i>
                </a>
                <button class="btn btn-ju-outline" disabled>
                    {{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}
                </button>
                <a href="{{ route('events.calendar', ['month' => $month + 1, 'year' => $year]) }}" 
                   class="btn btn-outline-secondary">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
            
            <a href="{{ route('events.calendar') }}" class="btn btn-ju-outline">
                <i class="fas fa-calendar-day me-2"></i>Today
            </a>
        </div>
    </div>
    
    <div class="ju-card-body">
        <!-- Calendar -->
        <div class="calendar">
            <!-- Weekday Headers -->
            <div class="row mb-2">
                @php
                    $weekdays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                @endphp
                
                @foreach($weekdays as $weekday)
                <div class="col text-center fw-bold text-muted">
                    {{ $weekday }}
                </div>
                @endforeach
            </div>
            
            <!-- Calendar Days -->
            @php
                $firstDay = date('N', mktime(0, 0, 0, $month, 1, $year)) % 7;
                $daysInMonth = date('t', mktime(0, 0, 0, $month, 1, $year));
                $today = date('j');
                $currentMonth = date('n');
                $currentYear = date('Y');
                
                $dayCount = 1;
            @endphp
            
            @for($i = 0; $i < 6; $i++)
            <div class="row mb-2">
                @for($j = 0; $j < 7; $j++)
                @if(($i == 0 && $j < $firstDay) || $dayCount > $daysInMonth)
                <div class="col calendar-day empty">
                    &nbsp;
                </div>
                @else
                @php
                    $date = sprintf('%04d-%02d-%02d', $year, $month, $dayCount);
                    $dayEvents = $events[$date] ?? [];
                    $isToday = ($dayCount == $today && $month == $currentMonth && $year == $currentYear);
                @endphp
                
                <div class="col calendar-day {{ $isToday ? 'today' : '' }}">
                    <div class="day-number">{{ $dayCount }}</div>
                    
                    @if(count($dayEvents) > 0)
                    <div class="day-events">
                        @foreach($dayEvents->take(2) as $event)
                        <div class="event-dot bg-{{ $event->type_color }}" 
                             data-bs-toggle="tooltip" 
                             title="{{ $event->title }}"></div>
                        @endforeach
                        
                        @if(count($dayEvents) > 2)
                        <div class="more-events">+{{ count($dayEvents) - 2 }}</div>
                        @endif
                    </div>
                    
                    <!-- Event Details Modal Trigger -->
                    <div class="mt-2">
                        <button class="btn btn-sm btn-outline-success w-100" 
                                data-bs-toggle="modal" 
                                data-bs-target="#dayEventsModal{{ $dayCount }}">
                            View Events
                        </button>
                    </div>
                    
                    <!-- Modal for Day Events -->
                    <div class="modal fade" id="dayEventsModal{{ $dayCount }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        Events on {{ date('F d, Y', mktime(0, 0, 0, $month, $dayCount, $year)) }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    @if(count($dayEvents) > 0)
                                    <div class="list-group">
                                        @foreach($dayEvents as $event)
                                        <a href="{{ route('events.guest.show', $event->slug) }}" 
                                           class="list-group-item list-group-item-action">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1">{{ $event->title }}</h6>
                                                <span class="badge bg-{{ $event->type_color }}">
                                                    {{ ucfirst($event->event_type) }}
                                                </span>
                                            </div>
                                            <p class="mb-1 small text-muted">{{ Str::limit($event->description, 100) }}</p>
                                            <small>
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $event->start_date->format('h:i A') }} - 
                                                {{ $event->end_date->format('h:i A') }}
                                                <i class="fas fa-map-marker-alt ms-3 me-1"></i>
                                                {{ $event->venue }}
                                            </small>
                                        </a>
                                        @endforeach
                                    </div>
                                    @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                        <h5>No Events Scheduled</h5>
                                        <p class="text-muted">There are no events scheduled for this date.</p>
                                    </div>
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-ju-outline" data-bs-dismiss="modal">Close</button>
                                    <a href="{{ route('events.guest.index') }}?date={{ $date }}" class="btn btn-ju">
                                        View All Date Events
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @php $dayCount++; @endphp
                @endif
                @endfor
            </div>
            @endfor
        </div>
        
        <!-- Legend -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-key me-2"></i>Event Type Legend</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-3">
                            <div class="d-flex align-items-center">
                                <div class="event-dot bg-primary me-2"></div>
                                <span>Academic</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="event-dot bg-success me-2"></div>
                                <span>Cultural</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="event-dot bg-warning me-2"></div>
                                <span>Sports</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="event-dot bg-info me-2"></div>
                                <span>Conference</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="event-dot bg-danger me-2"></div>
                                <span>Workshop</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="event-dot bg-secondary me-2"></div>
                                <span>Seminar/Other</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upcoming Events Summary -->
<div class="ju-card mt-4">
    <div class="ju-card-header">
        <h5 class="ju-card-title mb-0"><i class="fas fa-list me-2"></i>Upcoming Events This Month</h5>
    </div>
    <div class="ju-card-body">
        @php
            $upcomingThisMonth = collect($events)->flatten()->filter(function($event) {
                return $event->start_date->isFuture();
            })->take(5);
        @endphp
        
        @if($upcomingThisMonth->count() > 0)
        <div class="list-group list-group-flush">
            @foreach($upcomingThisMonth as $event)
            <a href="{{ route('events.guest.show', $event->slug) }}" 
               class="list-group-item list-group-item-action border-0 px-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">{{ $event->title }}</h6>
                        <small class="text-muted">
                            <i class="fas fa-calendar me-1"></i>
                            {{ $event->start_date->format('M d, Y h:i A') }}
                            <i class="fas fa-map-marker-alt ms-3 me-1"></i>
                            {{ $event->venue }}
                        </small>
                    </div>
                    <span class="badge bg-{{ $event->type_color }}">
                        {{ ucfirst($event->event_type) }}
                    </span>
                </div>
            </a>
            @endforeach
        </div>
        
        <div class="text-center mt-3">
            <a href="{{ route('events.guest.index') }}?date=month" class="btn btn-ju-outline">
                View All Events This Month
            </a>
        </div>
        @else
        <div class="text-center py-4">
            <i class="fas fa-calendar-plus fa-3x text-muted mb-3"></i>
            <h5>No Upcoming Events This Month</h5>
            <p class="text-muted">Check other months or browse all events.</p>
            <a href="{{ route('events.guest.index') }}" class="btn btn-ju">
                Browse All Events
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .calendar {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .calendar-day {
        min-height: 120px;
        border: 1px solid #e9ecef;
        padding: 10px;
        background: white;
        transition: all 0.3s;
    }
    
    .calendar-day:hover {
        background: #f8f9fa;
        transform: scale(1.02);
        z-index: 1;
        position: relative;
    }
    
    .calendar-day.today {
        background: #e8f5e9;
        border-color: #006400;
    }
    
    .calendar-day.empty {
        background: #f8f9fa;
    }
    
    .day-number {
        font-weight: bold;
        font-size: 1.2rem;
        color: #333;
        margin-bottom: 5px;
    }
    
    .day-events {
        display: flex;
        flex-wrap: wrap;
        gap: 3px;
        margin-bottom: 5px;
    }
    
    .event-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }
    
    .more-events {
        font-size: 0.8rem;
        color: #6c757d;
        margin-left: 3px;
    }
    
    @media (max-width: 768px) {
        .calendar-day {
            min-height: 80px;
            padding: 5px;
        }
        
        .day-number {
            font-size: 0.9rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));
        
        // Highlight today
        const todayElement = document.querySelector('.calendar-day.today');
        if (todayElement) {
            todayElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
</script>
@endpush