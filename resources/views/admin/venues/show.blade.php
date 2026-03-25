@extends('layouts.app')

@section('title', $venue->name . ' - Venue Details')
@section('page-title', $venue->name)
@section('page-subtitle', 'Venue Details')

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.venues.index') }}">Venues</a></li>
    <li class="breadcrumb-item active">{{ $venue->name }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Venue Header -->
    <div class="ju-card mb-4">
        <div class="ju-card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="ju-card-title m-0">{{ $venue->name }}</h5>
                <div class="btn-group">
                    <a href="{{ route('admin.venues.edit', $venue) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <form action="{{ route('admin.venues.toggle-availability', $venue) }}" method="POST" class="d-inline">
                        @csrf
                        @method('POST')
                        <button type="submit" class="btn btn-{{ $venue->is_available ? 'secondary' : 'success' }} btn-sm">
                            <i class="fas fa-{{ $venue->is_available ? 'times' : 'check' }} me-1"></i>
                            {{ $venue->is_available ? 'Mark Unavailable' : 'Mark Available' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="ju-card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong><i class="fas fa-building me-2"></i> Building:</strong>
                            <p class="mb-0">
                                <a href="{{ route('admin.buildings.show', $venue->building_id) }}" class="text-decoration-none">
                                    {{ $venue->building->name }}
                                </a>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <strong><i class="fas fa-university me-2"></i> Campus:</strong>
                            <p class="mb-0">
                                <a href="{{ route('admin.campuses.show', $venue->building->campus_id) }}" class="text-decoration-none">
                                    {{ $venue->building->campus->name }}
                                </a>
                            </p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            @php
                                $venueTypes = [
                                    'classroom' => 'Classroom',
                                    'auditorium' => 'Auditorium',
                                    'hall' => 'Hall',
                                    'lab' => 'Laboratory',
                                    'sports_complex' => 'Sports Complex',
                                    'conference_room' => 'Conference Room',
                                    'seminar_room' => 'Seminar Room',
                                    'open_space' => 'Open Space',
                                ];
                            @endphp
                            <strong><i class="fas fa-tag me-2"></i> Type:</strong>
                            <p class="mb-0">
                                <span class="badge bg-info">{{ $venueTypes[$venue->type] ?? ucfirst($venue->type) }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <strong><i class="fas fa-users me-2"></i> Capacity:</strong>
                            <p class="mb-0 h5">{{ $venue->capacity }} people</p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong><i class="fas fa-info-circle me-2"></i> Status:</strong>
                            <p class="mb-0">
                                @if($venue->is_available)
                                    <span class="badge bg-success">Available</span>
                                @else
                                    <span class="badge bg-secondary">Unavailable</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <strong><i class="fas fa-money-bill me-2"></i> Booking Fee:</strong>
                            <p class="mb-0">
                                @if($venue->booking_fee)
                                    ETB {{ number_format($venue->booking_fee, 2) }}
                                @else
                                    <span class="text-muted">Free</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    @if($venue->description)
                    <div class="mb-3">
                        <strong><i class="fas fa-align-left me-2"></i> Description:</strong>
                        <p class="mb-0">{{ $venue->description }}</p>
                    </div>
                    @endif
                    
                    @if($venue->amenities && count($venue->amenities) > 0)
                    <div class="mb-3">
                        <strong><i class="fas fa-tools me-2"></i> Amenities:</strong>
                        <div class="mt-2">
                            @foreach($venue->amenities as $amenity)
                            <span class="badge bg-light text-dark border me-2 mb-2 p-2">
                                <i class="fas fa-check text-success me-1"></i> {{ $amenity }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                <div class="col-md-4">
    <div class="card border-0 bg-light">
        <div class="card-body text-center">
            <i class="fas fa-calendar-alt fa-3x text-primary mb-3"></i>
            <h3>{{ $upcomingEvents->count() }}</h3>
            <p class="mb-0">Upcoming Events</p>
            <small class="text-muted">{{ $allEvents->count() }} total events</small>
        </div>
    </div>
</div>
            </div>
        </div>
    </div>

    <!-- Upcoming Events -->
@if($upcomingEvents->count() > 0)
<div class="ju-card mb-4">
    <div class="ju-card-header">
        <h5 class="ju-card-title m-0">Upcoming Events in this Venue</h5>
    </div>
    <div class="ju-card-body">
        <div class="table-responsive">
            <table class="table table-ju">
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Date & Time</th>
                        <th>Organizer</th>
                        <th>Registered</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($upcomingEvents as $event)
                        <tr>
                            <td>
                                <strong>{{ $event->title }}</strong>
                                <br><small class="text-muted">{{ Str::limit($event->description, 50) }}</small>
                            </td>
                            <td>
                                {{ $event->start_date->format('M d, Y') }}
                                <br><small class="text-muted">{{ $event->start_date->format('h:i A') }}</small>
                            </td>
                            <td>{{ $event->organizer }}</td>
                            <td>
                                @if($event->max_attendees)
                                <span class="badge bg-{{ $event->registered_count >= $event->max_attendees ? 'danger' : 'info' }}">
                                    {{ $event->registered_count }}/{{ $event->max_attendees }}
                                </span>
                                @else
                                <span class="badge bg-info">{{ $event->registered_count }} registered</span>
                                @endif
                            </td>
                            <td>
                                @if($event->start_date > now())
                                <span class="badge bg-success">Upcoming</span>
                                @elseif($event->end_date < now())
                                <span class="badge bg-secondary">Past</span>
                                @else
                                <span class="badge bg-warning">Ongoing</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.events.show', $event) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Availability Information -->
    <div class="ju-card">
        <div class="ju-card-header">
            <h5 class="ju-card-title m-0">Availability Information</h5>
        </div>
        <div class="ju-card-body">
            @if($venue->available_hours && count($venue->available_hours) > 0)
            <div class="row">
                @php
                    $days = [
                        'monday' => 'Monday',
                        'tuesday' => 'Tuesday', 
                        'wednesday' => 'Wednesday',
                        'thursday' => 'Thursday',
                        'friday' => 'Friday',
                        'saturday' => 'Saturday',
                        'sunday' => 'Sunday'
                    ];
                @endphp
                @foreach($days as $key => $day)
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-title">{{ $day }}</h6>
                            <p class="card-text">
                                @if(isset($venue->available_hours[$key]) && $venue->available_hours[$key])
                                    <span class="badge bg-success">Available</span>
                                    <br>
                                    <small>{{ $venue->available_hours[$key] }}</small>
                                @else
                                    <span class="badge bg-secondary">Not Available</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-4">
                <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No specific hours set</h5>
                <p class="text-muted">This venue is available based on booking schedule</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection