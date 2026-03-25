@extends('layouts.app')

@section('title', 'Buildings Management')
@section('page-title', 'Buildings Management')
@section('page-subtitle', 'Manage buildings across campuses')

@section('breadcrumb-items')
    <li class="breadcrumb-item active">Buildings</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="stat-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stat-number">{{ $buildings->total() }}</div>
                <div class="stat-label">Total Buildings</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-number">{{ $buildings->where('is_active', true)->count() }}</div>
                <div class="stat-label">Active Buildings</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card" style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);">
                <div class="stat-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-number">{{ $buildings->where('is_active', false)->count() }}</div>
                <div class="stat-label">Inactive Buildings</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card" style="background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);">
                <div class="stat-icon">
                    <i class="fas fa-door-open"></i>
                </div>
                <div class="stat-number">{{ \App\Models\Venue::count() }}</div>
                <div class="stat-label">Total Venues</div>
            </div>
        </div>
    </div>

    <!-- Filter and Search Card -->
    <div class="ju-card mb-4">
        <div class="ju-card-header">
            <h5 class="ju-card-title">Filter Buildings</h5>
        </div>
        <div class="ju-card-body">
            <form method="GET" action="{{ route('admin.buildings.index') }}">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="search" class="form-label">Search</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="Search by name, code...">
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="campus_id" class="form-label">Campus</label>
                        <select class="form-control" id="campus_id" name="campus_id">
                            <option value="">All Campuses</option>
                            @foreach($campuses as $campus)
                            <option value="{{ $campus->id }}" {{ request('campus_id') == $campus->id ? 'selected' : '' }}>
                                {{ $campus->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <div class="d-grid gap-2 w-100">
                            <button type="submit" class="btn btn-ju">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <a href="{{ route('admin.buildings.index') }}" class="btn btn-ju-outline">
                                <i class="fas fa-redo me-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Buildings List Card -->
    <div class="ju-card">
        <div class="ju-card-header d-flex justify-content-between align-items-center">
            <h5 class="ju-card-title m-0">All Buildings</h5>
            <a href="{{ route('admin.buildings.create') }}" class="btn btn-ju">
                <i class="fas fa-plus me-1"></i> Add New Building
            </a>
        </div>
        <div class="ju-card-body">
            @if($buildings->count() > 0)
            <div class="table-responsive">
                <table class="table table-ju data-table">
                    <thead>
                        <tr>
                            <th>Building</th>
                            <th>Campus</th>
                            <th>Code</th>
                            <th>Floors</th>
                            <th>Venues</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($buildings as $building)
                        <tr>
                            <td>
                                <strong>{{ $building->name }}</strong>
                                @if($building->description)
                                <br><small class="text-muted">{{ Str::limit($building->description, 40) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $building->campus->name }}</span>
                            </td>
                            <td>{{ $building->code ?? 'N/A' }}</td>
                            <td>{{ $building->floors }}</td>
                            <td>
                                <span class="badge bg-primary">{{ $building->venues_count }} venues</span>
                            </td>
                            <td>
                                @if($building->is_active)
                                <span class="badge bg-success">Active</span>
                                @else
                                <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.buildings.show', $building) }}" class="btn btn-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.buildings.edit', $building) }}" class="btn btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.buildings.toggle-active', $building) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="btn btn-{{ $building->is_active ? 'secondary' : 'success' }}" title="{{ $building->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="fas fa-{{ $building->is_active ? 'times' : 'check' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.buildings.destroy', $building) }}" method="POST" class="delete-form d-inline">
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
                <i class="fas fa-building fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No buildings found</h4>
                <p class="text-muted">Get started by adding your first building</p>
                <a href="{{ route('admin.buildings.create') }}" class="btn btn-ju">
                    <i class="fas fa-plus me-1"></i> Add Building
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection