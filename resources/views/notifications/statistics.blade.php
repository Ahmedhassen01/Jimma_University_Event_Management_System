@extends('layouts.app')

@section('title', 'Notification Statistics - Jimma University Events')
@section('page-title', 'Notification Statistics')
@section('page-subtitle', 'Analytics and insights about notifications')

@section('breadcrumb-items')
    <li class="breadcrumb-item">
        <a href="{{ route('notifications.index') }}">Notifications</a>
    </li>
    <li class="breadcrumb-item active">Statistics</li>
@endsection

@section('content')
<!-- Date Filter -->
<div class="row mb-4">
    <div class="col-12">
        <div class="ju-card">
            <div class="ju-card-header">
                <h5 class="ju-card-title mb-0">
                    <i class="fas fa-filter me-2"></i>Filter Statistics
                </h5>
            </div>
            <div class="ju-card-body">
                <form method="GET" action="{{ route('notifications.statistics') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" 
                               value="{{ $startDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" 
                               value="{{ $endDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-ju">
                            <i class="fas fa-filter me-2"></i>Apply Filter
                        </button>
                        <a href="{{ route('notifications.statistics') }}" class="btn btn-outline-ju ms-2">
                            <i class="fas fa-times me-2"></i>Clear
                        </a>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <div class="alert alert-info p-2 mb-0 w-100">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Showing: {{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }}
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <!-- Total Notifications Sent -->
    <div class="col-md-3 mb-4">
        <div class="stat-card stat-card-success">
            <i class="fas fa-paper-plane stat-icon"></i>
            <div class="stat-number">{{ number_format($statistics['total_sent']) }}</div>
            <div class="stat-label">Total Sent</div>
            @if(isset($statistics['filtered_sent']) && $statistics['filtered_sent'] < $statistics['total_sent'])
                <small class="text-white opacity-75">
                    {{ number_format($statistics['filtered_sent']) }} in selected period
                </small>
            @endif
        </div>
    </div>
    
    <!-- Unique Recipients -->
    <div class="col-md-3 mb-4">
        <div class="stat-card stat-card-info">
            <i class="fas fa-users stat-icon"></i>
            <div class="stat-number">{{ number_format($statistics['unique_recipients']) }}</div>
            <div class="stat-label">Unique Recipients</div>
            <small class="text-white opacity-75">
                In selected period
            </small>
        </div>
    </div>
    
    <!-- High Priority -->
    <div class="col-md-3 mb-4">
        <div class="stat-card stat-card-warning">
            <i class="fas fa-exclamation-triangle stat-icon"></i>
            <div class="stat-number">{{ number_format($statistics['high_priority']) }}</div>
            <div class="stat-label">High Priority</div>
            @if(isset($statistics['filtered_high_priority']))
                <small class="text-white opacity-75">
                    {{ number_format($statistics['filtered_high_priority']) }} in period
                </small>
            @endif
        </div>
    </div>
    
    <!-- Scheduled -->
    <div class="col-md-3 mb-4">
        <div class="stat-card stat-card-purple">
            <i class="fas fa-clock stat-icon"></i>
            <div class="stat-number">{{ number_format($statistics['scheduled_count']) }}</div>
            <div class="stat-label">Scheduled</div>
            <small class="text-white opacity-75">
                Pending delivery
            </small>
        </div>
    </div>
</div>

<!-- Time-based Statistics -->
<div class="row mb-4">
    <div class="col-12">
        <div class="ju-card">
            <div class="ju-card-header">
                <h5 class="ju-card-title mb-0">
                    <i class="fas fa-chart-line me-2"></i>Recent Activity
                </h5>
            </div>
            <div class="ju-card-body">
                <div class="row">
                    <!-- Today -->
                    <div class="col-md-3 mb-3">
                        <div class="card border-0 bg-light">
                            <div class="card-body text-center">
                                <h3 class="text-ju-primary">{{ number_format($statistics['today_sent']) }}</h3>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-calendar-day me-2"></i>Today
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- This Week -->
                    <div class="col-md-3 mb-3">
                        <div class="card border-0 bg-light">
                            <div class="card-body text-center">
                                <h3 class="text-ju-primary">{{ number_format($statistics['weekly_sent']) }}</h3>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-calendar-week me-2"></i>This Week
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- This Month -->
                    <div class="col-md-3 mb-3">
                        <div class="card border-0 bg-light">
                            <div class="card-body text-center">
                                <h3 class="text-ju-primary">{{ number_format($statistics['monthly_sent']) }}</h3>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-calendar-alt me-2"></i>This Month
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Event Notifications -->
                    <div class="col-md-3 mb-3">
                        <div class="card border-0 bg-light">
                            <div class="card-body text-center">
                                <h3 class="text-ju-primary">{{ number_format($statistics['event_notifications']) }}</h3>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-calendar me-2"></i>Event Related
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Statistics -->
<div class="row">
    <div class="col-12">
        <div class="ju-card">
            <div class="ju-card-header">
                <h5 class="ju-card-title mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Detailed Statistics
                </h5>
            </div>
            <div class="ju-card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Metric</th>
                                <th>Total</th>
                                <th>In Selected Period</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <i class="fas fa-paper-plane text-ju-primary me-2"></i>
                                    Notifications Sent
                                </td>
                                <td class="fw-bold">{{ number_format($statistics['total_sent']) }}</td>
                                <td>{{ number_format($statistics['filtered_sent'] ?? 0) }}</td>
                                <td class="text-muted">Total notifications delivered</td>
                            </tr>
                            <tr>
                                <td>
                                    <i class="fas fa-users text-ju-primary me-2"></i>
                                    Unique Recipients
                                </td>
                                <td class="fw-bold">-</td>
                                <td>{{ number_format($statistics['unique_recipients']) }}</td>
                                <td class="text-muted">Unique users who received notifications</td>
                            </tr>
                            <tr>
                                <td>
                                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                    High Priority
                                </td>
                                <td class="fw-bold">{{ number_format($statistics['high_priority'] ?? 0) }}</td>
                                <td>{{ number_format($statistics['filtered_high_priority'] ?? 0) }}</td>
                                <td class="text-muted">Urgent & High priority notifications</td>
                            </tr>
                            <tr>
                                <td>
                                    <i class="fas fa-clock text-purple me-2"></i>
                                    Scheduled
                                </td>
                                <td class="fw-bold">{{ number_format($statistics['scheduled_count'] ?? 0) }}</td>
                                <td>-</td>
                                <td class="text-muted">Notifications scheduled for future delivery</td>
                            </tr>
                            <tr>
                                <td>
                                    <i class="fas fa-calendar text-info me-2"></i>
                                    Event Notifications
                                </td>
                                <td class="fw-bold">{{ number_format($statistics['event_notifications'] ?? 0) }}</td>
                                <td>{{ number_format($statistics['filtered_event_notifications'] ?? 0) }}</td>
                                <td class="text-muted">Notifications related to events</td>
                            </tr>
                            <tr>
                                <td>
                                    <i class="fas fa-bullhorn text-danger me-2"></i>
                                    Custom Notifications
                                </td>
                                <td class="fw-bold">-</td>
                                <td>{{ number_format($statistics['custom_notifications'] ?? 0) }}</td>
                                <td class="text-muted">Custom notifications sent</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="row mt-4">
    <div class="col-12">
        <div class="d-flex justify-content-between">
            <a href="{{ route('notifications.index') }}" class="btn btn-outline-ju">
                <i class="fas fa-arrow-left me-2"></i>Back to Notifications
            </a>
            <div>
                <button onclick="window.print()" class="btn btn-outline-ju me-2">
                    <i class="fas fa-print me-2"></i>Print Report
                </button>
                <a href="{{ route('notifications.send-custom') }}" class="btn btn-ju">
                    <i class="fas fa-paper-plane me-2"></i>Send New Notification
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.table th {
    background-color: rgba(0, 100, 0, 0.05);
    border-bottom: 2px solid var(--ju-primary);
}
</style>
@endpush