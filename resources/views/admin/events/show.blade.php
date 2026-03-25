{{-- resources/views/admin/events/show.blade.php --}}
@extends('layouts.app')

@section('title', $event->title . ' - Jimma University')

@section('page-title', $event->title)
@section('page-subtitle', 'Event Details')

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.events.index') }}">Events</a></li>
    <li class="breadcrumb-item active">{{ Str::limit($event->title, 20) }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="ju-card mb-4">
            <div class="ju-card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #003366 0%, #004080 100%);">
                <h5 class="ju-card-title text-white mb-0">Event Information</h5>
                <div class="btn-group">
                    <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <a href="{{ route('admin.events.speakers.manage', $event) }}" class="btn btn-sm btn-info">
                        <i class="fas fa-users me-1"></i> Speakers
                    </a>
                    <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" 
                                onclick="return confirm('Are you sure you want to delete this event?')">
                            <i class="fas fa-trash me-1"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
            <div class="ju-card-body">
                @if($event->image)
                <div class="text-center mb-4">
                    <img src="{{ $event->image_url }}" alt="{{ $event->title }}" 
                         class="img-fluid rounded" style="max-height: 400px;">
                </div>
                @endif
                
                <div class="mb-4">
                    <h4>{{ $event->title }}</h4>
                    <p class="text-muted">{{ $event->description }}</p>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <h6 style="color: #003366;"><i class="fas fa-calendar-alt me-2"></i> Date & Time</h6>
                            <div class="ms-4">
                                <div><strong>Start:</strong> {{ $event->start_date->format('l, F j, Y \a\t h:i A') }}</div>
                                <div><strong>End:</strong> {{ $event->end_date->format('l, F j, Y \a\t h:i A') }}</div>
                                <div><strong>Duration:</strong> {{ $event->start_date->diffForHumans($event->end_date, true) }}</div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <h6 style="color: #003366;"><i class="fas fa-map-marker-alt me-2"></i> Location</h6>
                            <div class="ms-4">
                                <div><strong>Campus:</strong> {{ $event->campus_name }}</div>
                                <div><strong>Building:</strong> {{ $event->building_name }}</div>
                                <div><strong>Venue:</strong> {{ $event->venue_name }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <h6 style="color: #003366;"><i class="fas fa-info-circle me-2"></i> Event Details</h6>
                            <div class="ms-4">
                                <div><strong>Type:</strong> <span class="badge" style="background-color: #003366; color: white;">{{ ucfirst($event->event_type) }}</span></div>
                                <div><strong>Organizer:</strong> {{ $event->organizer }}</div>
                                <div><strong>Status:</strong> 
                                    @php
                                        $status = $event->status;
                                        $badgeClass = $status == 'upcoming' ? 'bg-primary' : ($status == 'ongoing' ? 'bg-success' : 'bg-secondary');
                                        $bgColor = $status == 'upcoming' ? '#003366' : ($status == 'ongoing' ? '#004d40' : '#6c757d');
                                    @endphp
                                    <span class="badge" style="background-color: {{ $bgColor }}; color: white;">{{ ucfirst($status) }}</span>
                                </div>
                                <div><strong>Visibility:</strong> 
                                    <span class="badge {{ $event->is_public ? 'bg-success' : 'bg-warning' }}">
                                        {{ $event->is_public ? 'Public' : 'Private' }}
                                    </span>
                                </div>
                                <div><strong>Featured:</strong> 
                                    <span class="badge {{ $event->is_featured ? 'bg-warning' : 'bg-secondary' }}">
                                        {{ $event->is_featured ? 'Yes' : 'No' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <h6 style="color: #003366;"><i class="fas fa-users me-2"></i> Attendance</h6>
                            <div class="ms-4">
                                <div><strong>Max Attendees:</strong> {{ $event->max_attendees ?: 'Unlimited' }}</div>
                                <div><strong>Registered:</strong> {{ $event->registered_attendees }}</div>
                                <div><strong>Available Seats:</strong> {{ $event->available_seats ?? 'Unlimited' }}</div>
                                <div><strong>Registration:</strong> 
                                    <span class="badge {{ $event->requires_registration ? 'bg-primary' : 'bg-secondary' }}">
                                        {{ $event->requires_registration ? 'Required' : 'Not Required' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($event->contact_email || $event->contact_phone)
                <div class="mb-3">
                    <h6 style="color: #003366;"><i class="fas fa-address-book me-2"></i> Contact Information</h6>
                    <div class="ms-4">
                        @if($event->contact_email)
                        <div><strong>Email:</strong> <a href="mailto:{{ $event->contact_email }}" style="color: #003366;">{{ $event->contact_email }}</a></div>
                        @endif
                        @if($event->contact_phone)
                        <div><strong>Phone:</strong> <a href="tel:{{ $event->contact_phone }}" style="color: #003366;">{{ $event->contact_phone }}</a></div>
                        @endif
                    </div>
                </div>
                @endif
                
                @if($event->tags)
                <div class="mb-3">
                    <h6 style="color: #003366;"><i class="fas fa-tags me-2"></i> Tags</h6>
                    <div class="ms-4">
                        @foreach($event->formatted_tags as $tag)
                            <span class="badge bg-light text-dark me-1" style="border: 1px solid #003366;">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Speakers Section -->
        <div class="ju-card mb-4">
            <div class="ju-card-header" style="background: linear-gradient(135deg, #003366 0%, #004080 100%);">
                <h5 class="ju-card-title text-white mb-0">
                    <i class="fas fa-users me-2"></i>
                    Event Speakers ({{ $event->speakers->count() }})
                </h5>
            </div>
            <div class="ju-card-body">
                @if($event->speakers->count() > 0)
                    <div class="row">
                        @foreach($event->speakers as $speaker)
                            <div class="col-md-6 mb-3">
                                <div class="card h-100 border" style="border-color: #003366 !important;">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            @if($speaker->photo)
                                                <img src="{{ $speaker->photo_url }}" 
                                                     alt="{{ $speaker->name }}"
                                                     class="rounded-circle me-3"
                                                     style="width: 60px; height: 60px; object-fit: cover;">
                                            @else
                                                <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                                                     style="width: 60px; height: 60px; background: linear-gradient(135deg, #003366 0%, #004080 100%); color: white;">
                                                    <i class="fas fa-user fa-2x"></i>
                                                </div>
                                            @endif
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{ $speaker->full_name }}</h6>
                                                <small class="text-muted d-block">{{ $speaker->position }}</small>
                                                <small class="text-muted d-block">{{ $speaker->organization }}</small>
                                                
                                                @if($speaker->pivot->session_title)
                                                    <div class="mt-2">
                                                        <strong class="small" style="color: #003366;">Session:</strong>
                                                        <p class="small mb-1">{{ $speaker->pivot->session_title }}</p>
                                                    </div>
                                                @endif
                                                
                                                @if($speaker->pivot->session_time)
                                                    <div class="small">
                                                        <i class="far fa-clock me-1" style="color: #003366;"></i>
                                                        {{ \Carbon\Carbon::parse($speaker->pivot->session_time)->format('M d, Y h:i A') }}
                                                        @if($speaker->pivot->session_duration)
                                                            ({{ $speaker->pivot->session_duration }} min)
                                                        @endif
                                                    </div>
                                                @endif
                                                
                                                <div class="mt-2">
                                                    @if($speaker->pivot->is_keynote)
                                                        <span class="badge" style="background-color: #003366; color: white;">Keynote</span>
                                                    @endif
                                                    @if($speaker->pivot->is_moderator)
                                                        <span class="badge bg-success">Moderator</span>
                                                    @endif
                                                    @if($speaker->pivot->is_panelist)
                                                        <span class="badge bg-info">Panelist</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-user-slash fa-3x mb-3" style="color: #003366;"></i>
                        <h5>No Speakers Assigned</h5>
                        <p class="text-muted">This event doesn't have any speakers yet.</p>
                        <a href="{{ route('admin.events.speakers.manage', $event) }}" class="btn" style="background-color: #003366; color: white;">
                            <i class="fas fa-plus me-2"></i>Add Speakers
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="ju-card mb-4">
            <div class="ju-card-header" style="background: linear-gradient(135deg, #003366 0%, #004080 100%);">
                <h6 class="ju-card-title text-white mb-0">Quick Actions</h6>
            </div>
            <div class="ju-card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i> Edit Event
                    </a>
                    
                    <a href="{{ route('admin.events.speakers.manage', $event) }}" class="btn btn-info">
                        <i class="fas fa-users me-2"></i> Manage Speakers
                    </a>
                    
                    <form action="{{ route('admin.events.toggle-featured', $event) }}" method="POST" class="d-grid">
                        @csrf
                        @method('POST')
                        <button type="submit" class="btn {{ $event->is_featured ? 'btn-secondary' : 'btn-outline-warning' }}">
                            <i class="fas fa-star me-2"></i> 
                            {{ $event->is_featured ? 'Remove from Featured' : 'Mark as Featured' }}
                        </button>
                    </form>
                    
                    <a href="{{ route('admin.events.index') }}" class="btn btn-outline-primary" style="border-color: #003366; color: #003366;">
                        <i class="fas fa-list me-2"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
        
        <div class="ju-card">
            <div class="ju-card-header" style="background: linear-gradient(135deg, #003366 0%, #004080 100%);">
                <h6 class="ju-card-title text-white mb-0">Event Statistics</h6>
            </div>
            <div class="ju-card-body">
                <div class="mb-3">
                    <small class="text-muted">Created</small>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-calendar-plus me-2" style="color: #003366;"></i>
                        {{ $event->created_at->format('M d, Y') }}
                    </div>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted">Last Updated</small>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-history me-2" style="color: #003366;"></i>
                        {{ $event->updated_at->format('M d, Y') }}
                    </div>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted">Speakers</small>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-users me-2" style="color: #003366;"></i>
                        <h4 class="mb-0">{{ $event->speakers->count() }}</h4>
                    </div>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted">Days Until Event</small>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-clock me-2" style="color: #003366;"></i>
                        @php
                            $days = now()->diffInDays($event->start_date, false);
                            if ($days < 0) {
                                echo 'Event has passed';
                            } elseif ($days == 0) {
                                echo 'Today';
                            } else {
                                echo $days . ' day' . ($days == 1 ? '' : 's');
                            }
                        @endphp
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection