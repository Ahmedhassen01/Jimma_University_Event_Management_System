@extends('layouts.app')

@section('title', 'Dashboard - JU Event Management')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Welcome back, ' . auth()->user()->name)

@section('breadcrumb-items')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<style>
    /* ============================================
       PREMIUM DASHBOARD - ROYAL BLUE & GOLD
       Official Jimma University Colors
    ============================================ */
    :root {
        --ju-blue: #002789;
        --ju-blue-dark: #001a5c;
        --ju-blue-darker: #021230;
        --ju-blue-light: #1a3a9a;
        --ju-blue-lighter: #3a6ab0;
        --ju-blue-soft: #e6ebf7;
        --ju-blue-muted: #d4e0f0;
        --ju-blue-glow: rgba(0, 39, 137, 0.2);
        
        --ju-gold: #C4A747;
        --ju-gold-dark: #a5862e;
        --ju-gold-light: #e5d6a6;
        --ju-gold-soft: rgba(196, 167, 71, 0.12);
        --ju-gold-gradient: linear-gradient(145deg, #C4A747, #a5862e);
        
        --ju-white: #ffffff;
        --ju-offwhite: #f9f9f9;
        --ju-gray: #f0f0f0;
        --ju-gray-soft: #f8fafc;
        --ju-gray-dark: #333333;
        
        --gradient-primary: linear-gradient(145deg, #002789, #001a5c);
        --gradient-primary-light: linear-gradient(145deg, #1a3a9a, #002789);
        --gradient-gold: linear-gradient(145deg, #C4A747, #a5862e);
        
        --shadow-xs: 0 2px 4px rgba(0,39,137,0.02);
        --shadow-sm: 0 4px 6px rgba(0,39,137,0.04);
        --shadow: 0 6px 12px rgba(0,39,137,0.06);
        --shadow-md: 0 8px 24px rgba(0,39,137,0.08);
        --shadow-lg: 0 16px 32px rgba(0,39,137,0.1);
        --shadow-xl: 0 24px 48px rgba(0,39,137,0.12);
        --shadow-2xl: 0 32px 64px rgba(0,39,137,0.15);
        --shadow-gold: 0 8px 20px rgba(196,167,71,0.2);
        
        --radius-sm: 0.25rem;
        --radius: 0.375rem;
        --radius-md: 0.5rem;
        --radius-lg: 0.75rem;
        --radius-xl: 1rem;
        --radius-2xl: 1.25rem;
        --radius-full: 9999px;
        
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        --transition-bounce: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        --transition-elastic: all 0.6s cubic-bezier(0.68, -0.6, 0.32, 1.6);
    }

    /* ============================================
       PREMIUM STAT CARDS
    ============================================ */
    .stat-card {
        background: var(--ju-white);
        border: 1px solid var(--ju-gray);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow);
        transition: var(--transition-bounce);
        position: relative;
        overflow: hidden;
        height: 100%;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--gradient-primary);
        transform: translateX(-100%);
        transition: transform 0.6s ease;
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-2xl);
        border-color: var(--ju-blue);
    }

    .stat-card:hover::before {
        transform: translateX(0);
    }

    .icon-circle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition-bounce);
        background: var(--ju-blue-soft);
        color: var(--ju-blue);
    }

    .stat-card:hover .icon-circle {
        background: var(--gradient-primary);
        color: white;
        transform: scale(1.1) rotate(8deg);
        box-shadow: var(--shadow-gold);
    }

    .stat-card .btn-sm {
        transition: var(--transition);
    }

    .stat-card:hover .btn-sm {
        background: var(--ju-blue);
        color: white;
        border-color: var(--ju-blue);
    }

    /* ============================================
       PREMIUM TABLE STYLES
    ============================================ */
    .table thead th {
        background: var(--ju-blue-soft);
        color: var(--ju-blue-dark);
        font-weight: 700;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 1rem;
        border-bottom: 2px solid var(--ju-blue);
    }

    .table-hover tbody tr {
        transition: var(--transition);
        cursor: pointer;
    }

    .table-hover tbody tr:hover {
        background: linear-gradient(90deg, var(--ju-blue-soft), rgba(230, 235, 247, 0.5));
        transform: translateX(5px);
        box-shadow: var(--shadow);
    }

    .user-avatar-sm {
        width: 36px;
        height: 36px;
        border-radius: var(--radius-full);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
        transition: var(--transition-bounce);
        background: var(--ju-blue-soft);
        color: var(--ju-blue);
    }

    .user-row:hover .user-avatar-sm {
        background: var(--gradient-primary);
        color: white;
        transform: scale(1.1) rotate(8deg);
        box-shadow: var(--shadow-gold);
    }

    /* ============================================
       PREMIUM BADGES
    ============================================ */
    .badge {
        padding: 0.5rem 1rem;
        border-radius: var(--radius-full);
        font-weight: 600;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        transition: var(--transition);
    }

    .badge-primary-ju {
        background: var(--gradient-primary);
        color: white;
        box-shadow: var(--shadow);
    }

    .badge-primary-ju-soft {
        background: var(--ju-blue-soft);
        color: var(--ju-blue-dark);
        border: 1px solid var(--ju-blue-muted);
    }

    .badge-success-soft {
        background: #e3f1e3;
        color: #28a745;
        border: 1px solid #c3e6cb;
    }

    .badge-secondary-soft {
        background: var(--ju-gray);
        color: var(--ju-gray-dark);
        border: 1px solid #ddd;
    }

    /* ============================================
       PREMIUM BUTTONS
    ============================================ */
    .btn-primary-ju {
        background: var(--gradient-primary);
        color: white;
        border: none;
        padding: 0.6rem 1.5rem;
        border-radius: var(--radius-full);
        font-weight: 600;
        font-size: 0.9rem;
        transition: var(--transition-bounce);
        box-shadow: var(--shadow);
        position: relative;
        overflow: hidden;
    }

    .btn-primary-ju::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.6s ease;
    }

    .btn-primary-ju:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-2xl);
        background: var(--ju-blue-dark);
    }

    .btn-primary-ju:hover::before {
        left: 100%;
    }

    .btn-primary-ju-outline {
        background: transparent;
        color: var(--ju-blue);
        border: 2px solid var(--ju-blue);
        padding: 0.5rem 1.2rem;
        border-radius: var(--radius-full);
        font-weight: 600;
        font-size: 0.85rem;
        transition: var(--transition-bounce);
    }

    .btn-primary-ju-outline:hover {
        background: var(--ju-blue);
        color: white;
        transform: translateY(-2px);
        box-shadow: var(--shadow);
    }

    /* ============================================
       PREMIUM QUICK ACTION BUTTONS
    ============================================ */
    .quick-action-btn {
        display: flex;
        align-items: center;
        text-decoration: none;
        color: var(--ju-gray-dark);
        background: var(--ju-white);
        border-radius: var(--radius-lg);
        border: 1px solid var(--ju-gray);
        transition: var(--transition-bounce);
        box-shadow: var(--shadow-xs);
        position: relative;
        overflow: hidden;
    }

    .quick-action-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 0;
        background: var(--gradient-primary);
        transition: height 0.3s ease;
    }

    .quick-action-btn:hover {
        background: linear-gradient(90deg, var(--ju-white), var(--ju-blue-soft));
        border-color: var(--ju-blue);
        transform: translateY(-4px) scale(1.02);
        box-shadow: var(--shadow-lg);
    }

    .quick-action-btn:hover::before {
        height: 100%;
    }

    .quick-action-btn .icon-circle {
        width: 48px;
        height: 48px;
        transition: var(--transition-bounce);
    }

    .quick-action-btn:hover .icon-circle {
        background: var(--gradient-primary) !important;
        color: white !important;
        transform: scale(1.1) rotate(8deg);
    }

    .quick-action-btn i.fa-arrow-right {
        transition: var(--transition-elastic);
        color: var(--ju-blue);
        opacity: 0.6;
    }

    .quick-action-btn:hover i.fa-arrow-right {
        transform: translateX(8px);
        opacity: 1;
        color: var(--ju-gold) !important;
    }

    /* ============================================
       PREMIUM CARD STYLES
    ============================================ */
    .ju-card {
        border: 1px solid var(--ju-gray);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow);
        transition: var(--transition);
        background: var(--ju-white);
        overflow: hidden;
    }

    .ju-card:hover {
        box-shadow: var(--shadow-xl);
        border-color: var(--ju-blue);
    }

    .ju-card-header {
        border-bottom: 1px solid var(--ju-gray);
        background: var(--ju-white) !important;
        padding: 1.25rem 1.5rem;
    }

    .ju-card-title {
        color: var(--ju-blue);
        font-weight: 700;
        font-size: 1.1rem;
        letter-spacing: -0.3px;
    }

    .ju-card-title i {
        color: var(--ju-gold);
    }

    /* ============================================
       ROLE DISTRIBUTION ITEMS
    ============================================ */
    .role-distribution-item {
        padding: 0.6rem 1rem;
        border-radius: var(--radius-lg);
        transition: var(--transition);
        border: 1px solid transparent;
    }

    .role-distribution-item:hover {
        background: var(--ju-blue-soft);
        border-color: var(--ju-blue-muted);
        transform: translateX(8px);
        box-shadow: var(--shadow);
    }

    .role-distribution-item .badge {
        transition: var(--transition);
    }

    .role-distribution-item:hover .badge {
        background: var(--gradient-primary) !important;
        color: white !important;
    }

    /* ============================================
       CHART CONTAINER
    ============================================ */
    .chart-container {
        position: relative;
        width: 100%;
        height: 260px;
        transition: var(--transition);
    }

    .chart-container:hover {
        transform: scale(1.01);
    }

    /* ============================================
       CUSTOM SCROLLBAR
    ============================================ */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    ::-webkit-scrollbar-track {
        background: var(--ju-blue-soft);
        border-radius: var(--radius-full);
    }

    ::-webkit-scrollbar-thumb {
        background: var(--ju-blue);
        border-radius: var(--radius-full);
        transition: var(--transition);
    }

    ::-webkit-scrollbar-thumb:hover {
        background: var(--ju-blue-dark);
    }

    /* ============================================
       ANIMATIONS
    ============================================ */
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .stat-card, .ju-card {
        animation: slideInUp 0.6s ease-out both;
    }

    .stat-card:nth-child(1) { animation-delay: 0.1s; }
    .stat-card:nth-child(2) { animation-delay: 0.2s; }
    .stat-card:nth-child(3) { animation-delay: 0.3s; }
    .stat-card:nth-child(4) { animation-delay: 0.4s; }
