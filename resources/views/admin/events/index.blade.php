{{-- resources/views/admin/events/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Events Management - Jimma University')
@section('page-title', 'Events Management')
@section('page-subtitle', 'Manage all university events and activities')

@section('breadcrumb-items')
    <li class="breadcrumb-item active">All Events</li>
@endsection

@section('content')
<div class="content-area">
    <!-- Quick Stats - Lighter Blue Theme -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card" style="background: linear-gradient(135deg, #e6f0fa 0%, #d4e4f5 100%);">
                <div class="stat-icon">
                    <i class="fas fa-calendar-alt" style="color: #0A3D62;"></i>
                </div>
                <div class="stat-number" style="color: #0A3D62;">{{ $totalCount }}</div>
                <div class="stat-label" style="color: #1a4f7a;">Total Events</div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card" style="background: linear-gradient(135deg, #e3f1e3 0%, #c8e6c9 100%);">
                <div class="stat-icon">
                    <i class="fas fa-clock" style="color: #1e7e34;"></i>
                </div>
                <div class="stat-number" style="color: #1e7e34;">{{ $upcomingCount }}</div>
                <div class="stat-label" style="color: #256d3a;">Upcoming</div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card" style="background: linear-gradient(135deg, #e1f5fe 0%, #b3e5fc 100%);">
                <div class="stat-icon">
                    <i class="fas fa-play-circle" style="color: #0277bd;"></i>
                </div>
                <div class="stat-number" style="color: #0277bd;">{{ $ongoingCount }}</div>
                <div class="stat-label" style="color: #01579b;">Ongoing</div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card" style="background: linear-gradient(135deg, #fbe9e7 0%, #ffccbc 100%);">
                <div class="stat-icon">
                    <i class="fas fa-history" style="color: #b71c1c;"></i>
                </div>
                <div class="stat-number" style="color: #b71c1c;">{{ $completedCount }}</div>
                <div class="stat-label" style="color: #8b1e1e;">Completed</div>
            </div>
        </div>
    </div>

    <!-- Filters Card - Lighter Blue Theme -->
    <div class="ju-card mb-4">
        <div class="ju-card-header" style="background: linear-gradient(135deg, #0A3D62 0%, #1a4f7a 100%);">
            <h5 class="ju-card-title text-white mb-0">
                <i class="fas fa-filter me-2"></i>Filter Events
            </h5>
        </div>
        <div class="ju-card-body" style="background: #f8fafc;">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label-ju" style="color: #0A3D62; font-weight: 500;">Search</label>
                        <div class="input-group">
                            <span class="input-group-text" style="border-color: #d4e4f5; background: white;">
                                <i class="fas fa-search" style="color: #0A3D62;"></i>
                            </span>
                            <input type="text" 
                                   class="form-control" 
                                   id="searchInput" 
                                   placeholder="Search events..."
                                   value="{{ request('search') }}"
                                   style="border-color: #d4e4f5; focus:border-#0A3D62;">
                        </div>
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label-ju" style="color: #0A3D62; font-weight: 500;">Status</label>
                        <select class="form-select" id="statusFilter" style="border-color: #d4e4f5;">
                            <option value="">All Status</option>
                            <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>
                                Upcoming
                            </option>
                            <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>
                                Ongoing
                            </option>
                            <option value="past" {{ request('status') == 'past' ? 'selected' : '' }}>
                                Completed
                            </option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label-ju" style="color: #0A3D62; font-weight: 500;">Type</label>
                        <select class="form-select" id="typeFilter" style="border-color: #d4e4f5;">
                            <option value="">All Types</option>
                            <option value="academic" {{ request('event_type') == 'academic' ? 'selected' : '' }}>
                                Academic
                            </option>
                            <option value="cultural" {{ request('event_type') == 'cultural' ? 'selected' : '' }}>
                                Cultural
                            </option>
                            <option value="sports" {{ request('event_type') == 'sports' ? 'selected' : '' }}>
                                Sports
                            </option>
                            <option value="conference" {{ request('event_type') == 'conference' ? 'selected' : '' }}>
                                Conference
                            </option>
                            <option value="workshop" {{ request('event_type') == 'workshop' ? 'selected' : '' }}>
                                Workshop
                            </option>
                            <option value="seminar" {{ request('event_type') == 'seminar' ? 'selected' : '' }}>
                                Seminar
                            </option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label-ju" style="color: #0A3D62; font-weight: 500;">Campus</label>
                        <select class="form-select" id="campusFilter" style="border-color: #d4e4f5;">
                            <option value="">All Campuses</option>
                            @foreach($campuses as $campus)
                            <option value="{{ $campus->id }}" {{ request('campus_id') == $campus->id ? 'selected' : '' }}>
                                {{ $campus->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label-ju" style="color: #0A3D62; font-weight: 500;">Speaker</label>
                        <select class="form-select" id="speakerFilter" style="border-color: #d4e4f5;">
                            <option value="">All Speakers</option>
                            @foreach($speakers as $speaker)
                            <option value="{{ $speaker->id }}" {{ request('speaker_id') == $speaker->id ? 'selected' : '' }}>
                                {{ $speaker->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-12">
                    <div class="d-flex gap-2 justify-content-end">
                        <button class="btn" style="background: #0A3D62; color: white; border: none;" id="applyFilters">
                            <i class="fas fa-filter me-2"></i>Apply Filters
                        </button>
                        <a href="{{ route('admin.events.index') }}" class="btn" style="border: 1px solid #0A3D62; color: #0A3D62; background: white;">
                            <i class="fas fa-redo me-2"></i>Reset
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Events Table Card - Lighter Blue Theme -->
    <div class="ju-card">
        <div class="ju-card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #0A3D62 0%, #1a4f7a 100%);">
            <h5 class="ju-card-title text-white mb-0">
                <i class="fas fa-calendar me-2"></i>All Events
            </h5>
            <div>
                <a href="{{ route('admin.events.export') }}" class="btn btn-light me-2" style="color: #0A3D62; background: white;">
                    <i class="fas fa-download me-2"></i>Export
                </a>
                <a href="{{ route('admin.events.create') }}" class="btn" style="background: white; color: #0A3D62;">
                    <i class="fas fa-plus me-2"></i>Create Event
                </a>
            </div>
        </div>
        <div class="ju-card-body" style="background: white;">
            @if($events->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover" id="eventsTable">
                    <thead>
                        <tr style="background: #f8fafc;">
                            <th style="color: #0A3D62; font-weight: 600;">Event Details</th>
                            <th style="color: #0A3D62; font-weight: 600;">Type & Organizer</th>
                            <th style="color: #0A3D62; font-weight: 600;">Speakers</th>
                            <th style="color: #0A3D62; font-weight: 600;">Date & Time</th>
                            <th style="color: #0A3D62; font-weight: 600;">Venue</th>
                            <th style="color: #0A3D62; font-weight: 600;">Status</th>
                            <th style="color: #0A3D62; font-weight: 600;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($events as $event)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($event->image)
                                    <div class="flex-shrink-0 me-3">
                                        <img src="{{ $event->image_url }}" 
                                             alt="{{ $event->title }}"
                                             class="rounded"
                                             style="width: 60px; height: 60px; object-fit: cover; border: 2px solid #e6f0fa;">
                                    </div>
                                    @else
                                    <div class="flex-shrink-0 me-3">
                                        <div class="ju-avatar ju-avatar-lg" style="background: #e6f0fa;">
                                            <i class="fas fa-calendar" style="color: #0A3D62;"></i>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1" style="color: #0A3D62;">{{ $event->title }}</h6>
                                        <p class="text-muted small mb-0">{{ Str::limit($event->short_description ?: $event->description, 60) }}</p>
                                        <div class="mt-1">
                                            <small class="text-muted">
                                                <i class="fas fa-users me-1" style="color: #0A3D62;"></i>
                                                {{ $event->registered_attendees }} / {{ $event->max_attendees ?? '∞' }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="mb-2">
                                    <span class="badge" style="background: #e6f0fa; color: #0A3D62;">
                                        {{ ucfirst($event->event_type) }}
                                    </span>
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-user-tie me-1" style="color: #0A3D62;"></i>
                                    {{ $event->organizer }}
                                </small>
                            </td>
                            <td>
                                @if($event->speakers->count() > 0)
                                    <div class="speaker-avatars">
                                        @foreach($event->speakers->take(3) as $speaker)
                                            @if($speaker->photo)
                                                <img src="{{ $speaker->photo_url }}" 
                                                     alt="{{ $speaker->name }}"
                                                     class="rounded-circle border-2"
                                                     style="width: 30px; height: 30px; object-fit: cover; margin-left: -5px; border-color: #e6f0fa;"
                                                     data-bs-toggle="tooltip"
                                                     title="{{ $speaker->name }}">
                                            @else
                                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center border-2"
                                                     style="width: 30px; height: 30px; background: #e6f0fa; color: #0A3D62; margin-left: -5px; border-color: white;"
                                                     data-bs-toggle="tooltip"
                                                     title="{{ $speaker->name }}">
                                                    <i class="fas fa-user fa-xs"></i>
                                                </div>
                                            @endif
                                        @endforeach
                                        @if($event->speakers->count() > 3)
                                            <span class="ms-1 small text-muted">+{{ $event->speakers->count() - 3 }}</span>
                                        @endif
                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        {{ $event->keynoteSpeakers->count() }} keynote
                                    </small>
                                @else
                                    <span class="text-muted small">No speakers</span>
                                @endif
                            </td>
                            <td>
                                <div class="small">
                                    <div class="mb-1">
                                        <i class="fas fa-play me-1" style="color: #28a745;"></i>
                                        {{ $event->start_date->format('M d, Y h:i A') }}
                                    </div>
                                    <div>
                                        <i class="fas fa-stop me-1" style="color: #dc3545;"></i>
                                        {{ $event->end_date->format('M d, Y h:i A') }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="small">
                                    @if($event->campus_name != 'Not specified')
                                    <div class="mb-1">
                                        <i class="fas fa-university me-1" style="color: #0A3D62;"></i>
                                        {{ $event->campus_name }}
                                    </div>
                                    @endif
                                    
                                    @if($event->building_name != 'Not specified')
                                    <div class="mb-1">
                                        <i class="fas fa-building me-1" style="color: #0A3D62;"></i>
                                        {{ $event->building_name }}
                                    </div>
                                    @endif
                                    
                                    @if($event->venue_name != 'Not specified')
                                    <div>
                                        <i class="fas fa-door-open me-1" style="color: #0A3D62;"></i>
                                        {{ $event->venue_name }}
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @php
                                    $status = $event->status;
                                    $bgColor = $status == 'upcoming' ? '#e6f0fa' : ($status == 'ongoing' ? '#e3f1e3' : '#fbe9e7');
                                    $textColor = $status == 'upcoming' ? '#0A3D62' : ($status == 'ongoing' ? '#1e7e34' : '#b71c1c');
                                    $icon = $status == 'upcoming' ? 'clock' : ($status == 'ongoing' ? 'play-circle' : 'check-circle');
                                @endphp
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge" style="background: {{ $bgColor }}; color: {{ $textColor }}; padding: 0.5rem 0.75rem;">
                                        <i class="fas fa-{{ $icon }} me-1"></i>
                                        {{ ucfirst($status) }}
                                    </span>
                                    @if($event->is_featured)
                                    <span class="badge" style="background: #fff3e0; color: #b76e00;" title="Featured Event">
                                        <i class="fas fa-star"></i>
                                    </span>
                                    @endif
                                    @if(!$event->is_public)
                                    <span class="badge" style="background: #e9ecef; color: #495057;" title="Private Event">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.events.show', $event) }}" 
                                       class="btn" 
                                       data-bs-toggle="tooltip" 
                                       title="View Details"
                                       style="border: 1px solid #e6f0fa; color: #0A3D62; margin-right: 0.25rem;">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.events.edit', $event) }}" 
                                       class="btn" 
                                       data-bs-toggle="tooltip" 
                                       title="Edit Event"
                                       style="border: 1px solid #e6f0fa; color: #ffc107; margin-right: 0.25rem;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.events.speakers.manage', $event) }}" 
                                       class="btn" 
                                       data-bs-toggle="tooltip" 
                                       title="Manage Speakers"
                                       style="border: 1px solid #e6f0fa; color: #28a745; margin-right: 0.25rem;">
                                        <i class="fas fa-users"></i>
                                    </a>
                                    <form action="{{ route('admin.events.destroy', $event) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this event?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn" 
                                                data-bs-toggle="tooltip" 
                                                title="Delete Event"
                                                style="border: 1px solid #e6f0fa; color: #dc3545;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-calendar-times fa-4x" style="color: #e6f0fa;"></i>
                </div>
                <h4 style="color: #0A3D62;">No Events Found</h4>
                <p class="text-muted mb-4">Get started by creating your first event</p>
                <a href="{{ route('admin.events.create') }}" class="btn" style="background: #0A3D62; color: white; border: none;">
                    <i class="fas fa-plus me-2"></i>Create Event
                </a>
            </div>
            @endif
        </div>
        
        @if($events->hasPages())
        <div class="ju-card-footer" style="background: #f8fafc; border-top: 1px solid #e6f0fa;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">
                        Showing {{ $events->firstItem() }} to {{ $events->lastItem() }} of {{ $events->total() }} entries
                    </small>
                </div>
                <div>
                    {{ $events->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Lighter Blue Theme */
    :root {
        --ju-lighter: #e6f0fa;
        --ju-light: #d4e4f5;
        --ju-primary: #0A3D62;
        --ju-secondary: #1a4f7a;
    }

    .stat-card {
        padding: 1.5rem;
        border-radius: 12px;
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        box-shadow: 0 2px 8px rgba(10, 61, 98, 0.08);
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(10, 61, 98, 0.12);
    }
    
    .stat-card .stat-icon {
        position: absolute;
        right: 20px;
        top: 20px;
        font-size: 2.5rem;
        opacity: 0.2;
    }
    
    .stat-card .stat-number {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }
    
    .stat-card .stat-label {
        font-size: 0.875rem;
        font-weight: 500;
        opacity: 0.8;
    }
    
    .ju-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(10, 61, 98, 0.08);
        overflow: hidden;
    }
    
    .ju-card-header {
        padding: 1rem 1.5rem;
        border-bottom: none;
    }
    
    .ju-card-body {
        padding: 1.5rem;
    }
    
    .ju-card-footer {
        padding: 1rem 1.5rem;
    }
    
    .form-label-ju {
        font-weight: 500;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }
    
    .form-control:focus,
    .form-select:focus {
        border-color: #0A3D62;
        box-shadow: 0 0 0 0.2rem rgba(10, 61, 98, 0.1);
    }
    
    .speaker-avatars {
        display: flex;
        align-items: center;
    }
    
    .speaker-avatars img,
    .speaker-avatars div {
        transition: transform 0.2s;
        border: 2px solid white;
    }
    
    .speaker-avatars img:hover,
    .speaker-avatars div:hover {
        transform: scale(1.1);
        z-index: 10;
    }
    
    .btn-group .btn {
        transition: all 0.2s;
        background: white;
        padding: 0.25rem 0.5rem;
        border-radius: 6px !important;
    }
    
    .btn-group .btn:hover {
        transform: translateY(-2px);
        background: #e6f0fa;
        border-color: #0A3D62 !important;
    }
    
    .table > :not(caption) > * > * {
        padding: 1rem 0.75rem;
        vertical-align: middle;
    }
    
    .table > thead > tr > th {
        padding: 0.75rem;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e6f0fa;
    }
    
    .badge {
        font-weight: 500;
        padding: 0.35rem 0.65rem;
        border-radius: 20px;
    }
    
    .pagination {
        margin-bottom: 0;
        gap: 0.25rem;
    }
    
    .page-link {
        border: 1px solid #e6f0fa;
        color: #0A3D62;
        border-radius: 6px;
        padding: 0.375rem 0.75rem;
    }
    
    .page-link:hover {
        background: #e6f0fa;
        color: #0A3D62;
        border-color: #0A3D62;
    }
    
    .page-item.active .page-link {
        background: #0A3D62;
        border-color: #0A3D62;
        color: white;
    }
    
    @media (max-width: 768px) {
        .stat-card .stat-number {
            font-size: 1.5rem;
        }
        
        .stat-card .stat-icon {
            font-size: 2rem;
        }
        
        .ju-card-body {
            padding: 1rem;
        }
        
        .btn-group {
            flex-wrap: wrap;
            gap: 0.25rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const typeFilter = document.getElementById('typeFilter');
        const campusFilter = document.getElementById('campusFilter');
        const speakerFilter = document.getElementById('speakerFilter');
        const applyFilters = document.getElementById('applyFilters');
        
        applyFilters.addEventListener('click', function() {
            const params = new URLSearchParams();
            
            if (searchInput.value) {
                params.set('search', searchInput.value);
            }
            
            if (statusFilter.value) {
                params.set('status', statusFilter.value);
            }
            
            if (typeFilter.value) {
                params.set('event_type', typeFilter.value);
            }
            
            if (campusFilter.value) {
                params.set('campus_id', campusFilter.value);
            }
            
            if (speakerFilter.value) {
                params.set('speaker_id', speakerFilter.value);
            }
            
            window.location.href = '{{ route("admin.events.index") }}?' + params.toString();
        });
        
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                applyFilters.click();
            }
        });
        
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Add ripple effect to buttons
        const buttons = document.querySelectorAll('.btn');
        buttons.forEach(button => {
            button.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                ripple.classList.add('ripple');
                this.appendChild(ripple);
                
                const x = e.clientX - e.target.offsetLeft;
                const y = e.clientY - e.target.offsetTop;
                
                ripple.style.left = `${x}px`;
                ripple.style.top = `${y}px`;
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });
    });
</script>
@endpush