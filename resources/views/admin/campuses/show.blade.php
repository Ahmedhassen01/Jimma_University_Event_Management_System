@extends('layouts.app')

@section('title', $campus->name . ' - Campus Details')
@section('page-title', $campus->name)
@section('page-subtitle', 'Campus Details')

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.campuses.index') }}">Campuses</a></li>
    <li class="breadcrumb-item active">{{ $campus->name }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Campus Header -->
    <div class="ju-card mb-4">
        <div class="ju-card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="ju-card-title m-0">{{ $campus->name }}</h5>
                <div class="btn-group">
                    <a href="{{ route('admin.campuses.edit', $campus) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <a href="{{ route('admin.buildings.index') }}?campus_id={{ $campus->id }}" class="btn btn-info btn-sm">
                        <i class="fas fa-building me-1"></i> View Buildings
                    </a>
                </div>
            </div>
        </div>
        <div class="ju-card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong><i class="fas fa-map-marker-alt me-2"></i> Location:</strong>
                            <p class="mb-0">{{ $campus->location }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong><i class="fas fa-info-circle me-2"></i> Status:</strong>
                            <p class="mb-0">
                                @if($campus->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong><i class="fas fa-envelope me-2"></i> Contact Email:</strong>
                            <p class="mb-0">{{ $campus->contact_email ?? 'Not provided' }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong><i class="fas fa-phone me-2"></i> Contact Phone:</strong>
                            <p class="mb-0">{{ $campus->contact_phone ?? 'Not provided' }}</p>
                        </div>
                    </div>
                    
                    @if($campus->description)
                    <div class="mb-3">
                        <strong><i class="fas fa-align-left me-2"></i> Description:</strong>
                        <p class="mb-0">{{ $campus->description }}</p>
                    </div>
                    @endif
                </div>
                <div class="col-md-4">
                    <div class="card border-0 bg-light">
                        <div class="card-body text-center">
                            <i class="fas fa-university fa-3x text-primary mb-3"></i>
                            <h3>{{ $campus->buildings_count }}</h3>
                            <p class="mb-0">Buildings in this campus</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Buildings in this Campus -->
    <div class="ju-card">
        <div class="ju-card-header d-flex justify-content-between align-items-center">
            <h5 class="ju-card-title m-0">Buildings in {{ $campus->name }}</h5>
            <a href="{{ route('admin.buildings.create') }}?campus_id={{ $campus->id }}" class="btn btn-ju btn-sm">
                <i class="fas fa-plus me-1"></i> Add Building
            </a>
        </div>
        <div class="ju-card-body">
            @if($campus->buildings->count() > 0)
            <div class="table-responsive">
                <table class="table table-ju">
                    <thead>
                        <tr>
                            <th>Building Name</th>
                            <th>Code</th>
                            <th>Floors</th>
                            <th>Venues</th>
                            <th>Contact</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($campus->buildings as $building)
                        <tr>
                            <td>
                                <strong>{{ $building->name }}</strong>
                                @if($building->description)
                                <br><small class="text-muted">{{ Str::limit($building->description, 40) }}</small>
                                @endif
                            </td>
                            <td>{{ $building->code ?? 'N/A' }}</td>
                            <td>{{ $building->floors }}</td>
                            <td>
                                <span class="badge bg-info">{{ $building->venues_count ?? 0 }} venues</span>
                            </td>
                            <td>
                                @if($building->contact_person)
                                <div><small>{{ $building->contact_person }}</small></div>
                                @endif
                                @if($building->contact_phone)
                                <div><small>{{ $building->contact_phone }}</small></div>
                                @endif
                            </td>
                            <td>
                                @if($building->is_active)
                                <span class="badge bg-success">Active</span>
                                @else
                                <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.buildings.show', $building) }}" class="btn btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.buildings.edit', $building) }}" class="btn btn-warning">
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
                <i class="fas fa-building fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No buildings in this campus</h5>
                <p>Add buildings to organize venues and halls</p>
                <a href="{{ route('admin.buildings.create') }}?campus_id={{ $campus->id }}" class="btn btn-ju">
                    <i class="fas fa-plus me-1"></i> Add Building
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection     