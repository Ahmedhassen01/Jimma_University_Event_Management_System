{{-- resources/views/admin/registrations/statistics.blade.php --}}
@extends('layouts.app')

@section('title', 'Registration Statistics | Jimma University')
@section('page-title', 'Registration Statistics')
@section('page-subtitle', 'Analytics and insights for event registrations')

@section('breadcrumb-items')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.registrations.index') }}">Registrations</a></li>
<li class="breadcrumb-item active">Statistics</li>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold" style="color: #002789;">
                    <i class="fas fa-chart-pie me-2"></i>Registration Analytics Dashboard
                </h5>
            </div>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-2 col-sm-6 mb-3">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">Total</h6>
                        <h3 class="mb-0">{{ $totalRegistrations }}</h3>
                    </div>
                    <i class="fas fa-ticket-alt fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-sm-6 mb-3">
        <div class="card bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">Confirmed</h6>
                        <h3 class="mb-0">{{ $confirmedRegistrations }}</h3>
                        <small>{{ $totalRegistrations > 0 ? round(($confirmedRegistrations / $totalRegistrations) * 100, 1) : 0 }}%</small>
                    </div>
                    <i class="fas fa-check-circle fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-sm-6 mb-3">
        <div class="card bg-warning text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">Pending</h6>
                        <h3 class="mb-0">{{ $pendingRegistrations }}</h3>
                        <small>{{ $totalRegistrations > 0 ? round(($pendingRegistrations / $totalRegistrations) * 100, 1) : 0 }}%</small>
                    </div>
                    <i class="fas fa-clock fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-sm-6 mb-3">
        <div class="card bg-info text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">Waitlisted</h6>
                        <h3 class="mb-0">{{ $waitlistedRegistrations }}</h3>
                    </div>
                    <i class="fas fa-list-ol fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-sm-6 mb-3">
        <div class="card bg-danger text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">Cancelled</h6>
                        <h3 class="mb-0">{{ $cancelledRegistrations }}</h3>
                    </div>
                    <i class="fas fa-times-circle fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-sm-6 mb-3">
        <div class="card bg-secondary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">Attended</h6>
                        <h3 class="mb-0">{{ $attendedCount }}</h3>
                        <small>{{ $confirmedRegistrations > 0 ? round(($attendedCount / $confirmedRegistrations) * 100, 1) : 0 }}% attendance</small>
                    </div>
                    <i class="fas fa-user-check fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold" style="color: #002789;">
                    <i class="fas fa-chart-pie me-2"></i>Registration Status Distribution
                </h6>
            </div>
            <div class="card-body">
                <canvas id="statusChart" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold" style="color: #002789;">
                    <i class="fas fa-chart-line me-2"></i>Daily Registrations (Last 30 Days)
                </h6>
            </div>
            <div class="card-body">
                <canvas id="dailyChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Top Events Table -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold" style="color: #002789;">
                    <i class="fas fa-trophy me-2"></i>Top Events by Registration
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Event Title</th>
                                <th>Date</th>
                                <th>Confirmed Registrations</th>
                                <th>Capacity</th>
                                <th>Fill Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($registrationsByEvent as $event)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.events.show', $event) }}" class="text-decoration-none fw-bold">
                                        {{ Str::limit($event->title, 50) }}
                                    </a>
                                </td>
                                <td>{{ $event->start_date->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge bg-success">{{ $event->registrations_count }}</span>
                                </td>
                                <td>{{ $event->max_attendees ?? 'Unlimited' }}</td>
                                <td>
                                    @if($event->max_attendees)
                                        @php $fillRate = ($event->registrations_count / $event->max_attendees) * 100; @endphp
                                        <div class="d-flex align-items-center">
                                            <div class="progress w-75 me-2" style="height: 8px;">
                                                <div class="progress-bar {{ $fillRate >= 90 ? 'bg-danger' : ($fillRate >= 70 ? 'bg-warning' : 'bg-success') }}" 
                                                     style="width: {{ $fillRate }}%"></div>
                                            </div>
                                            <span>{{ round($fillRate, 1) }}%</span>
                                        </div>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <p class="text-muted mb-0">No registration data available</p>
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
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Status Distribution Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'pie',
            data: {
                labels: ['Confirmed', 'Pending', 'Cancelled', 'Waitlisted'],
                datasets: [{
                    data: [
                        {{ $confirmedRegistrations }},
                        {{ $pendingRegistrations }},
                        {{ $cancelledRegistrations }},
                        {{ $waitlistedRegistrations }}
                    ],
                    backgroundColor: [
                        '#28a745',
                        '#ffc107',
                        '#dc3545',
                        '#17a2b8'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Daily Registrations Chart
        const dailyCtx = document.getElementById('dailyChart').getContext('2d');
        new Chart(dailyCtx, {
            type: 'line',
            data: {
                labels: [
                    @foreach($dailyRegistrations as $daily)
                        '{{ $daily->date }}',
                    @endforeach
                ],
                datasets: [
                    {
                        label: 'Total Registrations',
                        data: [
                            @foreach($dailyRegistrations as $daily)
                                {{ $daily->total }},
                            @endforeach
                        ],
                        borderColor: '#002789',
                        backgroundColor: 'rgba(0, 39, 137, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Confirmed',
                        data: [
                            @foreach($dailyRegistrations as $daily)
                                {{ $daily->confirmed }},
                            @endforeach
                        ],
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Pending',
                        data: [
                            @foreach($dailyRegistrations as $daily)
                                {{ $daily->pending }},
                            @endforeach
                        ],
                        borderColor: '#ffc107',
                        backgroundColor: 'rgba(255, 193, 7, 0.1)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    });
</script>
@endpush