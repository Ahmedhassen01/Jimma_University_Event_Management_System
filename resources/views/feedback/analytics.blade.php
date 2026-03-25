{{-- resources/views/feedback/analytics.blade.php --}}
@extends('layouts.app')

@section('title', 'Feedback Analytics')
@section('page-title', 'Feedback Analytics')

@section('content')
<style>
    .stat-card {
        border-radius: 15px;
        transition: transform 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
    }
    
    .chart-container {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }
    
    .analytics-header {
        background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
        color: white;
        padding: 30px 0;
        margin-bottom: 30px;
        border-radius: 15px;
    }
    
    .progress-bar-custom {
        height: 25px;
        border-radius: 12px;
        font-weight: 600;
    }
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="analytics-header">
        <div class="container">
            <h1 class="display-5 fw-bold mb-3">Feedback Analytics Dashboard</h1>
            <p class="lead opacity-90">Track and analyze feedback trends, ratings, and performance metrics</p>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stat-card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">TOTAL FEEDBACK</h6>
                            <h2 class="mb-0">{{ $total }}</h2>
                        </div>
                        <i class="fas fa-comments fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card stat-card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">WITH RATING</h6>
                            <h2 class="mb-0">{{ $withRating }}</h2>
                        </div>
                        <i class="fas fa-star fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card stat-card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">AVG RATING</h6>
                            <h2 class="mb-0">{{ $averageRating }}/5</h2>
                        </div>
                        <i class="fas fa-chart-line fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            @php
                $responseRate = $total > 0 ? round(($statusBreakdown['resolved'] / $total) * 100, 1) : 0;
            @endphp
            <div class="card stat-card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">RESOLUTION RATE</h6>
                            <h2 class="mb-0">{{ $responseRate }}%</h2>
                        </div>
                        <i class="fas fa-reply fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="chart-container">
                <h5 class="mb-3"><i class="fas fa-chart-pie me-2"></i>Feedback by Status</h5>
                <div class="mb-4">
                    @foreach($statusBreakdown as $status => $count)
                        @php
                            $percentage = $total > 0 ? round(($count / $total) * 100, 1) : 0;
                            $bgColor = match($status) {
                                'pending' => 'bg-warning',
                                'reviewed' => 'bg-info',
                                'resolved' => 'bg-success',
                                'closed' => 'bg-secondary',
                                default => 'bg-primary'
                            };
                        @endphp
                        <div class="mb-2">
                            <div class="d-flex justify-content-between">
                                <span class="text-capitalize">{{ $status }}</span>
                                <span>{{ $count }} ({{ $percentage }}%)</span>
                            </div>
                            <div class="progress progress-bar-custom">
                                <div class="progress-bar {{ $bgColor }}" 
                                     role="progressbar" 
                                     style="width: {{ $percentage }}%">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="chart-container">
                <h5 class="mb-3"><i class="fas fa-chart-bar me-2"></i>Feedback by Type</h5>
                <div class="mb-4">
                    @foreach($typeBreakdown as $type => $count)
                        @php
                            $percentage = $total > 0 ? round(($count / $total) * 100, 1) : 0;
                            $bgColor = match($type) {
                                'event' => 'bg-primary',
                                'system' => 'bg-info',
                                'general' => 'bg-success',
                                'suggestion' => 'bg-warning',
                                'complaint' => 'bg-danger',
                                default => 'bg-secondary'
                            };
                        @endphp
                        <div class="mb-2">
                            <div class="d-flex justify-content-between">
                                <span class="text-capitalize">{{ $type }}</span>
                                <span>{{ $count }} ({{ $percentage }}%)</span>
                            </div>
                            <div class="progress progress-bar-custom">
                                <div class="progress-bar {{ $bgColor }}" 
                                     role="progressbar" 
                                     style="width: {{ $percentage }}%">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Trend -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="chart-container">
                <h5 class="mb-3"><i class="fas fa-calendar-alt me-2"></i>Monthly Feedback Trend (Last 6 Months)</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Feedback Count</th>
                                <th>Average Rating</th>
                                <th>Progress</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monthlyTrend as $trend)
                                @php
                                    $monthName = \Carbon\Carbon::createFromFormat('Y-m', $trend->month)->format('F Y');
                                    $maxTrend = $monthlyTrend->max('count');
                                    $percentage = $maxTrend > 0 ? round(($trend->count / $maxTrend) * 100, 0) : 0;
                                @endphp
                                <tr>
                                    <td>{{ $monthName }}</td>
                                    <td>
                                        <strong>{{ $trend->count }}</strong> feedback
                                    </td>
                                    <td>
                                        @php
                                            $monthAvg = \App\Models\Feedback::whereNotNull('rating')
                                                ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$trend->month])
                                                ->avg('rating');
                                        @endphp
                                        @if($monthAvg)
                                            <div class="rating-stars d-inline">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= round($monthAvg))
                                                        <i class="fas fa-star text-warning"></i>
                                                    @else
                                                        <i class="far fa-star text-secondary"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span class="ms-2">{{ round($monthAvg, 1) }}/5</span>
                                        @else
                                            <span class="text-muted">No ratings</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-primary" 
                                                 role="progressbar" 
                                                 style="width: {{ $percentage }}%">
                                                {{ $percentage }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Analytics -->
    <div class="row">
        <!-- Top Rated Events -->
        <div class="col-md-6">
            <div class="chart-container">
                <h5 class="mb-3"><i class="fas fa-trophy me-2"></i>Top Rated Events</h5>
                @if($topRatedEvents->count() > 0)
                    <div class="list-group">
                        @foreach($topRatedEvents as $eventFeedback)
                            @if($eventFeedback->event)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $eventFeedback->event->title }}</h6>
                                            <small class="text-muted">
                                                {{ $eventFeedback->event->start_date->format('M d, Y') }}
                                            </small>
                                        </div>
                                        <div class="text-end">
                                            <div class="rating-stars">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= round($eventFeedback->avg_rating))
                                                        <i class="fas fa-star text-warning"></i>
                                                    @else
                                                        <i class="far fa-star text-secondary"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <strong>{{ round($eventFeedback->avg_rating, 1) }}/5</strong>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center py-3">
                        <i class="fas fa-star fa-2x mb-2"></i><br>
                        No rated events yet
                    </p>
                @endif
            </div>
        </div>
        
        <!-- Recent Feedback -->
        <div class="col-md-6">
            <div class="chart-container">
                <h5 class="mb-3"><i class="fas fa-history me-2"></i>Recent Feedback</h5>
                @if($recentFeedbacks->count() > 0)
                    <div class="list-group">
                        @foreach($recentFeedbacks as $feedback)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ $feedback->getSubmitterName() }}</h6>
                                        <small class="text-muted">
                                            {{ Str::limit($feedback->message, 50) }}
                                        </small>
                                        <br>
                                        <span class="badge bg-{{ $feedback->type == 'complaint' ? 'danger' : 'primary' }}">
                                            {{ ucfirst($feedback->type) }}
                                        </span>
                                        @if($feedback->rating)
                                            <span class="badge bg-warning ms-1">
                                                {{ $feedback->rating }}/5
                                            </span>
                                        @endif
                                    </div>
                                    <small class="text-muted">
                                        {{ $feedback->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center py-3">
                        <i class="fas fa-inbox fa-2x mb-2"></i><br>
                        No recent feedback
                    </p>
                @endif
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body text-center">
                    <div class="btn-group" role="group">
                        <a href="{{ route('feedback.admin.index') }}" class="btn btn-primary">
                            <i class="fas fa-list me-2"></i>View All Feedback
                        </a>
                        <a href="{{ route('feedback.admin.export') }}" class="btn btn-success">
                            <i class="fas fa-download me-2"></i>Export Data
                        </a>
                        <a href="{{ route('feedback.testimonials') }}" class="btn btn-info">
                            <i class="fas fa-eye me-2"></i>View Public Testimonials
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// You can add JavaScript charting libraries here if needed
// For example: Chart.js, ApexCharts, etc.

document.addEventListener('DOMContentLoaded', function() {
    // Simple animation for stat cards
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>
@endpush
@endsection