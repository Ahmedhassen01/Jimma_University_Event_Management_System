{{-- resources/views/events/guest/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Browse Events - Jimma University')
@section('page-title', 'Browse All Events')
@section('page-subtitle', 'Discover and join events across all Jimma University campuses')

@section('breadcrumb-items')
    <li class="breadcrumb-item">
        <a href="{{ route('home') }}" class="d-flex align-items-center text-decoration-none">
            <i class="fas fa-home me-2 text-primary"></i>Home
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('events.guest.dashboard') }}" class="d-flex align-items-center text-decoration-none">
            <i class="fas fa-calendar me-2 text-primary"></i>Events
        </a>
    </li>
    <li class="breadcrumb-item active">
        <span class="text-primary fw-semibold">Browse Events</span>
    </li>
@endsection

@section('content')
<style>
    :root {
        --ju-primary-blue: #003366;      /* Deep Jimma Blue */
        --ju-secondary-blue: #004080;    /* Rich Blue */
        --ju-accent-blue: #1E4B8C;       /* Vibrant Blue */
        --ju-light-blue: #E6F0FA;        /* Light Blue Background */
        --ju-lighter-blue: #F0F7FF;      /* Very Light Blue */
        --ju-border-blue: #B8D1E6;       /* Border Color */
        --ju-dark-blue: #002244;         /* Dark Navy Blue */
        --ju-gold: #FFC72C;              /* Gold Accent (kept for contrast) */
        --ju-card-shadow: 0 4px 12px rgba(0, 51, 102, 0.08);
        --ju-card-hover: 0 12px 28px rgba(0, 64, 128, 0.18);
        --ju-gradient: linear-gradient(135deg, #003366 0%, #004080 100%);
        --ju-gradient-light: linear-gradient(135deg, #E6F0FA 0%, #F0F7FF 100%);
        --ju-gradient-hover: linear-gradient(135deg, #004080 0%, #1E4B8C 100%);
        --ju-transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    }
    
    /* Clean Base Styles */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body {
        background: #F5F9FF;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    }
    
    /* Blue Theme Components */
    .text-primary {
        color: var(--ju-primary-blue) !important;
    }
    
    .bg-primary {
        background: var(--ju-primary-blue) !important;
    }
    
    .border-primary {
        border-color: var(--ju-primary-blue) !important;
    }
    
    .btn-primary {
        background: var(--ju-gradient);
        border: none;
        color: white;
        transition: var(--ju-transition);
    }
    
    .btn-primary:hover {
        background: var(--ju-gradient-hover);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 51, 102, 0.25);
    }
    
    .btn-outline-primary {
        border: 2px solid var(--ju-primary-blue);
        color: var(--ju-primary-blue);
        background: transparent;
        transition: var(--ju-transition);
    }
    
    .btn-outline-primary:hover {
        background: var(--ju-gradient);
        border-color: transparent;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 51, 102, 0.2);
    }
    
    /* Advanced Filter Bar - Blue Theme */
    .filter-card {
        background: white;
        border: none;
        border-radius: 16px;
        box-shadow: var(--ju-card-shadow);
        transition: var(--ju-transition);
        position: relative;
        overflow: hidden;
    }
    
    .filter-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--ju-gradient);
        border-radius: 4px 0 0 4px;
    }
    
    .filter-card:hover {
        box-shadow: var(--ju-card-hover);
    }
    
    .input-group-text {
        background: var(--ju-light-blue);
        border: 1px solid var(--ju-border-blue);
        color: var(--ju-primary-blue);
        transition: var(--ju-transition);
    }
    
    .form-control, .form-select {
        border: 1px solid var(--ju-border-blue);
        padding: 0.6rem 1rem;
        transition: var(--ju-transition);
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--ju-accent-blue);
        box-shadow: 0 0 0 3px rgba(0, 64, 128, 0.1);
        outline: none;
    }
    
    .form-control:hover, .form-select:hover {
        border-color: var(--ju-secondary-blue);
    }
    
    /* Category Buttons - Blue Theme */
    .category-btn {
        padding: 8px 20px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: var(--ju-transition);
        border: 2px solid transparent;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-outline-primary.category-btn {
        border-color: var(--ju-border-blue);
        color: var(--ju-primary-blue);
        background: white;
    }
    
    .btn-outline-primary.category-btn:hover {
        background: var(--ju-gradient);
        border-color: transparent;
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 51, 102, 0.25);
    }
    
    .btn-outline-primary.category-btn.active {
        background: var(--ju-gradient);
        color: white;
        border-color: transparent;
        box-shadow: 0 6px 16px rgba(0, 51, 102, 0.2);
    }
    
    .btn-outline-warning.category-btn:hover {
        background: linear-gradient(135deg, #FFB300, #FFA000);
        border-color: transparent;
        color: white;
        transform: translateY(-3px);
    }
    
    .btn-outline-success.category-btn:hover {
        background: linear-gradient(135deg, #2E7D32, #1B5E20);
        border-color: transparent;
        color: white;
        transform: translateY(-3px);
    }
    
    .btn-outline-info.category-btn:hover {
        background: linear-gradient(135deg, #0288D1, #01579B);
        border-color: transparent;
        color: white;
        transform: translateY(-3px);
    }
    
    .btn-outline-danger.category-btn:hover {
        background: linear-gradient(135deg, #C62828, #8B0000);
        border-color: transparent;
        color: white;
        transform: translateY(-3px);
    }
    
    .btn-outline-dark.category-btn:hover {
        background: linear-gradient(135deg, #424242, #212121);
        border-color: transparent;
        color: white;
        transform: translateY(-3px);
    }
    
    /* Enhanced Event Card - Blue Theme */
    .event-card {
        background: white;
        border: none !important;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--ju-card-shadow);
        transition: var(--ju-transition);
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
    }
    
    .event-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--ju-card-hover);
    }
    
    .event-card::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 0;
        background: var(--ju-gradient);
        opacity: 0;
        transition: var(--ju-transition);
    }
    
    .event-card:hover::after {
        height: 4px;
        opacity: 1;
    }
    
    .event-card .card-img-top {
        transition: var(--ju-transition);
        height: 200px;
        object-fit: cover;
    }
    
    .event-card:hover .card-img-top {
        transform: scale(1.05);
    }
    
    .event-card .card-body {
        padding: 1.5rem;
        flex: 1;
    }
    
    .event-card .card-title {
        color: var(--ju-primary-blue);
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 0.75rem;
        transition: var(--ju-transition);
        line-height: 1.4;
    }
    
    .event-card:hover .card-title {
        color: var(--ju-secondary-blue);
    }
    
    .event-card .card-text {
        color: #5A6A7E;
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 1rem;
    }
    
    /* Image Placeholder - Blue Theme */
    .event-card .bg-primary {
        background: var(--ju-gradient-light) !important;
        transition: var(--ju-transition);
    }
    
    .event-card:hover .bg-primary {
        background: var(--ju-light-blue) !important;
    }
    
    .event-card .fa-3x {
        color: var(--ju-primary-blue);
        opacity: 0.3;
        transition: var(--ju-transition);
    }
    
    .event-card:hover .fa-3x {
        color: var(--ju-accent-blue);
        opacity: 0.5;
        transform: scale(1.1);
    }
    
    /* Badges - Blue Theme */
    .badge {
        padding: 6px 12px;
        font-weight: 600;
        font-size: 0.75rem;
        border-radius: 20px;
        transition: var(--ju-transition);
    }
    
    .badge.bg-warning {
        background: var(--ju-gold) !important;
        color: #003366;
    }
    
    .badge.bg-danger {
        background: #DC3545 !important;
        animation: pulse-blue 1.5s infinite;
    }
    
    @keyframes pulse-blue {
        0% { box-shadow: 0 0 0 0 rgba(0, 64, 128, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(0, 64, 128, 0); }
        100% { box-shadow: 0 0 0 0 rgba(0, 64, 128, 0); }
    }
    
    /* Event Details Icons - Blue Theme */
    .event-card .text-muted i {
        color: var(--ju-primary-blue);
        transition: var(--ju-transition);
    }
    
    .event-card:hover .text-muted i {
        color: var(--ju-accent-blue);
        transform: scale(1.15);
    }
    
    /* Progress Bar - Blue Theme */
    .progress {
        background: var(--ju-light-blue);
        border-radius: 10px;
        overflow: hidden;
        height: 8px;
    }
    
    .progress-bar {
        background: var(--ju-gradient);
        transition: width 0.6s ease;
        position: relative;
        overflow: hidden;
    }
    
    .progress-bar::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        animation: shimmer 1.5s infinite;
    }
    
    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    
    .progress-bar.bg-danger {
        background: linear-gradient(135deg, #DC3545, #B02A37) !important;
    }
    
    .progress-bar.bg-warning {
        background: linear-gradient(135deg, #FFC107, #FFA000) !important;
    }
    
    /* Card Footer Buttons - Blue Theme */
    .event-card .btn-success {
        background: white;
        color: var(--ju-primary-blue);
        border: 2px solid var(--ju-primary-blue);
        padding: 0.5rem 1.25rem;
        font-weight: 600;
        transition: var(--ju-transition);
        position: relative;
        overflow: hidden;
        z-index: 1;
    }
    
    .event-card .btn-success::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 0%;
        height: 100%;
        background: var(--ju-gradient);
        transition: var(--ju-transition);
        z-index: -1;
    }
    
    .event-card .btn-success:hover {
        color: white;
        border-color: transparent;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 51, 102, 0.25);
    }
    
    .event-card .btn-success:hover::before {
        width: 100%;
    }
    
    .event-card .btn-outline-secondary {
        border: 2px solid var(--ju-border-blue);
        color: var(--ju-primary-blue);
        transition: var(--ju-transition);
    }
    
    .event-card .btn-outline-secondary:hover {
        background: var(--ju-gradient);
        border-color: transparent;
        color: white;
        transform: translateY(-2px) rotate(90deg);
    }
    
    /* Dropdown Menu - Blue Theme */
    .dropdown-menu {
        border: none;
        border-radius: 12px;
        box-shadow: var(--ju-card-hover);
        padding: 0.5rem;
    }
    
    .dropdown-item {
        border-radius: 8px;
        padding: 0.6rem 1rem;
        color: var(--ju-primary-blue);
        transition: var(--ju-transition);
    }
    
    .dropdown-item:hover {
        background: var(--ju-gradient);
        color: white;
        transform: translateX(5px);
    }
    
    .dropdown-item i {
        transition: var(--ju-transition);
    }
    
    .dropdown-item:hover i {
        color: white !important;
        transform: scale(1.1);
    }
    
    .dropdown-divider {
        border-color: var(--ju-border-blue);
        opacity: 0.5;
    }
    
    /* List View - Blue Theme */
    .list-group-item {
        transition: var(--ju-transition);
        border-left: 4px solid transparent;
    }
    
    .list-group-item:hover {
        background: var(--ju-light-blue);
        border-left-color: var(--ju-primary-blue);
        transform: translateX(5px);
    }
    
    .list-group-item .bg-success {
        background: var(--ju-gradient-light) !important;
        transition: var(--ju-transition);
    }
    
    .list-group-item:hover .bg-success {
        background: var(--ju-gradient) !important;
    }
    
    .list-group-item:hover .text-success {
        color: white !important;
    }
    
    .list-group-item .fw-bold {
        color: var(--ju-primary-blue);
        transition: var(--ju-transition);
    }
    
    .list-group-item:hover .fw-bold {
        color: var(--ju-secondary-blue);
    }
    
    /* Pagination - Blue Theme */
    .pagination {
        gap: 5px;
    }
    
    .page-link {
        border: 2px solid var(--ju-border-blue);
        border-radius: 8px !important;
        padding: 0.6rem 1rem;
        color: var(--ju-primary-blue);
        font-weight: 600;
        transition: var(--ju-transition);
        margin: 0 2px;
    }
    
    .page-link:hover {
        background: var(--ju-gradient);
        border-color: transparent;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 51, 102, 0.2);
    }
    
    .page-item.active .page-link {
        background: var(--ju-gradient);
        border-color: transparent;
        color: white;
        box-shadow: 0 6px 16px rgba(0, 51, 102, 0.25);
    }
    
    .page-item.disabled .page-link {
        border-color: var(--ju-border-blue);
        color: #9AA6B2;
        opacity: 0.7;
    }
    
    /* View Toggle Buttons - Blue Theme */
    .btn-group .btn-outline-secondary {
        border: 2px solid var(--ju-border-blue);
        color: var(--ju-primary-blue);
        padding: 0.5rem 1rem;
        transition: var(--ju-transition);
    }
    
    .btn-group .btn-outline-secondary:hover {
        background: var(--ju-gradient);
        border-color: transparent;
        color: white;
        transform: translateY(-2px);
        z-index: 2;
    }
    
    .btn-group .btn-outline-secondary.active {
        background: var(--ju-gradient);
        border-color: transparent;
        color: white;
    }
    
    /* Calendar Button - Blue Theme */
    .btn-outline-success {
        border: 2px solid var(--ju-primary-blue);
        color: var(--ju-primary-blue);
        background: transparent;
        transition: var(--ju-transition);
    }
    
    .btn-outline-success:hover {
        background: var(--ju-gradient);
        border-color: transparent;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 51, 102, 0.25);
    }
    
    .btn-outline-success i {
        transition: var(--ju-transition);
    }
    
    .btn-outline-success:hover i {
        transform: rotate(15deg);
    }
    
    /* Empty State - Blue Theme */
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
        background: white;
        border-radius: 24px;
        box-shadow: var(--ju-card-shadow);
    }
    
    .empty-state i {
        color: var(--ju-primary-blue);
        opacity: 0.2;
        transition: var(--ju-transition);
    }
    
    .empty-state:hover i {
        transform: scale(1.1);
        opacity: 0.3;
    }
    
    .empty-state h4 {
        color: var(--ju-primary-blue);
        font-weight: 700;
    }
    
    /* Modal - Blue Theme */
    .modal-content {
        border: none;
        border-radius: 20px;
        overflow: hidden;
    }
    
    .modal-header {
        background: var(--ju-gradient-light);
        border-bottom: 2px solid var(--ju-border-blue);
    }
    
    .modal-title {
        color: var(--ju-primary-blue);
        font-weight: 700;
    }
    
    .btn-close {
        transition: var(--ju-transition);
    }
    
    .btn-close:hover {
        transform: rotate(90deg);
    }
    
    /* FC Customization - FullCalendar Blue Theme */
    .fc .fc-button-primary {
        background: var(--ju-gradient);
        border: none;
        transition: var(--ju-transition);
    }
    
    .fc .fc-button-primary:hover {
        background: var(--ju-gradient-hover);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 51, 102, 0.25);
    }
    
    .fc .fc-button-primary:not(:disabled).fc-button-active,
    .fc .fc-button-primary:not(:disabled):active {
        background: var(--ju-gradient-hover);
    }
    
    .fc .fc-day-today {
        background: var(--ju-light-blue) !important;
    }
    
    .fc .fc-event {
        background: var(--ju-gradient);
        border: none;
        padding: 4px 8px;
        transition: var(--ju-transition);
    }
    
    .fc .fc-event:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 12px rgba(0, 51, 102, 0.3);
    }
    
    /* Advanced Options Link - Blue Theme */
    .btn-link {
        color: var(--ju-primary-blue);
        font-weight: 600;
        text-decoration: none;
        transition: var(--ju-transition);
    }
    
    .btn-link:hover {
        color: var(--ju-accent-blue);
        transform: translateX(5px);
    }
    
    .btn-link i {
        transition: var(--ju-transition);
    }
    
    .btn-link:hover i {
        transform: translateX(3px);
    }
    
    /* Results Count Animation */
    @keyframes slideInBlue {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .text-muted.small {
        animation: slideInBlue 0.6s ease;
    }
    
    /* Hover Card Effects */
    .position-relative {
        overflow: hidden;
    }
    
    .position-relative::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(0, 102, 204, 0.1), transparent);
        transition: left 0.6s ease;
    }
    
    .event-card:hover .position-relative::after {
        left: 100%;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .filter-card {
            border-radius: 12px;
        }
        
        .category-btn {
            width: 100%;
            justify-content: center;
        }
        
        .event-card {
            margin-bottom: 0;
        }
    }