</style>

<div class="container-fluid px-0">
    <!-- ============================================
         STATS CARDS WITH PREMIUM ANIMATIONS
    ============================================ -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="ju-card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted mb-2" style="font-size: 0.8rem; letter-spacing: 0.5px;">TOTAL USERS</h6>
                            <h3 class="mb-0" style="color: var(--ju-blue); font-weight: 800; font-size: 2.2rem;">{{ $stats['total_users'] }}</h3>
                            <div class="mt-2">
                                <span class="badge" style="background: var(--ju-blue-soft); color: var(--ju-blue);">
                                    <i class="fas fa-arrow-up me-1" style="color: var(--ju-gold);"></i> +12% this month
                                </span>
                            </div>
                        </div>
                        <div class="icon-circle">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('users.index') }}" class="btn btn-primary-ju-outline btn-sm w-100">
                            <i class="fas fa-arrow-right me-2"></i>View Users
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="ju-card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted mb-2" style="font-size: 0.8rem; letter-spacing: 0.5px;">TOTAL ROLES</h6>
                            <h3 class="mb-0" style="color: var(--ju-blue); font-weight: 800; font-size: 2.2rem;">{{ $stats['total_roles'] }}</h3>
                            <div class="mt-2">
                                <span class="badge" style="background: var(--ju-blue-soft); color: var(--ju-blue);">
                                    <i class="fas fa-shield-alt me-1" style="color: var(--ju-gold);"></i> Active
                                </span>
                            </div>
                        </div>
                        <div class="icon-circle">
                            <i class="fas fa-user-tag fa-2x"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('roles.index') }}" class="btn btn-primary-ju-outline btn-sm w-100">
                            <i class="fas fa-arrow-right me-2"></i>View Roles
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="ju-card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted mb-2" style="font-size: 0.8rem; letter-spacing: 0.5px;">TOTAL PERMISSIONS</h6>
                            <h3 class="mb-0" style="color: var(--ju-blue); font-weight: 800; font-size: 2.2rem;">{{ $stats['total_permissions'] }}</h3>
                            <div class="mt-2">
                                <span class="badge" style="background: var(--ju-blue-soft); color: var(--ju-blue);">
                                    <i class="fas fa-key me-1" style="color: var(--ju-gold);"></i> System
                                </span>
                            </div>
                        </div>
                        <div class="icon-circle">
                            <i class="fas fa-key fa-2x"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('permissions.index') }}" class="btn btn-primary-ju-outline btn-sm w-100">
                            <i class="fas fa-arrow-right me-2"></i>View Permissions
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="ju-card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted mb-2" style="font-size: 0.8rem; letter-spacing: 0.5px;">ACTIVE SESSIONS</h6>
                            <h3 class="mb-0" style="color: var(--ju-blue); font-weight: 800; font-size: 2.2rem;">{{ $stats['active_sessions'] }}</h3>
                            <div class="mt-2">
                                <span class="badge" style="background: #e3f1e3; color: #28a745;">
                                    <i class="fas fa-circle me-1" style="color: #28a745;"></i> Live
                                </span>
                            </div>
                        </div>
                        <div class="icon-circle">
                            <i class="fas fa-user-clock fa-2x"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="badge badge-primary-ju w-100 py-2" style="font-size: 0.9rem;">
                            <i class="fas fa-chart-line me-2"></i>Real-time
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================
         MAIN CONTENT ROW
    ============================================ -->
    <div class="row g-4 mb-4">
        <!-- Recent Users -->
        <div class="col-lg-6">
            <div class="ju-card h-100">
                <div class="ju-card-header d-flex align-items-center">
                    <i class="fas fa-history me-2" style="color: var(--ju-gold);"></i>
                    <h5 class="ju-card-title mb-0">Recent Users</h5>
                    <span class="ms-auto badge badge-primary-ju-soft">Last 7 days</span>
                </div>
                <div class="ju-card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Joined</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentUsers as $user)
                                <tr class="user-row" onclick="window.location.href='{{ route('users.show', $user) }}'" style="cursor: pointer;">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar-sm me-3">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <strong style="color: var(--ju-blue);">{{ $user->name }}</strong>
                                                <small class="text-muted d-block">{{ $user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($user->role)
                                            <span class="badge badge-primary-ju-soft px-3 py-2">{{ $user->role->name }}</span>
                                        @else
                                            <span class="badge badge-secondary-soft">No Role</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $user->created_at->format('M d, Y') }}</strong>
                                            <small class="text-muted d-block">{{ $user->created_at->format('h:i A') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-success-soft px-3 py-2">
                                            <i class="fas fa-check-circle me-1"></i>Active
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="ju-card-footer bg-white p-3 text-center border-top">
                    <a href="{{ route('users.index') }}" class="btn btn-primary-ju px-5">
                        <i class="fas fa-users me-2"></i>View All Users
                    </a>
                </div>
            </div>
        </div>
        
        <!-- User Growth Chart -->
        <div class="col-lg-6">
            <div class="ju-card h-100">
                <div class="ju-card-header d-flex align-items-center">
                    <i class="fas fa-chart-bar me-2" style="color: var(--ju-gold);"></i>
                    <h5 class="ju-card-title mb-0">User Growth</h5>
                    <span class="ms-auto badge badge-primary-ju-soft">Monthly</span>
                </div>
                <div class="ju-card-body">
                    <div class="chart-container">
                        <canvas id="userGrowthChart"></canvas>
                    </div>
                    <div class="mt-4 d-flex justify-content-center gap-4">
                        <div class="d-flex align-items-center">
                            <span class="d-inline-block me-2" style="width: 12px; height: 12px; background: #002789; border-radius: 4px;"></span>
                            <small class="text-muted">New Users</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="d-inline-block me-2" style="width: 20px; height: 3px; background: var(--ju-gold); border-radius: 2px;"></span>
                            <small class="text-muted">Cumulative</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================
         BOTTOM ROW
    ============================================ -->
    <div class="row g-4">
        <!-- Role Distribution -->
        <div class="col-lg-5">
            <div class="ju-card h-100">
                <div class="ju-card-header d-flex align-items-center">
                    <i class="fas fa-chart-pie me-2" style="color: var(--ju-gold);"></i>
                    <h5 class="ju-card-title mb-0">Role Distribution</h5>
                    <span class="ms-auto badge badge-primary-ju-soft">{{ $userRoleDistribution->count() }} roles</span>
                </div>
                <div class="ju-card-body">
                    <div class="chart-container" style="height: 200px;">
                        <canvas id="roleDistributionChart"></canvas>
                    </div>
                    <div class="mt-4">
                        @foreach($userRoleDistribution as $role)
                        <div class="role-distribution-item d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fas fa-circle me-2" style="color: {{ ['#002789', '#1a3a9a', '#3a6ab0', '#C4A747', '#a5862e'][$loop->index % 5] }}; font-size: 0.6rem;"></i>
                                <strong style="color: var(--ju-blue);">{{ $role->name }}</strong>
                            </span>
                            <span class="badge badge-primary-ju-soft px-3 py-2">{{ $role->users_count }} users</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="col-lg-7">
            <div class="ju-card h-100">
                <div class="ju-card-header d-flex align-items-center">
                    <i class="fas fa-bolt me-2" style="color: var(--ju-gold);"></i>
                    <h5 class="ju-card-title mb-0">Quick Actions</h5>
                    <span class="ms-auto badge badge-primary-ju-soft">Shortcuts</span>
                </div>
                <div class="ju-card-body">
                    <div class="row g-3">
                        @if(auth()->user()->hasPermission('create_users'))
                        <div class="col-md-6">
                            <a href="{{ route('users.create') }}" class="quick-action-btn p-3">
                                <div class="icon-circle me-3">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold" style="color: var(--ju-blue);">Add User</h6>
                                    <small class="text-muted">Create new account</small>
                                </div>
                                <i class="fas fa-arrow-right ms-3"></i>
                            </a>
                        </div>
                        @endif
                        
                        @if(auth()->user()->hasPermission('create_roles'))
                        <div class="col-md-6">
                            <a href="{{ route('roles.create') }}" class="quick-action-btn p-3">
                                <div class="icon-circle me-3">
                                    <i class="fas fa-user-tag"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold" style="color: var(--ju-blue);">Create Role</h6>
                                    <small class="text-muted">Define new role</small>
                                </div>
                                <i class="fas fa-arrow-right ms-3"></i>
                            </a>
                        </div>
                        @endif
                        
                        @if(auth()->user()->hasPermission('create_permissions'))
                        <div class="col-md-6">
                            <a href="{{ route('permissions.create') }}" class="quick-action-btn p-3">
                                <div class="icon-circle me-3">
                                    <i class="fas fa-key"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold" style="color: var(--ju-blue);">Add Permission</h6>
                                    <small class="text-muted">Set access rights</small>
                                </div>
                                <i class="fas fa-arrow-right ms-3"></i>
                            </a>
                        </div>
                        @endif
                        
                        <div class="col-md-6">
                            <a href="#" class="quick-action-btn p-3">
                                <div class="icon-circle me-3">
                                    <i class="fas fa-calendar-plus"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold" style="color: var(--ju-blue);">Schedule Event</h6>
                                    <small class="text-muted">Plan new event</small>
                                </div>
                                <i class="fas fa-arrow-right ms-3"></i>
                            </a>
                        </div>
                        
                        <div class="col-md-6">
                            <a href="#" class="quick-action-btn p-3">
                                <div class="icon-circle me-3">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold" style="color: var(--ju-blue);">Generate Report</h6>
                                    <small class="text-muted">Analytics & insights</small>
                                </div>
                                <i class="fas fa-arrow-right ms-3"></i>
                            </a>
                        </div>
                        
                        <div class="col-md-6">
                            <a href="#" class="quick-action-btn p-3">
                                <div class="icon-circle me-3">
                                    <i class="fas fa-cog"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold" style="color: var(--ju-blue);">Settings</h6>
                                    <small class="text-muted">System configuration</small>
                                </div>
                                <i class="fas fa-arrow-right ms-3"></i>
                            </a>
                        </div>
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
        // ============================================
        // ROLE DISTRIBUTION CHART - Royal Blue Theme
        // ============================================
        const roleCtx = document.getElementById('roleDistributionChart').getContext('2d');
        const roleData = @json($userRoleDistribution->pluck('users_count'));
        const roleLabels = @json($userRoleDistribution->pluck('name'));
        
        new Chart(roleCtx, {
            type: 'doughnut',
            data: {
                labels: roleLabels,
                datasets: [{
                    data: roleData,
                    backgroundColor: [
                        '#002789', // Royal Blue
                        '#1a3a9a', // Lighter Blue
                        '#3a6ab0', // Even Lighter
                        '#C4A747', // Gold
                        '#a5862e', // Dark Gold
                        '#5a7ec9', // Light Blue
                    ],
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverOffset: 12,
                    hoverBorderColor: '#002789',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        backgroundColor: '#002789',
                        titleColor: '#fff',
                        bodyColor: '#e6ebf7',
                        padding: 12,
                        cornerRadius: 8,
                    }
                },
                hover: {
                    animationDuration: 300
                }
            }
        });
        
        // ============================================
        // USER GROWTH CHART - Royal Blue & Gold
        // ============================================
        @php
            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $monthlyUsers = $monthlyUsers ?? [12, 19, 27, 35, 42, 48, 55, 62, 70, 78, 85, 92];
            $cumulative = $cumulative ?? [12, 31, 58, 93, 135, 183, 238, 300, 370, 448, 533, 625];
        @endphp
        
        const growthCtx = document.getElementById('userGrowthChart').getContext('2d');
        new Chart(growthCtx, {
            type: 'bar',
            data: {
                labels: @json($months),
                datasets: [
                    {
                        label: 'New Users',
                        data: @json($monthlyUsers),
                        backgroundColor: '#002789',
                        borderRadius: 6,
                        barPercentage: 0.6,
                        categoryPercentage: 0.8,
                    },
                    {
                        label: 'Cumulative',
                        data: @json($cumulative),
                        type: 'line',
                        borderColor: '#C4A747',
                        backgroundColor: 'transparent',
                        borderWidth: 3,
                        pointBorderColor: '#002789',
                        pointBackgroundColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 7,
                        pointHoverBackgroundColor: '#C4A747',
                        pointHoverBorderColor: '#002789',
                        tension: 0.2,
                        yAxisID: 'y1',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 39, 137, 0.06)',
                        },
                        title: {
                            display: true,
                            text: 'New users',
                            color: '#002789',
                            font: { size: 11, weight: '600' }
                        }
                    },
                    y1: {
                        position: 'right',
                        beginAtZero: true,
                        grid: {
                            drawOnChartArea: false,
                        },
                        title: {
                            display: true,
                            text: 'Total users',
                            color: '#C4A747',
                            font: { size: 11, weight: '600' }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        backgroundColor: '#002789',
                        titleColor: '#fff',
                        bodyColor: '#e6ebf7',
                        padding: 12,
                        cornerRadius: 8,
                    },
                    legend: {
                        display: false,
                    }
                }
            }
        });
    });
</script>
@endpush