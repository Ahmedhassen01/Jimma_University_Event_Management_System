{{-- resources/views/admin/dashboard/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Welcome back, {{ auth()->user()->name }}!')

@section('breadcrumb-items')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<!-- Stats Cards Row -->
<div class="row g-3 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-calendar-alt fa-2x text-primary"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Total Events</h6>
                        <h3 class="mb-0 fw-bold">{{ number_format($totalEvents) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-users fa-2x text-success"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Total Users</h6>
                        <h3 class="mb-0 fw-bold">{{ number_format($totalUsers) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-user-check fa-2x text-warning"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Registrations</h6>
                        <h3 class="mb-0 fw-bold">{{ number_format($totalRegistrations) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-info bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-star fa-2x text-info"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Total Feedback</h6>
                        <h3 class="mb-0 fw-bold">{{ number_format($totalFeedback) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-line text-primary me-2"></i>
                    Registrations (Last 7 Days)
                </h5>
            </div>
            <div class="card-body">
                <canvas id="registrationsChart" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-pie text-primary me-2"></i>
                    Event Types
                </h5>
            </div>
            <div class="card-body">
                <canvas id="eventTypesChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Tables Row -->
<div class="row g-3">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calendar-alt text-primary me-2"></i>
                    Upcoming Events
                </h5>
                <a href="{{ route('admin.events.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-3">Event</th>
                                <th>Date</th>
                                <th>Venue</th>
                                <th class="text-end pe-3">Registrations</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($upcomingEvents as $event)
                            <tr>
                                <td class="ps-3">
                                    <span class="fw-semibold">{{ Str::limit($event->title, 30) }}</span>
                                </td>
                                <td>{{ $event->start_date->format('M d, Y') }}</td>
                                <td>{{ $event->venue ?? 'TBD' }}</td>
                                <td class="text-end pe-3">
                                    <span class="badge bg-primary">{{ $event->registered_attendees }}/{{ $event->max_attendees ?? '∞' }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    No upcoming events
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clock text-primary me-2"></i>
                    Recent Registrations
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-3">User</th>
                                <th>Event</th>
                                <th>Date</th>
                                <th class="text-end pe-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentRegistrations as $reg)
                            <tr>
                                <td class="ps-3">
                                    <span class="fw-semibold">{{ $reg->user->name ?? 'Unknown' }}</span>
                                </td>
                                <td>{{ Str::limit($reg->event->title ?? 'Unknown', 25) }}</td>
                                <td>{{ $reg->created_at->diffForHumans() }}</td>
                                <td class="text-end pe-3">
                                    @if($reg->status == 'confirmed')
                                        <span class="badge bg-success">Confirmed</span>
                                    @elseif($reg->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $reg->status }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    No recent registrations
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions Row -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm bg-primary text-white">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fas fa-bolt fa-2x"></i>
                        <span class="fw-semibold">Quick Actions</span>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.events.create') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-plus me-1"></i>New Event
                        </a>
                        <a href="{{ route('announcements.create') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-bullhorn me-1"></i>Announcement
                        </a>
                        <a href="{{ route('event-requests.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-clipboard-list me-1"></i>Requests
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Registrations Chart
    const ctx1 = document.getElementById('registrationsChart').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                label: 'Registrations',
                data: {!! json_encode($registrationsData) !!},
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                borderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Event Types Chart
    const ctx2 = document.getElementById('eventTypesChart').getContext('2d');
    const types = {!! json_encode($eventTypes) !!};
    
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: types.map(t => t.event_type.charAt(0).toUpperCase() + t.event_type.slice(1)),
            datasets: [{
                data: types.map(t => t.total),
                backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#0dcaf0'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
});
</script>
@endpush