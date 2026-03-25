@extends('layouts.app')

@section('title', $building->name . ' - Building Details')
@section('page-title', $building->name)
@section('page-subtitle', 'Building Details')

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.buildings.index') }}">Buildings</a></li>
    <li class="breadcrumb-item active">{{ $building->name }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Building Header -->
    <div class="ju-card mb-4">
        <div class="ju-card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="ju-card-title m-0">{{ $building->name }}</h5>
                <div class="btn-group">
                    <a href="{{ route('admin.buildings.edit', $building) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <a href="{{ route('admin.venues.create') }}?building_id={{ $building->id }}" class="btn btn-ju btn-sm">
                        <i class="fas fa-plus me-1"></i> Add Venue
                    </a>
                </div>
            </div>
        </div>
        <div class="ju-card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong><i class="fas fa-university me-2"></i> Campus:</strong>
                            <p class="mb-0">
                                <a href="{{ route('admin.campuses.show', $building->campus_id) }}" class="text-decoration-none">
                                    {{ $building->campus->name }}
                                </a>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <strong><i class="fas fa-info-circle me-2"></i> Status:</strong>
                            <p class="mb-0">
                                @if($building->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong><i class="fas fa-hashtag me-2"></i> Building Code:</strong>
                            <p class="mb-0">{{ $building->code ?? 'Not assigned' }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong><i class="fas fa-stairs me-2"></i> Floors:</strong>
                            <p class="mb-0">{{ $building->floors }}</p>
                        </div>
                    </div>
                    
                    @if($building->contact_person || $building->contact_phone)
                    <div class="row mb-3">
                        @if($building->contact_person)
                        <div class="col-md-6">
                            <strong><i class="fas fa-user me-2"></i> Contact Person:</strong>
                            <p class="mb-0">{{ $building->contact_person }}</p>
                        </div>
                        @endif
                        @if($building->contact_phone)
                        <div class="col-md-6">
                            <strong><i class="fas fa-phone me-2"></i> Contact Phone:</strong>
                            <p class="mb-0">{{ $building->contact_phone }}</p>
                        </div>
                        @endif
                    </div>
                    @endif
                    
                    @if($building->description)
                    <div class="mb-3">
                        <strong><i class="fas fa-align-left me-2"></i> Description:</strong>
                        <p class="mb-0">{{ $building->description }}</p>
                    </div>
                    @endif
                </div>
                <div class="col-md-4">
                    <div class="card border-0 bg-light">
                        <div class="card-body text-center">
                            <i class="fas fa-door-open fa-3x text-primary mb-3"></i>
                            <h3>{{ $building->venues_count }}</h3>
                            <p class="mb-0">Venues in this building</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Venues in this Building -->
    <div class="ju-card">
        <div class="ju-card-header d-flex justify-content-between align-items-center">
            <h5 class="ju-card-title m-0">Venues in {{ $building->name }}</h5>
            <a href="{{ route('admin.venues.create') }}?building_id={{ $building->id }}" class="btn btn-ju btn-sm">
                <i class="fas fa-plus me-1"></i> Add Venue
            </a>
        </div>
        <div class="ju-card-body">
            @if($building->venues->count() > 0)
            <div class="table-responsive">
                <table class="table table-ju">
                    <thead>
                        <tr>
                            <th>Venue Name</th>
                            <th>Type</th>
                            <th>Capacity</th>
                            <th>Amenities</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($building->venues as $venue)
                        <tr>
                            <td>
                                <strong>{{ $venue->name }}</strong>
                                @if($venue->description)
                                <br><small class="text-muted">{{ Str::limit($venue->description, 40) }}</small>
                                @endif
                            </td>
                            <td>
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
                                <span class="badge bg-info">{{ $venueTypes[$venue->type] ?? ucfirst($venue->type) }}</span>
                            </td>
                            <td>{{ $venue->capacity }} people</td>
                            <td>
                                @if($venue->amenities && count($venue->amenities) > 0)
                                    @foreach($venue->amenities as $amenity)
                                        <span class="badge bg-light text-dark border me-1 mb-1">{{ $amenity }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">No amenities</span>
                                @endif
                            </td>
                            <td>
                                @if($venue->is_available)
                                <span class="badge bg-success">Available</span>
                                @else
                                <span class="badge bg-secondary">Unavailable</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.venues.show', $venue) }}" class="btn btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.venues.edit', $venue) }}" class="btn btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-4">
                <i class="fas fa-door-open fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No venues in this building</h5>
                <p>Add venues like classrooms, halls, or auditoriums</p>
                <a href="{{ route('admin.venues.create') }}?building_id={{ $building->id }}" class="btn btn-ju">
                    <i class="fas fa-plus me-1"></i> Add Venue
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection