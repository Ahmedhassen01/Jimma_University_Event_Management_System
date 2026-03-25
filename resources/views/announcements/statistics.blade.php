@extends('layouts.app')

@section('title', 'Announcement Statistics - Jimma University')
@section('page-title', 'Announcement Statistics')
@section('page-subtitle', 'Analytics & Insights for University Announcements')

@section('breadcrumb-items')
<li class="breadcrumb-item"><a href="{{ route('announcements.index') }}">Announcements</a></li>
<li class="breadcrumb-item active">Statistics</li>
@endsection

@section('content')
<style>
    /* ============================================
       DISTINCT COLORFUL STAT CARDS - ALL DIFFERENT
       Each card has unique, vibrant color scheme
       Perfect visibility and contrast
    ============================================ */
    .stat-card-lg {
        padding: 2rem;
        min-height: 180px;
        color: white;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-lg);
        transition: all var(--transition-bounce);
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: center;
        border: none;
    }
    
    .stat-card-lg:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-2xl);
    }
    
    /* Card 1: Royal Purple - Total Announcements */
    .stat-card-lg.card-1 {
        background: linear-gradient(145deg, #6b46c1 0%, #553c9a 100%);
        border-bottom: 5px solid #fbbf24;
    }
    .stat-card-lg.card-1::after {
        background: radial-gradient(circle at top right, rgba(251, 191, 36, 0.2), transparent 70%);
    }
    
    /* Card 2: Teal Green - Published */
    .stat-card-lg.card-2 {
        background: linear-gradient(145deg, #0f766e 0%, #115e59 100%);
        border-bottom: 5px solid #fcd34d;
    }
    .stat-card-lg.card-2::after {
        background: radial-gradient(circle at bottom left, rgba(252, 211, 77, 0.2), transparent 70%);
    }
    
    /* Card 3: Crimson Red - Active */
    .stat-card-lg.card-3 {
        background: linear-gradient(145deg, #b91c1c 0%, #991b1b 100%);
        border-bottom: 5px solid #fde047;
    }
    .stat-card-lg.card-3::after {
        background: radial-gradient(circle at top left, rgba(253, 224, 71, 0.2), transparent 70%);
    }
    
    /* Card 4: Electric Blue - Total Views */
    .stat-card-lg.card-4 {
        background: linear-gradient(145deg, #2563eb 0%, #1d4ed8 100%);
        border-bottom: 5px solid #bef264;
    }
    .stat-card-lg.card-4::after {
        background: radial-gradient(circle at bottom right, rgba(190, 242, 100, 0.2), transparent 70%);
    }
    
    .stat-number-lg {
        font-size: 3.5rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        line-height: 1;
        text-shadow: 0 4px 8px rgba(0,0,0,0.2);
        color: white;
        position: relative;
        z-index: 2;
    }
    
    .stat-label-lg {
        font-size: 1.2rem;
        font-weight: 700;
        opacity: 1;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        color: white;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 2;
        text-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    
    .stat-card-lg .d-flex {
        color: white;
        position: relative;
        z-index: 2;
    }
    
    .stat-card-lg i {
        color: rgba(255,255,255,0.95);
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
    }
    
    .stat-card-lg small {
        color: white;
        font-weight: 600;
        text-shadow: 0 1px 2px rgba(0,0,0,0.2);
    }
    
    .percentage-badge {
        font-size: 0.85rem;
        padding: 0.35rem 0.85rem;
        border-radius: 50px;
        font-weight: 700;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        position: relative;
        z-index: 2;
    }
    
    .stat-card-lg .percentage-badge {
        background: rgba(255,255,255,0.25) !important;
        color: white !important;
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255,255,255,0.3);
    }
    
    /* Background pattern overlay for stat cards */
    .stat-card-lg::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        opacity: 0.1;
        pointer-events: none;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 0l30 30-30 30L0 30z' fill='%23ffffff' fill-opacity='0.1'/%3E%3C/svg%3E");
        background-size: 30px 30px;
    }
    
    /* Chart Container */
    .chart-container {
        background: white;
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        box-shadow: var(--shadow-md);
        height: 100%;
        border: 1px solid var(--main-border);
    }
    
    /* Table Stats */
    .table-stats td {
        vertical-align: middle;
        padding: 1rem;
    }
    
    .progress-thin {
        height: 8px;
        border-radius: 50px;
        background-color: #e9ecef;
    }
    
    .progress-bar {
        border-radius: 50px;
    }
    
    /* Type Badge Colors - Different from cards */
    .badge.bg-urgent { background: linear-gradient(145deg, #dc2626, #b91c1c) !important; }
    .badge.bg-event { background: linear-gradient(145deg, #7c3aed, #6d28d9) !important; }
    .badge.bg-campus { background: linear-gradient(145deg, #059669, #047857) !important; }
    .badge.bg-general { background: linear-gradient(145deg, #2563eb, #1d4ed8) !important; }
    .badge.bg-info { background: linear-gradient(145deg, #0891b2, #0e7490) !important; }
    .badge.bg-warning { background: linear-gradient(145deg, #d97706, #b45309) !important; color: white !important; }
    .badge.bg-danger { background: linear-gradient(145deg, #dc2626, #b91c1c) !important; }
    .badge.bg-success { background: linear-gradient(145deg, #059669, #047857) !important; }
    .badge.bg-primary { background: linear-gradient(145deg, #4f46e5, #4338ca) !important; }
    .badge.bg-secondary { background: linear-gradient(145deg, #4b5563, #374151) !important; }
    
    /* List Group Items */
    .list-group-item {
        border: 1px solid var(--main-border);
        transition: all var(--transition);
    }
    
    .list-group-item:hover {
        background-color: #f8fafc;
        transform: translateX(5px);
        border-left: 3px solid #4f46e5;
    }
    
    /* Trend Indicators */
    .trend-up { 
        color: #059669;
        font-weight: 600;
    }
    .trend-down { 
        color: #dc2626;
        font-weight: 600;
    }
    
    /* Print Styles */
    @media print {
        .stat-card-lg {
            background: #f8f9fa !important;
            color: black !important;
            border: 1px solid #ddd !important;
            box-shadow: none !important;
        }
        
        .stat-number-lg, .stat-label-lg, .stat-card-lg .d-flex {
            color: black !important;
        }
        
        .percentage-badge {
            background: #e9ecef !important;
            color: black !important;
            border: 1px solid #ced4da !important;
        }
    }
</style>

<div class="container-fluid">
    <!-- Main Stats - ALL DIFFERENT COLORS - NOW VISIBLE -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card-lg card-1">
                <div class="stat-number-lg">{{ number_format($totalAnnouncements) }}</div>
                <div class="stat-label-lg">Total Announcements</div>
                <div class="d-flex align-items-center mt-2">
                    <i class="fas fa-bullhorn me-2 fa-lg"></i>
                    <small class="fw-semibold">All time</small>
                </div>
                <div class="mt-2">
                    <span class="percentage-badge bg-white">
                        <i class="fas fa-chart-bar me-1"></i>100%
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card-lg card-2">
                <div class="stat-number-lg">{{ number_format($publishedAnnouncements) }}</div>
                <div class="stat-label-lg">Published</div>
                <div class="d-flex align-items-center mt-2">
                    @php
                        $publishRate = $totalAnnouncements > 0 ? ($publishedAnnouncements / $totalAnnouncements) * 100 : 0;
                    @endphp
                    <i class="fas fa-check-circle me-2 fa-lg"></i>
                    <small class="fw-semibold">{{ round($publishRate, 1) }}% Publish Rate</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card-lg card-3">
                <div class="stat-number-lg">{{ number_format($activeAnnouncements) }}</div>
                <div class="stat-label-lg">Active</div>
                <div class="d-flex align-items-center mt-2">
                    @php
                        $activeRate = $publishedAnnouncements > 0 ? ($activeAnnouncements / $publishedAnnouncements) * 100 : 0;
                    @endphp
                    <i class="fas fa-play-circle me-2 fa-lg"></i>
                    <small class="fw-semibold">{{ round($activeRate, 1) }}% Active Rate</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card-lg card-4">
                <div class="stat-number-lg">{{ number_format($totalViews) }}</div>
                <div class="stat-label-lg">Total Views</div>
                <div class="d-flex align-items-center mt-2">
                    @php
                        $avgViews = $totalAnnouncements > 0 ? $totalViews / $totalAnnouncements : 0;
                    @endphp
                    <i class="fas fa-eye me-2 fa-lg"></i>
                    <small class="fw-semibold">{{ round($avgViews, 1) }} avg views</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Detailed Stats -->
    <div class="row mb-4">
        <!-- By Type Chart -->
        <div class="col-lg-6 mb-4">
            <div class="ju-card">
                <div class="ju-card-header">
                    <h5 class="ju-card-title">
                        <i class="fas fa-chart-pie me-2" style="color: #7c3aed;"></i>Announcements by Type
                    </h5>
                </div>
                <div class="ju-card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-stats">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Count</th>
                                    <th>Percentage</th>
                                    <th>Total Views</th>
                                    <th>Avg Views</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($byType as $type)
                                @php
                                    $percentage = $totalAnnouncements > 0 ? ($type->count / $totalAnnouncements) * 100 : 0;
                                    $avgViews = $type->count > 0 ? $type->total_views / $type->count : 0;
                                    $typeColor = $type->type == 'urgent' ? 'danger' : ($type->type == 'event' ? 'primary' : ($type->type == 'campus' ? 'success' : 'info'));
                                    $typeBgClass = $type->type == 'urgent' ? 'bg-danger' : ($type->type == 'event' ? 'bg-primary' : ($type->type == 'campus' ? 'bg-success' : 'bg-info'));
                                @endphp
                                <tr>
                                    <td>
                                        <span class="badge {{ $typeBgClass }}" style="padding: 0.5rem 1rem;">
                                            <i class="fas fa-{{ $type->type == 'urgent' ? 'exclamation-circle' : ($type->type == 'event' ? 'calendar' : ($type->type == 'campus' ? 'university' : 'info-circle')) }} me-1"></i>
                                            {{ ucfirst($type->type) }}
                                        </span>
                                    </td>
                                    <td class="fw-bold">{{ $type->count }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress progress-thin w-100 me-2" style="background-color: #e9ecef;">
                                                <div class="progress-bar bg-{{ $typeColor }}" 
                                                     style="width: {{ $percentage }}%;"></div>
                                            </div>
                                            <span class="fw-semibold">{{ round($percentage, 1) }}%</span>
                                        </div>
                                    </td>
                                    <td>{{ number_format($type->total_views) }}</td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-eye me-1"></i>{{ round($avgViews, 1) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- By Audience Chart -->
        <div class="col-lg-6 mb-4">
            <div class="ju-card">
                <div class="ju-card-header">
                    <h5 class="ju-card-title">
                        <i class="fas fa-users me-2" style="color: #0891b2;"></i>Announcements by Audience
                    </h5>
                </div>
                <div class="ju-card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-stats">
                            <thead>
                                <tr>
                                    <th>Audience</th>
                                    <th>Count</th>
                                    <th>Percentage</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($byAudience as $audience)
                                @php
                                    $percentage = $totalAnnouncements > 0 ? ($audience->count / $totalAnnouncements) * 100 : 0;
                                    $audienceLabels = [
                                        'all' => 'Everyone',
                                        'students' => 'Students Only',
                                        'faculty' => 'Faculty Only',
                                        'staff' => 'Staff Only',
                                        'specific' => 'Specific Users'
                                    ];
                                    $audienceIcons = [
                                        'all' => 'globe',
                                        'students' => 'user-graduate',
                                        'faculty' => 'chalkboard-teacher',
                                        'staff' => 'user-tie',
                                        'specific' => 'user-tag'
                                    ];
                                @endphp
                                <tr>
                                    <td>
                                        <span class="badge bg-info" style="padding: 0.5rem 1rem;">
                                            <i class="fas fa-{{ $audienceIcons[$audience->audience] ?? 'users' }} me-1"></i>
                                            {{ $audienceLabels[$audience->audience] ?? ucfirst($audience->audience) }}
                                        </span>
                                    </td>
                                    <td class="fw-bold">{{ $audience->count }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress progress-thin w-100 me-2" style="background-color: #e9ecef;">
                                                <div class="progress-bar bg-info" style="width: {{ $percentage }}%;"></div>
                                            </div>
                                            <span class="fw-semibold">{{ round($percentage, 1) }}%</span>
                                        </div>
                                    </td>
                                    <td>
                                        @switch($audience->audience)
                                            @case('all')
                                                Available to all university members
                                                @break
                                            @case('students')
                                                Targeted to students only
                                                @break
                                            @case('faculty')
                                                Targeted to faculty members
                                                @break
                                            @case('staff')
                                                Targeted to staff members
                                                @break
                                            @default
                                                Targeted to specific individuals
                                        @endswitch
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Most Viewed & Recent Announcements -->
    <div class="row">
        <!-- Most Viewed -->
        <div class="col-lg-6 mb-4">
            <div class="ju-card">
                <div class="ju-card-header">
                    <h5 class="ju-card-title">
                        <i class="fas fa-fire me-2" style="color: #ea580c;"></i>Top 10 Most Viewed Announcements
                    </h5>
                </div>
                <div class="ju-card-body">
                    <div class="list-group">
                        @forelse($mostViewed as $index => $announcement)
                        <a href="{{ route('announcements.show', $announcement) }}" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <span class="badge me-3" style="min-width: 30px; background: linear-gradient(145deg, #4f46e5, #4338ca);">{{ $index + 1 }}</span>
                                <div>
                                    <div class="fw-semibold">{{ Str::limit($announcement->title, 50) }}</div>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>{{ $announcement->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="badge me-2" style="background: linear-gradient(145deg, #d97706, #b45309);">
                                    <i class="fas fa-eye me-1"></i>{{ number_format($announcement->views) }}
                                </span>
                                <span class="badge" style="background: linear-gradient(145deg, {{ $announcement->type == 'urgent' ? '#dc2626, #b91c1c' : ($announcement->type == 'event' ? '#7c3aed, #6d28d9' : ($announcement->type == 'campus' ? '#059669, #047857' : '#2563eb, #1d4ed8')) }});">
                                    {{ ucfirst($announcement->type) }}
                                </span>
                            </div>
                        </a>
                        @empty
                        <div class="text-center py-5">
                            <i class="fas fa-chart-line fa-4x text-muted mb-3"></i>
                            <p class="text-muted fw-semibold">No viewed announcements yet</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Announcements -->
        <div class="col-lg-6 mb-4">
            <div class="ju-card">
                <div class="ju-card-header">
                    <h5 class="ju-card-title">
                        <i class="fas fa-history me-2" style="color: #6b46c1;"></i>Recent Announcements
                    </h5>
                </div>
                <div class="ju-card-body">
                    <div class="list-group">
                        @forelse($recentAnnouncements as $announcement)
                        <a href="{{ route('announcements.show', $announcement) }}" 
                           class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="fw-semibold">{{ Str::limit($announcement->title, 60) }}</div>
                                    <small class="text-muted">
                                        <i class="fas fa-user me-1"></i>{{ $announcement->creator->name ?? 'System' }}
                                    </small>
                                </div>
                                <div class="text-end">
                                    <div class="mb-1">
                                        <span class="badge" style="background: linear-gradient(145deg, {{ $announcement->type == 'urgent' ? '#dc2626, #b91c1c' : ($announcement->type == 'event' ? '#7c3aed, #6d28d9' : ($announcement->type == 'campus' ? '#059669, #047857' : '#2563eb, #1d4ed8')) }});">
                                            {{ ucfirst($announcement->type) }}
                                        </span>
                                    </div>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>{{ $announcement->created_at->format('M d, Y') }}
                                    </small>
                                </div>
                            </div>
                        </a>
                        @empty
                        <div class="text-center py-5">
                            <i class="fas fa-newspaper fa-4x text-muted mb-3"></i>
                            <p class="text-muted fw-semibold">No recent announcements</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Trends -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="ju-card">
                <div class="ju-card-header">
                    <h5 class="ju-card-title">
                        <i class="fas fa-chart-line me-2" style="color: #0f766e;"></i>Monthly Trends (Last 6 Months)
                    </h5>
                </div>
                <div class="ju-card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Announcements</th>
                                    <th>Total Views</th>
                                    <th>Avg Views per Announcement</th>
                                    <th>Trend</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $previousCount = null;
                                    $previousViews = null;
                                @endphp
                                @foreach($monthlyTrend as $month)
                                @php
                                    $avgViews = $month->count > 0 ? $month->total_views / $month->count : 0;
                                    $countTrend = $previousCount !== null ? 
                                        ($previousCount > 0 ? (($month->count - $previousCount) / $previousCount) * 100 : 0) : 0;
                                    $viewsTrend = $previousViews !== null ? 
                                        ($previousViews > 0 ? (($month->total_views - $previousViews) / $previousViews) * 100 : 0) : 0;
                                @endphp
                                <tr>
                                    <td class="fw-semibold">{{ date('F Y', strtotime($month->month . '-01')) }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="fw-bold me-2">{{ $month->count }}</span>
                                            @if($countTrend != 0)
                                            <span class="badge {{ $countTrend > 0 ? 'bg-success' : 'bg-danger' }}">
                                                <i class="fas fa-arrow-{{ $countTrend > 0 ? 'up' : 'down' }} me-1"></i>
                                                {{ abs(round($countTrend, 1)) }}%
                                            </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="fw-bold me-2">{{ number_format($month->total_views) }}</span>
                                            @if($viewsTrend != 0)
                                            <span class="badge {{ $viewsTrend > 0 ? 'bg-success' : 'bg-danger' }}">
                                                <i class="fas fa-arrow-{{ $viewsTrend > 0 ? 'up' : 'down' }} me-1"></i>
                                                {{ abs(round($viewsTrend, 1)) }}%
                                            </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            <i class="fas fa-eye me-1"></i>{{ round($avgViews, 1) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($countTrend > 10)
                                        <span class="text-success fw-semibold">
                                            <i class="fas fa-rocket me-1"></i>High Growth
                                        </span>
                                        @elseif($countTrend > 0)
                                        <span class="text-success fw-semibold">
                                            <i class="fas fa-arrow-up me-1"></i>Growing
                                        </span>
                                        @elseif($countTrend < -10)
                                        <span class="text-danger fw-semibold">
                                            <i class="fas fa-arrow-down me-1"></i>Declining
                                        </span>
                                        @elseif($countTrend < 0)
                                        <span class="text-warning fw-semibold">
                                            <i class="fas fa-minus me-1"></i>Stable
                                        </span>
                                        @else
                                        <span class="text-muted fw-semibold">
                                            <i class="fas fa-minus me-1"></i>Steady
                                        </span>
                                        @endif
                                    </td>
                                </tr>
                                @php
                                    $previousCount = $month->count;
                                    $previousViews = $month->total_views;
                                @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mt-2">
        <div class="col-12">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <div class="mb-2 mb-md-0">
                    <a href="{{ route('announcements.index') }}" class="btn btn-outline-secondary hover-lift">
                        <i class="fas fa-arrow-left me-2"></i>Back to Announcements
                    </a>
                    <a href="{{ route('announcements.create') }}" class="btn btn-outline-primary hover-lift ms-2">
                        <i class="fas fa-plus-circle me-2"></i>Create New Announcement
                    </a>
                </div>
                <div>
                    <button class="btn btn-outline-success hover-lift" onclick="printStatistics()">
                        <i class="fas fa-print me-2"></i>Print Report
                    </button>
                    <button class="btn btn-outline-info hover-lift ms-2" onclick="exportStatistics()">
                        <i class="fas fa-download me-2"></i>Export Data
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function printStatistics() {
    window.print();
}

function exportStatistics() {
    // Simple CSV export
    const data = [
        ['Jimma University - Announcement Statistics Report'],
        ['Generated on: ' + new Date().toLocaleString()],
        [],
        ['Metric', 'Value'],
        ['Total Announcements', '{{ $totalAnnouncements }}'],
        ['Published Announcements', '{{ $publishedAnnouncements }}'],
        ['Active Announcements', '{{ $activeAnnouncements }}'],
        ['Total Views', '{{ $totalViews }}'],
        [],
        ['Announcements by Type'],
        ['Type', 'Count', 'Percentage', 'Total Views', 'Average Views']
    ];
    
    @foreach($byType as $type)
        @php
            $percentage = $totalAnnouncements > 0 ? ($type->count / $totalAnnouncements) * 100 : 0;
            $avgViews = $type->count > 0 ? $type->total_views / $type->count : 0;
        @endphp
        data.push(['{{ ucfirst($type->type) }}', '{{ $type->count }}', '{{ round($percentage, 1) }}%', '{{ $type->total_views }}', '{{ round($avgViews, 1) }}']);
    @endforeach
    
    data.push([], ['Announcements by Audience'], ['Audience', 'Count', 'Percentage']);
    
    @foreach($byAudience as $audience)
        @php
            $percentage = $totalAnnouncements > 0 ? ($audience->count / $totalAnnouncements) * 100 : 0;
            $audienceLabels = [
                'all' => 'Everyone',
                'students' => 'Students Only',
                'faculty' => 'Faculty Only',
                'staff' => 'Staff Only',
                'specific' => 'Specific Users'
            ];
        @endphp
        data.push(['{{ $audienceLabels[$audience->audience] ?? ucfirst($audience->audience) }}', '{{ $audience->count }}', '{{ round($percentage, 1) }}%']);
    @endforeach
    
    // Convert to CSV
    const csvContent = data.map(row => 
        row.map(cell => `"${cell}"`).join(',')
    ).join('\n');
    
    // Create download link
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = `announcement-statistics-${new Date().toISOString().split('T')[0]}.csv`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    showToast('Statistics exported successfully!', 'success');
}

function showToast(message, type = 'success') {
    // Check if toast container exists, create if not
    let container = document.querySelector('.toast-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'toast-container';
        document.body.appendChild(container);
    }
    
    const toast = document.createElement('div');
    toast.className = `ju-toast ju-toast-${type} animate__animated animate__fadeInRight`;
    
    let icon = 'check-circle';
    let bgColor = '#28a745';
    if (type === 'error') { icon = 'exclamation-circle'; bgColor = '#dc3545'; }
    if (type === 'info') { icon = 'info-circle'; bgColor = '#17a2b8'; }
    if (type === 'warning') { icon = 'exclamation-triangle'; bgColor = '#ffc107'; }
    
    toast.innerHTML = `
        <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-${icon} fa-2x" style="color: ${bgColor};"></i>
            </div>
            <div class="flex-grow-1 ms-3">
                <div class="fw-bold mb-1">${type.charAt(0).toUpperCase() + type.slice(1)}</div>
                <div class="toast-message">${message}</div>
            </div>
            <button type="button" class="btn-close" onclick="this.closest('.ju-toast').remove()"></button>
        </div>
    `;
    
    container.appendChild(toast);
    
    setTimeout(() => {
        if (toast.parentNode) {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }
    }, 5000);
}

// Auto-hide toasts on page load
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        document.querySelectorAll('.ju-toast').forEach(toast => {
            toast.style.transition = 'opacity 0.5s ease';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 500);
        });
    }, 5000);
});
</script>

<style media="print">
    @media print {
        .ju-header, .ju-sidebar, .ju-footer, .btn, .dropdown,
        .action-buttons, .share-buttons, .toast-container,
        .hover-lift, .hover-scale, .hover-rotate {
            display: none !important;
        }
        
        .ju-main-content {
            padding: 0 !important;
            background: white !important;
            box-shadow: none !important;
            width: 100% !important;
            max-width: 100% !important;
        }
        
        .ju-card {
            border: 1px solid #ddd !important;
            box-shadow: none !important;
            margin-bottom: 20px !important;
            break-inside: avoid;
        }
        
        .stat-card-lg {
            background: #f8f9fa !important;
            color: black !important;
            border: 1px solid #ddd !important;
            box-shadow: none !important;
            break-inside: avoid;
            page-break-inside: avoid;
        }
        
        .stat-card-lg.card-1, .stat-card-lg.card-2, .stat-card-lg.card-3, .stat-card-lg.card-4 {
            background: #f8f9fa !important;
            border: 1px solid #ddd !important;
        }
        
        .stat-number-lg, .stat-label-lg, .stat-card-lg .d-flex,
        .stat-card-lg i, .stat-card-lg small {
            color: black !important;
        }
        
        .percentage-badge {
            background: #e9ecef !important;
            color: black !important;
            border: 1px solid #ced4da !important;
        }
        
        .list-group-item {
            border: 1px solid #ddd !important;
            margin-bottom: 5px !important;
            break-inside: avoid;
        }
        
        .table {
            break-inside: auto;
        }
        
        .table th {
            background-color: #f8f9fa !important;
            color: #000 !important;
            border: 1px solid #ddd !important;
        }
        
        .table td {
            border: 1px solid #ddd !important;
        }
        
        .badge {
            border: 1px solid #000 !important;
            background: #f8f9fa !important;
            color: #000 !important;
            background-image: none !important;
        }
        
        .progress-bar {
            background-color: #6c757d !important;
        }
        
        @page {
            margin: 1.5cm;
            size: landscape;
        }
        
        body {
            font-size: 11pt !important;
            line-height: 1.3 !important;
            background: white !important;
        }
        
        h1, h2, h3, h4, h5, h6 {
            page-break-after: avoid;
            color: black !important;
        }
        
        table, figure {
            page-break-inside: avoid;
        }
        
        tr, img {
            page-break-inside: avoid;
        }
        
        thead {
            display: table-header-group;
        }
        
        tfoot {
            display: table-footer-group;
        }
        
        a[href]:after {
            content: none !important;
        }
    }
</style>
@endpush