</style>

<div class="container-fluid px-4 py-4">
    <!-- Advanced Filter Bar -->
    <div class="filter-card mb-4">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('events.guest.index') }}" id="advancedFilterForm">
                <div class="row g-3 align-items-end">
                    <!-- Quick Search -->
                    <div class="col-lg-3">
                        <label class="form-label small fw-semibold text-primary mb-2">
                            <i class="fas fa-search me-1"></i>Search Events
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control" name="search" 
                                   placeholder="Title, speaker, keywords..." 
                                   value="{{ request('search') }}">
                        </div>
                    </div>

                    <!-- Campus Filter -->
                    <div class="col-lg-2">
                        <label class="form-label small fw-semibold text-primary mb-2">
                            <i class="fas fa-map-marker-alt me-1"></i>Campus
                        </label>
                        <select class="form-select" name="campus">
                            <option value="">All Campuses</option>
                            @foreach($campuses as $campus)
                            <option value="{{ $campus }}" {{ request('campus') == $campus ? 'selected' : '' }}>
                                {{ $campus }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Event Type -->
                    <div class="col-lg-2">
                        <label class="form-label small fw-semibold text-primary mb-2">
                            <i class="fas fa-tag me-1"></i>Event Type
                        </label>
                        <select class="form-select" name="event_type">
                            <option value="">All Types</option>
                            @foreach($eventTypes as $type)
                            <option value="{{ $type }}" {{ request('event_type') == $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date Filter -->
                    <div class="col-lg-2">
                        <label class="form-label small fw-semibold text-primary mb-2">
                            <i class="fas fa-calendar-alt me-1"></i>Date Range
                        </label>
                        <select class="form-select" name="date_range">
                            <option value="">Any Date</option>
                            <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>This Week</option>
                            <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>This Month</option>
                            <option value="upcoming" {{ request('date_range') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                        </select>
                    </div>

                    <!-- Sort Options -->
                    <div class="col-lg-2">
                        <label class="form-label small fw-semibold text-primary mb-2">
                            <i class="fas fa-sort-amount-down me-1"></i>Sort By
                        </label>
                        <select class="form-select" name="sort">
                            <option value="start_date" {{ request('sort') == 'start_date' ? 'selected' : '' }}>Date (Newest)</option>
                            <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Title (A-Z)</option>
                            <option value="popularity" {{ request('sort') == 'popularity' ? 'selected' : '' }}>Most Popular</option>
                            <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Recently Added</option>
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="col-lg-1">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill" title="Apply Filters">
                                <i class="fas fa-filter me-1"></i>
                                <span class="d-none d-lg-inline">Apply</span>
                            </button>
                            <a href="{{ route('events.guest.index') }}" class="btn btn-outline-primary" title="Clear Filters">
                                <i class="fas fa-redo-alt"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Advanced Options Toggle -->
                <div class="row mt-3">
                    <div class="col-12">
                        <button type="button" class="btn btn-link p-0" id="advancedToggleBtn">
                            <i class="fas fa-caret-down me-1"></i>Advanced Options
                        </button>
                    </div>
                </div>

                <!-- Advanced Options Panel -->
                <div id="advancedOptions" style="display: none;">
                    <div class="row mt-3 pt-3 border-top" style="border-color: var(--ju-border-blue) !important;">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-primary">
                                <i class="fas fa-users me-1"></i>Audience
                            </label>
                            <select class="form-select" name="audience">
                                <option value="">All Audiences</option>
                                <option value="students" {{ request('audience') == 'students' ? 'selected' : '' }}>Students</option>
                                <option value="faculty" {{ request('audience') == 'faculty' ? 'selected' : '' }}>Faculty</option>
                                <option value="staff" {{ request('audience') == 'staff' ? 'selected' : '' }}>Staff</option>
                                <option value="public" {{ request('audience') == 'public' ? 'selected' : '' }}>Public</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-primary">
                                <i class="fas fa-ticket-alt me-1"></i>Registration
                            </label>
                            <select class="form-select" name="registration">
                                <option value="">Any</option>
                                <option value="required" {{ request('registration') == 'required' ? 'selected' : '' }}>Registration Required</option>
                                <option value="free" {{ request('registration') == 'free' ? 'selected' : '' }}>Free Entry</option>
                                <option value="paid" {{ request('registration') == 'paid' ? 'selected' : '' }}>Paid Event</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-primary">
                                <i class="fas fa-clock me-1"></i>Status
                            </label>
                            <select class="form-select" name="status">
                                <option value="">All Status</option>
                                <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <!-- Event Type Categories -->
        <div class="col-12 mb-4">
            <div class="filter-card">
                <div class="card-body p-4">
                    <h5 class="mb-3 d-flex align-items-center">
                        <span class="badge bg-primary me-2" style="width: 6px; height: 6px; padding: 0; border-radius: 50%;"></span>
                        <span class="text-primary fw-bold">Browse by Category</span>
                    </h5>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('events.guest.index', ['event_type' => 'academic']) }}" 
                           class="category-btn btn-outline-primary {{ request('event_type') == 'academic' ? 'active' : '' }}">
                            <i class="fas fa-graduation-cap"></i>Academic
                        </a>
                        <a href="{{ route('events.guest.index', ['event_type' => 'cultural']) }}" 
                           class="category-btn btn-outline-warning {{ request('event_type') == 'cultural' ? 'active' : '' }}">
                            <i class="fas fa-music"></i>Cultural
                        </a>
                        <a href="{{ route('events.guest.index', ['event_type' => 'sports']) }}" 
                           class="category-btn btn-outline-success {{ request('event_type') == 'sports' ? 'active' : '' }}">
                            <i class="fas fa-futbol"></i>Sports
                        </a>
                        <a href="{{ route('events.guest.index', ['event_type' => 'conference']) }}" 
                           class="category-btn btn-outline-info {{ request('event_type') == 'conference' ? 'active' : '' }}">
                            <i class="fas fa-users"></i>Conference
                        </a>
                        <a href="{{ route('events.guest.index', ['event_type' => 'workshop']) }}" 
                           class="category-btn btn-outline-danger {{ request('event_type') == 'workshop' ? 'active' : '' }}">
                            <i class="fas fa-tools"></i>Workshop
                        </a>
                        <a href="{{ route('events.guest.index', ['event_type' => 'seminar']) }}" 
                           class="category-btn btn-outline-dark {{ request('event_type') == 'seminar' ? 'active' : '' }}">
                            <i class="fas fa-chalkboard-teacher"></i>Seminar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Events Grid -->
        <div class="col-12">
            <!-- View Controls -->
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <div class="mb-2 mb-sm-0">
                    <h4 class="mb-1 d-flex align-items-center">
                        <i class="fas fa-calendar-alt text-primary me-2"></i>
                        <span class="text-primary">{{ request('event_type') ? ucfirst(request('event_type')) . ' Events' : 'All Events' }}</span>
                    </h4>
                    <p class="text-muted mb-0 small">
                        @if($events->count() > 0)
                            Showing <span class="fw-bold text-primary">{{ $events->firstItem() }}-{{ $events->lastItem() }}</span> 
                            of <span class="fw-bold text-primary">{{ $events->total() }}</span> events
                        @else
                            No events found
                        @endif
                    </p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div class="btn-group" role="group" aria-label="View toggle">
                        <button type="button" class="btn btn-outline-secondary active" onclick="changeView('grid')" title="Grid View">
                            <i class="fas fa-th-large"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="changeView('list')" title="List View">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                    <a href="#calendarModal" class="btn btn-outline-primary" data-bs-toggle="modal">
                        <i class="fas fa-calendar-alt me-1"></i>
                        <span class="d-none d-md-inline">Calendar</span>
                    </a>
                </div>
            </div>

            <!-- Events Grid View -->
            <div id="gridView">
                @if($events->count() > 0)
                <div class="row g-4">
                    @foreach($events as $event)
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <div class="event-card">
                            <div class="position-relative">
                                <!-- Event Image -->
                                @if($event->image)
                                <img src="{{ $event->image_url }}" 
                                     class="card-img-top" 
                                     alt="{{ $event->title }}"
                                     loading="lazy"
                                     onerror="this.onerror=null; this.parentElement.querySelector('.bg-primary').style.display='flex'; this.style.display='none';">
                                @else
                                <div class="bg-primary d-flex align-items-center justify-content-center" 
                                     style="height: 200px; background: var(--ju-gradient-light) !important;">
                                    <i class="fas fa-{{ $event->event_type == 'sports' ? 'futbol' : ($event->event_type == 'cultural' ? 'music' : ($event->event_type == 'academic' ? 'graduation-cap' : 'calendar-day')) }} fa-3x" style="color: var(--ju-primary-blue); opacity: 0.3;"></i>
                                </div>
                                @endif

                                <!-- Badges -->
                                <div class="position-absolute top-0 start-0 m-3">
                                    <span class="badge" style="background: var(--ju-gradient); color: white;">
                                        {{ ucfirst($event->event_type) }}
                                    </span>
                                </div>

                                @if($event->is_featured)
                                <div class="position-absolute top-0 end-0 m-3">
                                    <span class="badge" style="background: var(--ju-gold); color: var(--ju-primary-blue);">
                                        <i class="fas fa-star me-1"></i>Featured
                                    </span>
                                </div>
                                @endif

                                @if($event->status == 'ongoing')
                                <div class="position-absolute bottom-0 start-0 m-3">
                                    <span class="badge" style="background: #DC3545; color: white;">
                                        <i class="fas fa-play-circle me-1"></i>Live Now
                                    </span>
                                </div>
                                @endif
                            </div>

                            <div class="card-body">
                                <h5 class="card-title">{{ Str::limit($event->title, 60) }}</h5>
                                <p class="card-text">{{ Str::limit(strip_tags($event->description), 90) }}</p>

                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <div class="d-flex align-items-center text-muted small">
                                            <i class="fas fa-calendar me-2" style="color: var(--ju-primary-blue);"></i>
                                            <span>{{ $event->start_date->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex align-items-center text-muted small">
                                            <i class="fas fa-clock me-2" style="color: var(--ju-primary-blue);"></i>
                                            <span>{{ $event->start_date->format('h:i A') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex align-items-center text-muted small">
                                            <i class="fas fa-map-marker-alt me-2" style="color: var(--ju-primary-blue);"></i>
                                            <span>{{ Str::limit($event->venue, 20) }}</span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex align-items-center text-muted small">
                                            <i class="fas fa-users me-2" style="color: var(--ju-primary-blue);"></i>
                                            <span>{{ $event->registered_attendees ?? 0 }} attending</span>
                                        </div>
                                    </div>
                                </div>

                                @if($event->max_attendees)
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span class="fw-semibold" style="color: var(--ju-primary-blue);">Capacity</span>
                                        <span class="text-muted">{{ $event->registered_attendees ?? 0 }}/{{ $event->max_attendees }}</span>
                                    </div>
                                    <div class="progress">
                                        @php
                                            $percentage = $event->max_attendees > 0 ? min(100, ($event->registered_attendees ?? 0) / $event->max_attendees * 100) : 0;
                                            $barClass = $percentage > 80 ? 'bg-danger' : ($percentage > 50 ? 'bg-warning' : 'bg-primary');
                                        @endphp
                                        <div class="progress-bar {{ $barClass }}" 
                                             style="width: {{ $percentage }}%"
                                             role="progressbar"
                                             aria-valuenow="{{ $percentage }}"
                                             aria-valuemin="0"
                                             aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="card-footer bg-transparent border-top-0 pb-4 px-4 pt-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('events.guest.show', $event->slug) }}" 
                                       class="btn btn-success">
                                        <i class="fas fa-eye me-2"></i>View Details
                                    </a>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary dropdown-toggle" 
                                                type="button" 
                                                data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                            <i class="fas fa-ellipsis-h"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="shareEvent('{{ $event->slug }}')">
                                                    <i class="fas fa-share-alt me-2" style="color: var(--ju-primary-blue);"></i>Share Event
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('events.export.ics', $event->slug) }}">
                                                    <i class="fas fa-calendar-plus me-2" style="color: var(--ju-primary-blue);"></i>Add to Calendar
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="saveEvent({{ $event->id }})">
                                                    <i class="fas fa-bookmark me-2" style="color: var(--ju-primary-blue);"></i>Save Event
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="reportEvent({{ $event->id }})">
                                                    <i class="fas fa-flag me-2" style="color: #DC3545;"></i>Report Event
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <!-- Empty State -->
                <div class="empty-state">
                    <i class="fas fa-calendar-times fa-4x mb-4"></i>
                    <h4 class="mb-3">No Events Found</h4>
                    <p class="text-muted mb-4">We couldn't find any events matching your criteria. Try adjusting your filters.</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('events.guest.index') }}" class="btn btn-primary">
                            <i class="fas fa-redo-alt me-2"></i>Clear All Filters
                        </a>
                        <a href="{{ route('events.guest.dashboard') }}" class="btn btn-outline-primary">
                            <i class="fas fa-home me-2"></i>Go to Dashboard
                        </a>
                    </div>
                </div>
                @endif
            </div>

            <!-- Events List View (Hidden by default) -->
            <div id="listView" style="display: none;">
                @if($events->count() > 0)
                <div class="filter-card">
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @foreach($events as $event)
                            <div class="list-group-item border-0 py-4 px-4">
                                <div class="row align-items-center">
                                    <!-- Date Column -->
                                    <div class="col-lg-2 col-md-3 mb-3 mb-md-0">
                                        <div class="bg-primary d-flex flex-column align-items-center justify-content-center rounded p-3" style="background: var(--ju-gradient-light) !important;">
                                            <span class="small fw-bold" style="color: var(--ju-primary-blue);">{{ $event->start_date->format('M') }}</span>
                                            <span class="fs-3 fw-bold" style="color: var(--ju-primary-blue);">{{ $event->start_date->format('d') }}</span>
                                            <span class="small" style="color: var(--ju-primary-blue); opacity: 0.8;">{{ $event->start_date->format('Y') }}</span>
                                        </div>
                                    </div>

                                    <!-- Event Info -->
                                    <div class="col-lg-6 col-md-5">
                                        <h6 class="mb-2" style="color: var(--ju-primary-blue); font-weight: 700;">{{ $event->title }}</h6>
                                        <p class="text-muted small mb-2">{{ Str::limit(strip_tags($event->description), 120) }}</p>
                                        <div class="d-flex flex-wrap gap-2">
                                            <span class="badge" style="background: var(--ju-gradient); color: white;">{{ ucfirst($event->event_type) }}</span>
                                            <span class="badge" style="background: var(--ju-border-blue); color: var(--ju-primary-blue);">{{ $event->campus ?? 'Main Campus' }}</span>
                                            @if($event->requires_registration)
                                            <span class="badge" style="background: var(--ju-gold); color: var(--ju-primary-blue);">
                                                <i class="fas fa-ticket-alt me-1"></i>Registration
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Time & Location -->
                                    <div class="col-lg-2 col-md-2 mt-3 mt-md-0">
                                        <div class="mb-2 small text-muted">
                                            <i class="fas fa-clock me-1" style="color: var(--ju-primary-blue);"></i>
                                            {{ $event->start_date->format('h:i A') }}
                                        </div>
                                        <div class="mb-2 small text-muted">
                                            <i class="fas fa-map-marker-alt me-1" style="color: var(--ju-primary-blue);"></i>
                                            {{ Str::limit($event->venue, 15) }}
                                        </div>
                                        <div class="small text-muted">
                                            <i class="fas fa-users me-1" style="color: var(--ju-primary-blue);"></i>
                                            {{ $event->registered_attendees ?? 0 }} attending
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="col-lg-2 col-md-2 text-end mt-3 mt-md-0">
                                        <div class="d-flex flex-column gap-2">
                                            <a href="{{ route('events.guest.show', $event->slug) }}" 
                                               class="btn btn-primary btn-sm">
                                                View Details
                                            </a>
                                            <a href="#" onclick="shareEvent('{{ $event->slug }}')" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-share-alt me-1"></i>Share
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Pagination -->
            @if($events->hasPages())
            <div class="row mt-5">
                <div class="col-12">
                    <nav aria-label="Events navigation">
                        <div class="d-flex flex-wrap justify-content-between align-items-center">
                            <div class="text-muted small mb-2 mb-sm-0">
                                Page <span class="fw-bold text-primary">{{ $events->currentPage() }}</span> of {{ $events->lastPage() }}
                            </div>
                            {{ $events->withQueryString()->links('vendor.pagination.bootstrap-5') }}
                        </div>
                    </nav>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Calendar Modal -->
<div class="modal fade" id="calendarModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center">
                    <i class="fas fa-calendar-alt me-2" style="color: var(--ju-primary-blue);"></i>
                    Events Calendar
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div id="calendarView" style="height: 600px;"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
<style>
    /* FullCalendar Overrides */
    .fc .fc-col-header-cell-cushion {
        color: var(--ju-primary-blue);
        text-decoration: none;
        font-weight: 600;
    }
    
    .fc .fc-daygrid-day-number {
        color: var(--ju-primary-blue);
        text-decoration: none;
        font-weight: 500;
    }
    
    .fc .fc-event {
        cursor: pointer;
        border-radius: 6px;
        padding: 3px 8px;
        margin-bottom: 2px;
    }
    
    .fc .fc-event-title {
        font-weight: 500;
        font-size: 0.85rem;
    }
    
    .fc .fc-button-primary:focus {
        box-shadow: 0 0 0 3px rgba(0, 64, 128, 0.25);
    }
    
    /* Loading State */
    .fc .fc-loading {
        color: var(--ju-primary-blue);
        font-weight: 600;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize view toggle
    window.changeView = function(view) {
        const gridView = document.getElementById('gridView');
        const listView = document.getElementById('listView');
        const gridBtn = document.querySelector('button[onclick*="grid"]');
        const listBtn = document.querySelector('button[onclick*="list"]');
        
        if (view === 'grid') {
            gridView.style.display = 'block';
            listView.style.display = 'none';
            gridBtn.classList.add('active');
            listBtn.classList.remove('active');
            
            // Animate grid items
            document.querySelectorAll('.event-card').forEach((card, index) => {
                card.style.animation = `slideInBlue 0.5s ease ${index * 0.05}s both`;
            });
        } else {
            gridView.style.display = 'none';
            listView.style.display = 'block';
            gridBtn.classList.remove('active');
            listBtn.classList.add('active');
            
            // Animate list items
            document.querySelectorAll('.list-group-item').forEach((item, index) => {
                item.style.animation = `slideInBlue 0.5s ease ${index * 0.05}s both`;
            });
        }
        
        // Store preference
        localStorage.setItem('eventsViewPreference', view);
    };

    // Load view preference
    const savedView = localStorage.getItem('eventsViewPreference');
    if (savedView === 'list') {
        window.changeView('list');
    }

    // Advanced options toggle
    const advancedOptions = document.getElementById('advancedOptions');
    const toggleBtn = document.getElementById('advancedToggleBtn');
    
    if (advancedOptions && toggleBtn) {
        toggleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (advancedOptions.style.display === 'none' || !advancedOptions.style.display) {
                advancedOptions.style.display = 'block';
                this.innerHTML = '<i class="fas fa-caret-up me-1"></i>Hide Options';
                
                // Animate open
                advancedOptions.style.animation = 'slideDown 0.4s ease';
            } else {
                advancedOptions.style.animation = 'slideUp 0.4s ease';
                setTimeout(() => {
                    advancedOptions.style.display = 'none';
                }, 380);
                this.innerHTML = '<i class="fas fa-caret-down me-1"></i>Advanced Options';
            }
        });
        
        // Check if any advanced filter is active
        const advancedFilters = ['audience', 'registration', 'status'];
        const hasActiveFilter = advancedFilters.some(filter => 
            new URLSearchParams(window.location.search).has(filter)
        );
        
        if (hasActiveFilter) {
            advancedOptions.style.display = 'block';
            toggleBtn.innerHTML = '<i class="fas fa-caret-up me-1"></i>Hide Options';
        }
    }

    // Initialize calendar
    const calendarEl = document.getElementById('calendarView');
    if (calendarEl) {
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listMonth'
            },
            events: '{{ route("api.events.calendar") }}',
            eventClick: function(info) {
                window.location.href = '/guest/events/' + info.event.extendedProps.slug;
            },
            eventDisplay: 'block',
            eventColor: '#003366',
            eventTextColor: '#ffffff',
            loading: function(isLoading) {
                if (isLoading) {
                    calendarEl.style.opacity = '0.6';
                } else {
                    calendarEl.style.opacity = '1';
                }
            }
        });
        
        // Load calendar when modal is shown
        const calendarModal = document.getElementById('calendarModal');
        if (calendarModal) {
            calendarModal.addEventListener('shown.bs.modal', function() {
                calendar.render();
            });
            
            calendarModal.addEventListener('hidden.bs.modal', function() {
                calendar.destroy();
            });
        }
    }

    // Auto-submit form on filter change (except search)
    const filterForm = document.getElementById('advancedFilterForm');
    if (filterForm) {
        const autoSubmitSelects = filterForm.querySelectorAll('select[name="campus"], select[name="event_type"], select[name="date_range"], select[name="sort"], select[name="audience"], select[name="registration"], select[name="status"]');
        
        autoSubmitSelects.forEach(select => {
            select.addEventListener('change', function() {
                // Add loading state
                const submitBtn = filterForm.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Applying...';
                    submitBtn.disabled = true;
                }
                filterForm.submit();
            });
        });
    }

    // Search input debounce
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                filterForm.submit();
            }
        });
    }

    // Share event function
    window.shareEvent = function(slug) {
        const url = window.location.origin + '/guest/events/' + slug;
        
        if (navigator.share) {
            navigator.share({
                title: 'Jimma University Event',
                text: 'Check out this event at Jimma University',
                url: url
            }).catch(() => {
                // Fallback to clipboard
                copyToClipboard(url);
            });
        } else {
            copyToClipboard(url);
        }
    };

    // Copy to clipboard helper
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('Event link copied to clipboard!');
        }).catch(() => {
            prompt('Copy this link to share the event:', text);
        });
    }

    // Save event function
    window.saveEvent = function(eventId) {
        fetch('/api/events/' + eventId + '/save', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.saved) {
                alert('Event saved successfully!');
            } else {
                alert('Event removed from saved!');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Please login to save events');
        });
    };

    // Report event function
    window.reportEvent = function(eventId) {
        const reason = prompt('Please enter the reason for reporting this event:');
        if (reason && reason.trim()) {
            fetch('/api/events/' + eventId + '/report', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ reason: reason.trim() })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message || 'Thank you for your report. We will review it shortly.');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while reporting the event.');
            });
        }
    };

    // Animation keyframes
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes slideUp {
            from {
                opacity: 1;
                transform: translateY(0);
            }
            to {
                opacity: 0;
                transform: translateY(-20px);
            }
        }
    `;
    document.head.appendChild(style);
});
</script>
@endpush
