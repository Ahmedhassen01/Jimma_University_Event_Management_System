@extends('layouts.app')

@section('title', 'Campuses Management')
@section('page-title', 'Campuses Management')
@section('page-subtitle', 'Manage Jimma University campuses')

@section('breadcrumb-items')
    <li class="breadcrumb-item active">Campuses</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="stat-icon">
                    <i class="fas fa-university"></i>
                </div>
                <div class="stat-number">{{ $campuses->total() }}</div>
                <div class="stat-label">Total Campuses</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-number">{{ $activeCount }}</div>
                <div class="stat-label">Active Campuses</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card" style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);">
                <div class="stat-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-number">{{ $inactiveCount }}</div>
                <div class="stat-label">Inactive Campuses</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card" style="background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);">
                <div class="stat-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stat-number">{{ \App\Models\Building::count() }}</div>
                <div class="stat-label">Total Buildings</div>
            </div>
        </div>
    </div>

    <!-- Filter and Search Card -->
    <div class="ju-card mb-4">
        <div class="ju-card-header">
            <h5 class="ju-card-title">Filter Campuses</h5>
        </div>
        <div class="ju-card-body">
            <form method="GET" action="{{ route('admin.campuses.index') }}">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="search" class="form-label">Search</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="Search by name, location...">
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
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
                            <a href="{{ route('admin.campuses.index') }}" class="btn btn-ju-outline">
                                <i class="fas fa-redo me-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Campuses List Card -->
    <div class="ju-card">
        <div class="ju-card-header d-flex justify-content-between align-items-center">
            <h5 class="ju-card-title m-0">All Campuses</h5>
            <a href="{{ route('admin.campuses.create') }}" class="btn btn-ju">
                <i class="fas fa-plus me-1"></i> Add New Campus
            </a>
        </div>
        <div class="ju-card-body">
            @if($campuses->count() > 0)
            <div class="table-responsive">
                <table class="table table-ju data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Location</th>
                            <th>Buildings</th>
                            <th>Contact</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($campuses as $campus)
                        <tr>
                            <td>
                                <strong>{{ $campus->name }}</strong>
                                @if($campus->description)
                                <br><small class="text-muted">{{ Str::limit($campus->description, 50) }}</small>
                                @endif
                            </td>
                            <td>{{ $campus->location }}</td>
                            <td>
                                <span class="badge bg-info">{{ $campus->buildings_count }} buildings</span>
                            </td>
                            <td>
                                @if($campus->contact_email)
                                <div><i class="fas fa-envelope fa-fw"></i> {{ $campus->contact_email }}</div>
                                @endif
                                @if($campus->contact_phone)
                                <div><i class="fas fa-phone fa-fw"></i> {{ $campus->contact_phone }}</div>
                                @endif
                            </td>
                            <td>
                                @if($campus->is_active)
                                <span class="badge bg-success">Active</span>
                                @else
                                <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.campuses.show', $campus) }}" class="btn btn-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.campuses.edit', $campus) }}" class="btn btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.campuses.toggle-active', $campus) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="btn btn-{{ $campus->is_active ? 'secondary' : 'success' }}" title="{{ $campus->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="fas fa-{{ $campus->is_active ? 'times' : 'check' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.campuses.destroy', $campus) }}" method="POST" class="delete-form d-inline">
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
                <i class="fas fa-university fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No campuses found</h4>
                <p class="text-muted">Get started by adding your first campus</p>
                <a href="{{ route('admin.campuses.create') }}" class="btn btn-ju">
                    <i class="fas fa-plus me-1"></i> Add Campus
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection