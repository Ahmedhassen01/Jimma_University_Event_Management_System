@extends('layouts.app')

@section('title', 'Admin Dashboard - JU Event Management')
@section('page-title', 'Admin Dashboard')
@section('page-subtitle', 'Welcome back, ' . auth()->user()->name)

@section('breadcrumb-items')
    <li class="breadcrumb-item active">Admin Dashboard</li>
@endsection

@section('content')
<div class="row">
    <!-- Stats Cards with Blue Hover Animations -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="ju-card h-100 stat-card">
            <div class="ju-card-body">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h6 class="text-muted mb-2">Total Users</h6>
                        <h3 class="mb-0">{{ $stats['total_users'] }}</h3>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon-circle bg-primary-ju text-white">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    @can('view_users')
                    <a href="{{ route('users.index') }}" class="btn btn-sm btn-primary-ju-outline">View Users</a>
                    @else
                    <span class="text-muted">Limited access</span>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="ju-card h-100 stat-card">
            <div class="ju-card-body">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h6 class="text-muted mb-2">Total Roles</h6>
                        <h3 class="mb-0">{{ $stats['total_roles'] }}</h3>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon-circle bg-primary-ju text-white">
                            <i class="fas fa-user-tag fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    @can('view_roles')
                    <a href="{{ route('roles.index') }}" class="btn btn-sm btn-primary-ju-outline">View Roles</a>
                    @else
                    <span class="text-muted">Limited access</span>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="ju-card h-100 stat-card">
            <div class="ju-card-body">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h6 class="text-muted mb-2">Total Permissions</h6>
                        <h3 class="mb-0">{{ $stats['total_permissions'] }}</h3>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon-circle bg-primary-ju text-white">
                            <i class="fas fa-key fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    @can('view_permissions')
                    <a href="{{ route('permissions.index') }}" class="btn btn-sm btn-primary-ju-outline">View Permissions</a>
                    @else
                    <span class="text-muted">Limited access</span>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="ju-card h-100 stat-card">
            <div class="ju-card-body">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h6 class="text-muted mb-2">Active Sessions</h6>
                        <h3 class="mb-0">{{ $stats['active_sessions'] }}</h3>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon-circle bg-primary-ju text-white">
                            <i class="fas fa-user-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="badge badge-primary-ju">Live</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Users (left column) -->
    <div class="col-lg-6 mb-4">
        <div class="ju-card h-100">
            <div class="ju-card-header bg-white">
                <h5 class="ju-card-title mb-0"><i class="fas fa-history me-2 text-primary-ju"></i>Recent Users</h5>
            </div>
            <div class="ju-card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
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
                            <tr class="user-row">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar-sm me-2 bg-primary-ju-light text-primary-ju">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        {{ $user->name }}
                                    </div>
                                </td>
                                <td>
                                    @if($user->role)
                                        <span class="badge bg-primary-ju-soft text-primary-ju-dark px-3 py-2">{{ $user->role->name }}</span>
                                    @else
                                        <span class="badge bg-secondary-soft">No Role</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge bg-success-soft text-success px-3 py-2">Active</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="text-center mt-3">
                    @can('view_users')
                    <a href="{{ route('users.index') }}" class="btn btn-primary-ju px-4">View All Users</a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bar Chart: User Growth (inspired by JU official style) -->
    <div class="col-lg-6 mb-4">
        <div class="ju-card h-100">
            <div class="ju-card-header bg-white">
                <h5 class="ju-card-title mb-0"><i class="fas fa-chart-bar me-2 text-primary-ju"></i>User Growth (Monthly)</h5>
            </div>
            <div class="ju-card-body">
                <div class="chart-container" style="height: 260px; position: relative;">
                    <canvas id="userGrowthChart"></canvas>
                </div>
                <div class="mt-3 text-center text-muted small">
                    <span class="mx-2"><i class="fas fa-circle text-primary-ju me-1"></i> New users</span>
                    <span class="mx-2"><i class="fas fa-circle text-primary-ju-light me-1"></i> Cumulative</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Role Distribution (Pie Chart) - now with blue scheme -->
    <div class="col-lg-5 mb-4">
        <div class="ju-card h-100">
            <div class="ju-card-header bg-white">
                <h5 class="ju-card-title mb-0"><i class="fas fa-chart-pie me-2 text-primary-ju"></i>User Role Distribution</h5>
            </div>
            <div class="ju-card-body d-flex flex-column">
                <div class="chart-container" style="height: 220px;">
                    <canvas id="roleDistributionChart"></canvas>
                </div>
                <div class="mt-4">
                    @foreach($userRoleDistribution as $role)
                    <div class="d-flex justify-content-between align-items-center mb-2 role-distribution-item">
                        <span><i class="fas fa-circle me-2" style="color: {{ ['#0D47A1', '#1565C0', '#1976D2', '#1E88E5', '#2196F3', '#42A5F5'][$loop->index % 6] }};"></i> {{ $role->name }}</span>
                        <span class="badge bg-primary-ju-soft text-primary-ju-dark px-3 py-2">{{ $role->users_count }} users</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions with Blue Hover -->
    <div class="col-lg-7 mb-4">
        <div class="ju-card h-100">
            <div class="ju-card-header bg-white">
                <h5 class="ju-card-title mb-0"><i class="fas fa-bolt me-2 text-primary-ju"></i>Quick Actions</h5>
            </div>
            <div class="ju-card-body">
                <div class="row g-3">
                    @can('create_users')
                    <div class="col-md-6 col-sm-6 mb-2">
                        <a href="{{ route('users.create') }}" class="quick-action-btn d-flex align-items-center p-3">
                            <div class="icon-circle bg-primary-ju-light text-primary-ju me-3">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-bold">Add User</h6>
                                <small class="text-muted">Create new account</small>
                            </div>
                            <i class="fas fa-arrow-right text-primary-ju"></i>
                        </a>
                    </div>
                    @endcan
                    
                    @can('create_roles')
                    <div class="col-md-6 col-sm-6 mb-2">
                        <a href="{{ route('roles.create') }}" class="quick-action-btn d-flex align-items-center p-3">
                            <div class="icon-circle bg-primary-ju-light text-primary-ju me-3">
                                <i class="fas fa-user-tag"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-bold">Create Role</h6>
                                <small class="text-muted">Define new role</small>
                            </div>
                            <i class="fas fa-arrow-right text-primary-ju"></i>
                        </a>
                    </div>
                    @endcan
                    
                    @can('create_permissions')
                    <div class="col-md-6 col-sm-6 mb-2">
                        <a href="{{ route('permissions.create') }}" class="quick-action-btn d-flex align-items-center p-3">
                            <div class="icon-circle bg-primary-ju-light text-primary-ju me-3">
                                <i class="fas fa-key"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-bold">Add Permission</h6>
                                <small class="text-muted">Set access rights</small>
                            </div>
                            <i class="fas fa-arrow-right text-primary-ju"></i>
                        </a>
                    </div>
                    @endcan
                    
                    @can('manage_events')
                    <div class="col-md-6 col-sm-6 mb-2">
                        <a href="{{ route('admin.events.create') }}" class="quick-action-btn d-flex align-items-center p-3">
                            <div class="icon-circle bg-primary-ju-light text-primary-ju me-3">
                                <i class="fas fa-calendar-plus"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-bold">Create Event</h6>
                                <small class="text-muted">Schedule new event</small>
                            </div>
                            <i class="fas fa-arrow-right text-primary-ju"></i>
                        </a>
                    </div>
                    @endcan
                    
                    @can('view_feedback_analytics')
                    <div class="col-md-6 col-sm-6 mb-2">
                        <a href="{{ route('feedback.analytics') }}" class="quick-action-btn d-flex align-items-center p-3">
                            <div class="icon-circle bg-primary-ju-light text-primary-ju me-3">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-bold">Analytics</h6>
                                <small class="text-muted">View reports & insights</small>
                            </div>
                            <i class="fas fa-arrow-right text-primary-ju"></i>
                        </a>
                    </div>
                    @endcan
                    
                    @can('manage_settings')
                    <div class="col-md-6 col-sm-6 mb-2">
                        <a href="#" class="quick-action-btn d-flex align-items-center p-3">
                            <div class="icon-circle bg-primary-ju-light text-primary-ju me-3">
                                <i class="fas fa-cog"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-bold">Settings</h6>
                                <small class="text-muted">System configuration</small>
                            </div>
                            <i class="fas fa-arrow-right text-primary-ju"></i>
                        </a>
                    </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Jimma University Official Blue Color Scheme */
    :root {
        --ju-blue: #0A3D62;      /* Primary JU Blue */
        --ju-blue-dark: #083452; /* Darker shade for hover */
        --ju-blue-light: #3a6e9f; /* Lighter shade */
        --ju-blue-soft: #e6f0fa; /* Soft background */
        --ju-blue-muted: #d4e4f5; /* Muted for accents */
        --ju-gray-soft: #f8f9fc;
    }

    /* Stat Cards with Hover Animation */
    .stat-card {
        transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        border: none;
        box-shadow: 0 2px 4px rgba(10, 61, 98, 0.08);
    }
    
    .stat-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 20px rgba(10, 61, 98, 0.15);
        border-left: 4px solid var(--ju-blue);
    }
    
    .icon-circle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    .stat-card:hover .icon-circle {
        transform: scale(1.08);
        background-color: var(--ju-blue-dark) !important;
    }
    
    /* Background utilities for blue scheme */
    .bg-primary-ju {
        background-color: var(--ju-blue) !important;
    }
    
    .bg-primary-ju-light {
        background-color: var(--ju-blue-soft) !important;
    }
    
    .bg-primary-ju-soft {
        background-color: #eef5fc !important;
    }
    
    .bg-success-soft {
        background-color: #e3f1e3 !important;
    }
    
    .bg-secondary-soft {
        background-color: #f0f2f5 !important;
    }
    
    .text-primary-ju {
        color: var(--ju-blue) !important;
    }
    
    .text-primary-ju-dark {
        color: var(--ju-blue-dark) !important;
    }
    
    .text-success {
        color: #1e7e34 !important;
    }
    
    .badge-primary-ju {
        background-color: var(--ju-blue);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
    }
    
    .badge-primary-ju-soft {
        background-color: #eef5fc;
        color: var(--ju-blue-dark);
        border-radius: 20px;
    }
    
    .btn-primary-ju {
        background-color: var(--ju-blue);
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 30px;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 2px 6px rgba(10, 61, 98, 0.2);
    }
    
    .btn-primary-ju:hover {
        background-color: var(--ju-blue-dark);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 14px rgba(10, 61, 98, 0.3);
    }
    
    .btn-primary-ju-outline {
        border: 1.5px solid var(--ju-blue);
        color: var(--ju-blue);
        background-color: transparent;
        border-radius: 30px;
        padding: 6px 18px;
        transition: all 0.3s ease;
    }
    
    .btn-primary-ju-outline:hover {
        background-color: var(--ju-blue);
        color: white;
        border-color: var(--ju-blue);
        transform: translateY(-2px);
        box-shadow: 0 5px 12px rgba(10, 61, 98, 0.2);
    }
    
    /* User Avatar */
    .user-avatar-sm {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s ease;
    }
    
    .user-row:hover .user-avatar-sm {
        background-color: var(--ju-blue) !important;
        color: white !important;
    }
    
    .user-row:hover {
        background-color: rgba(10, 61, 98, 0.03);
    }
    
    /* Table styles */
    .table-hover tbody tr:hover {
        background-color: rgba(10, 61, 98, 0.04);
    }
    
    .table thead th {
        border-bottom: 2px solid var(--ju-blue-muted);
        color: var(--ju-blue-dark);
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    /* Quick Action Buttons */
    .quick-action-btn {
        display: flex;
        align-items: center;
        text-decoration: none;
        color: #2c3e50;
        background-color: white;
        border-radius: 16px;
        border: 1px solid #edf2f7;
        transition: all 0.25s ease;
        box-shadow: 0 2px 5px rgba(0,0,0,0.02);
    }
    
    .quick-action-btn:hover {
        background-color: #f5faff;
        border-color: var(--ju-blue-light);
        transform: translateY(-3px);
        box-shadow: 0 12px 20px rgba(10, 61, 98, 0.1);
        text-decoration: none;
    }
    
    .quick-action-btn:hover .icon-circle {
        background-color: var(--ju-blue) !important;
        color: white !important;
    }
    
    .quick-action-btn .icon-circle {
        width: 48px;
        height: 48px;
        transition: all 0.25s ease;
    }
    
    .quick-action-btn i.fa-arrow-right {
        transition: transform 0.2s ease;
        opacity: 0.6;
    }
    
    .quick-action-btn:hover i.fa-arrow-right {
        transform: translateX(5px);
        opacity: 1;
        color: var(--ju-blue-dark) !important;
    }
    
    /* Card header styling to match JU official look */
    .ju-card-header {
        border-bottom: 1px solid #e9ecef;
        background-color: white !important;
        padding: 1.25rem 1.5rem;
    }
    
    .ju-card-title {
        color: var(--ju-blue-dark);
        font-weight: 600;
    }
    
    /* Role distribution item hover */
    .role-distribution-item {
        padding: 6px 8px;
        border-radius: 10px;
        transition: background 0.2s;
    }
    
    .role-distribution-item:hover {
        background-color: #f0f7fe;
    }
    
    /* Chart container */
    .chart-container {
        position: relative;
        width: 100%;
    }
    
    /* Custom scrollbar (optional) */
    ::-webkit-scrollbar-thumb {
        background: var(--ju-blue-light);
        border-radius: 10px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ============================================
        // 1. Role Distribution Chart (Doughnut) - Blue Theme
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
                        '#0D47A1', // Dark blue
                        '#1565C0', 
                        '#1976D2',
                        '#1E88E5',
                        '#2196F3',
                        '#42A5F5',
                        '#64B5F6'
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 8,
                    hoverBorderColor: '#0A3D62',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false, // Hide default legend; we have custom list
                    },
                    tooltip: {
                        backgroundColor: '#0A3D62',
                        titleColor: '#fff',
                        bodyColor: '#f0f7fc',
                    }
                },
                hover: {
                    animationDuration: 300
                }
            }
        });
        
        // ============================================
        // 2. User Growth Bar Chart (Monthly)
        // ============================================
        @php
            // Provide sample monthly growth data if not set
            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
            $monthlyUsers = $monthlyUsers ?? [12, 19, 27, 35, 42, 48];
            $cumulative = $cumulative ?? [12, 31, 58, 93, 135, 183];
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
                        backgroundColor: '#0A3D62',
                        borderRadius: 6,
                        barPercentage: 0.55,
                        categoryPercentage: 0.8,
                        borderSkipped: false,
                    },
                    {
                        label: 'Cumulative',
                        data: @json($cumulative),
                        type: 'line',
                        borderColor: '#3a6e9f',
                        backgroundColor: 'transparent',
                        borderWidth: 3,
                        pointBorderColor: '#0A3D62',
                        pointBackgroundColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 7,
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
                            color: 'rgba(10, 61, 98, 0.06)',
                        },
                        title: {
                            display: true,
                            text: 'New users',
                            color: '#0A3D62',
                            font: { size: 11, weight: '500' }
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
                            color: '#3a6e9f',
                            font: { size: 11, weight: '500' }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        backgroundColor: '#0A3D62',
                        titleColor: '#fff',
                        bodyColor: '#e6f0fa',
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