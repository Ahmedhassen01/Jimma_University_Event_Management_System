@extends('layouts.app')

@section('title', 'Venues Management')
@section('page-title', 'Venues Management')
@section('page-subtitle', 'Manage halls, classrooms, and auditoriums')

@section('breadcrumb-items')
    <li class="breadcrumb-item active">Venues</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="stat-icon">
                    <i class="fas fa-door-open"></i>
                </div>
                <div class="stat-number">{{ $venues->total() }}</div>
                <div class="stat-label">Total Venues</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-number">{{ $venues->where('is_available', true)->count() }}</div>
                <div class="stat-label">Available Venues</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card" style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);">
                <div class="stat-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-number">{{ $venues->where('is_available', false)->count() }}</div>
                <div class="stat-label">Unavailable Venues</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card" style="background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number">{{ number_format($venues->sum('capacity')) }}</div>
                <div class="stat-label">Total Capacity</div>
            </div>
        </div>
    </div>

    <!-- Filter and Search Card -->
    <div class="ju-card mb-4">
        <div class="ju-card-header">
            <h5 class="ju-card-title">Filter Venues</h5>
        </div>
        <div class="ju-card-body">
            <form method="GET" action="{{ route('admin.venues.index') }}">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="search" class="form-label">Search</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="Search by name, description...">
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="campus_id" class="form-label">Campus</label>
                        <select class="form-control select2" id="campus_id" name="campus_id">
                            <option value="">All Campuses</option>
                            @foreach($campuses as $campus)
                            <option value="{{ $campus->id }}" {{ request('campus_id') == $campus->id ? 'selected' : '' }}>
                                {{ $campus->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="building_id" class="form-label">Building</label>
                        <select class="form-control select2" id="building_id" name="building_id">
                            <option value="">All Buildings</option>
                            @foreach($buildings as $building)
                            <option value="{{ $building->id }}" {{ request('building_id') == $building->id ? 'selected' : '' }}>
                                {{ $building->name }} ({{ $building->campus->name }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <div class="d-grid gap-2 w-100">
                            <button type="submit" class="btn btn-ju">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <a href="{{ route('admin.venues.index') }}" class="btn btn-ju-outline">
                                <i class="fas fa-redo me-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="type" class="form-label">Venue Type</label>
                        <select class="form-control" id="type" name="type">
                            <option value="">All Types</option>
                            @foreach($venueTypes as $key => $value)
                            <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="availability" class="form-label">Availability</label>
                        <select class="form-control" id="availability" name="availability">
                            <option value="">All</option>
                            <option value="1" {{ request('availability') == '1' ? 'selected' : '' }}>Available</option>
                            <option value="0" {{ request('availability') == '0' ? 'selected' : '' }}>Unavailable</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="min_capacity" class="form-label">Minimum Capacity</label>
                        <input type="number" class="form-control" id="min_capacity" name="min_capacity" 
                               value="{{ request('min_capacity') }}" placeholder="e.g., 50" min="1">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Venues List Card -->
    <div class="ju-card">
        <div class="ju-card-header d-flex justify-content-between align-items-center">
            <h5 class="ju-card-title m-0">All Venues</h5>
            <a href="{{ route('admin.venues.create') }}" class="btn btn-ju">
                <i class="fas fa-plus me-1"></i> Add New Venue
            </a>
        </div>
        <div class="ju-card-body">
            @if($venues->count() > 0)
            <div class="table-responsive">
                <table class="table table-ju data-table">
                    <thead>
                        <tr>
                            <th>Venue</th>
                            <th>Building & Campus</th>
                            <th>Type</th>
                            <th>Capacity</th>
                            <th>Amenities</th>
                            <th>Availability</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($venues as $venue)
                        <tr>
                            <td>
                                <strong>{{ $venue->name }}</strong>
                                @if($venue->description)
                                <br><small class="text-muted">{{ Str::limit($venue->description, 40) }}</small>
                                @endif
                            </td>
                            <td>
                                <div>{{ $venue->building->name }}</div>
                                <small class="text-muted">{{ $venue->building->campus->name }}</small>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $venueTypes[$venue->type] ?? ucfirst($venue->type) }}</span>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $venue->capacity }} people</span>
                            </td>
                            <td>
                                @if($venue->amenities && count($venue->amenities) > 0)
                                    @foreach(array_slice($venue->amenities, 0, 2) as $amenity)
                                        <span class="badge bg-light text-dark border me-1 mb-1">{{ $amenity }}</span>
                                    @endforeach
                                    @if(count($venue->amenities) > 2)
                                        <span class="badge bg-secondary">+{{ count($venue->amenities) - 2 }} more</span>
                                    @endif
                                @else
                                    <span class="text-muted">None</span>
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
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.venues.show', $venue) }}" class="btn btn-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.venues.edit', $venue) }}" class="btn btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.venues.toggle-availability', $venue) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="btn btn-{{ $venue->is_available ? 'secondary' : 'success' }}" title="{{ $venue->is_available ? 'Mark Unavailable' : 'Mark Available' }}">
                                            <i class="fas fa-{{ $venue->is_available ? 'times' : 'check' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.venues.destroy', $venue) }}" method="POST" class="delete-form d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Delete">
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
                <i class="fas fa-door-open fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No venues found</h4>
                <p class="text-muted">Get started by adding your first venue</p>
                <a href="{{ route('admin.venues.create') }}" class="btn btn-ju">
                    <i class="fas fa-plus me-1"></i> Add Venue
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .select2-container--bootstrap5 .select2-selection {
        min-height: 38px;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap5',
            width: '100%'
        });
        
        // Filter buildings based on selected campus
        $('#campus_id').change(function() {
            var campusId = $(this).val();
            var buildingSelect = $('#building_id');
            
            if (campusId) {
                // Disable building select while loading
                buildingSelect.prop('disabled', true);
                
                // Load buildings for selected campus
                // Load buildings for selected campus
$.ajax({
    url: '/admin/buildings/by-campus/' + campusId,
    method: 'GET',
    success: function(data) {
                        buildingSelect.empty();
                        buildingSelect.append('<option value="">All Buildings</option>');
                        
                        $.each(data, function(index, building) {
                            buildingSelect.append(
                                '<option value="' + building.id + '">' + 
                                building.name + '</option>'
                            );
                        });
                        
                        buildingSelect.prop('disabled', false);
                        buildingSelect.trigger('change');
                    }
                });
            } else {
                // Reset building select
                buildingSelect.empty();
                buildingSelect.append('<option value="">All Buildings</option>');
                @foreach($buildings as $building)
                buildingSelect.append(
                    '<option value="{{ $building->id }}" {{ request('building_id') == $building->id ? 'selected' : '' }}>' + 
                    '{{ $building->name }} ({{ $building->campus->name }})</option>'
                );
                @endforeach
            }
        });
    });
</script>
@endpush