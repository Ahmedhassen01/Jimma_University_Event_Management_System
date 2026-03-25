{{-- resources/views/layouts/admin.blade.php --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Admin Dashboard') - JU Event Management</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom Admin Styles -->
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background: #f5f7fb;
        }
        
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .admin-sidebar {
            width: 280px;
            background: white;
            box-shadow: 2px 0 10px rgba(0,0,0,0.02);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transition: all 0.3s;
            z-index: 1000;
        }
        
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .sidebar-header h3 {
            color: #0A3D62;
            font-weight: 700;
            font-size: 1.4rem;
            margin: 0;
        }
        
        .sidebar-header small {
            color: #6c757d;
            font-size: 0.8rem;
        }
        
        .sidebar-menu {
            padding: 1.5rem 0;
        }
        
        .menu-section {
            margin-bottom: 1.5rem;
        }
        
        .menu-section-title {
            padding: 0 1.5rem;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #adb5bd;
            margin-bottom: 0.5rem;
        }
        
        .menu-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: #495057;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        
        .menu-item:hover {
            background: #f8f9fa;
            color: #0A3D62;
            border-left-color: #0A3D62;
        }
        
        .menu-item.active {
            background: #e6f0fa;
            color: #0A3D62;
            border-left-color: #0A3D62;
            font-weight: 500;
        }
        
        .menu-item i {
            width: 24px;
            font-size: 1.2rem;
            margin-right: 0.75rem;
        }
        
        /* Main Content */
        .admin-main {
            flex: 1;
            margin-left: 280px;
            padding: 1.5rem;
        }
        
        /* Top Navbar */
        .admin-navbar {
            background: white;
            border-radius: 20px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .navbar-left {
            display: flex;
            align-items: center;
        }
        
        .page-title-area {
            margin-left: 1rem;
        }
        
        .page-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }
        
        .page-subtitle {
            font-size: 0.8rem;
            color: #6c757d;
        }
        
        .breadcrumb {
            margin-bottom: 0;
            background: none;
            padding: 0;
        }
        
        .breadcrumb-item a {
            color: #6c757d;
            text-decoration: none;
        }
        
        .breadcrumb-item.active {
            color: #0A3D62;
        }
        
        .navbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .notification-btn {
            position: relative;
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            color: #495057;
            transition: all 0.3s;
        }
        
        .notification-btn:hover {
            background: #e6f0fa;
            color: #0A3D62;
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: white;
            font-size: 0.6rem;
            padding: 0.2rem 0.5rem;
            border-radius: 30px;
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem;
            border-radius: 12px;
            background: #f8f9fa;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .user-menu:hover {
            background: #e6f0fa;
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: #0A3D62;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        
        .user-info {
            line-height: 1.3;
        }
        
        .user-name {
            font-size: 0.9rem;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .user-role {
            font-size: 0.7rem;
            color: #6c757d;
        }
        
        /* Content Area */
        .admin-content {
            background: transparent;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .admin-sidebar {
                left: -280px;
            }
            
            .admin-sidebar.show {
                left: 0;
            }
            
            .admin-main {
                margin-left: 0;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-header">
                <h3>JU Events</h3>
                <small>Administration Panel</small>
            </div>
            
            <div class="sidebar-menu">
                <div class="menu-section">
                    <div class="menu-section-title">Main</div>
                    <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-chart-pie"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.events.index') }}" class="menu-item {{ request()->routeIs('admin.events*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Events</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="menu-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>Users</span>
                    </a>
                </div>
                
                <div class="menu-section">
                    <div class="menu-section-title">Management</div>
                    <a href="{{ route('admin.speakers.index') }}" class="menu-item {{ request()->routeIs('admin.speakers*') ? 'active' : '' }}">
                        <i class="fas fa-microphone-alt"></i>
                        <span>Speakers</span>
                    </a>
                    <a href="{{ route('admin.venues.index') }}" class="menu-item {{ request()->routeIs('admin.venues*') ? 'active' : '' }}">
                        <i class="fas fa-map-pin"></i>
                        <span>Venues</span>
                    </a>
                    <a href="{{ route('admin.event-requests.index') }}" class="menu-item {{ request()->routeIs('admin.event-requests*') ? 'active' : '' }}">
                        <i class="fas fa-file-signature"></i>
                        <span>Event Requests</span>
                        @if($pendingRequestsCount ?? 0 > 0)
                        <span class="badge bg-danger ms-auto">{{ $pendingRequestsCount }}</span>
                        @endif
                    </a>
                </div>
                
                <div class="menu-section">
                    <div class="menu-section-title">Content</div>
                    <a href="{{ route('admin.announcements.index') }}" class="menu-item">
                        <i class="fas fa-bullhorn"></i>
                        <span>Announcements</span>
                    </a>
                    <a href="{{ route('admin.feedback.index') }}" class="menu-item">
                        <i class="fas fa-star"></i>
                        <span>Feedback</span>
                    </a>
                    <a href="{{ route('admin.gallery.index') }}" class="menu-item">
                        <i class="fas fa-images"></i>
                        <span>Gallery</span>
                    </a>
                </div>
                
                <div class="menu-section">
                    <div class="menu-section-title">System</div>
                    <a href="{{ route('admin.roles.index') }}" class="menu-item">
                        <i class="fas fa-user-tag"></i>
                        <span>Roles & Permissions</span>
                    </a>
                    <a href="{{ route('admin.notifications.index') }}" class="menu-item">
                        <i class="fas fa-bell"></i>
                        <span>Notifications</span>
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="menu-item">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                    <a href="{{ route('admin.reports.index') }}" class="menu-item">
                        <i class="fas fa-file-alt"></i>
                        <span>Reports</span>
                    </a>
                </div>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="admin-main">
            <!-- Top Navbar -->
            <nav class="admin-navbar">
                <div class="navbar-left">
                    <button class="btn btn-icon d-lg-none" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <div class="page-title-area">
                        <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
                        @hasSection('page-subtitle')
                            <p class="page-subtitle">@yield('page-subtitle')</p>
                        @endif
                    </div>
                </div>
                
                <div class="navbar-right">
                    <button class="notification-btn">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </button>
                    
                    <div class="user-menu" data-bs-toggle="dropdown">
                        <div class="user-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="user-info d-none d-md-block">
                            <div class="user-name">{{ auth()->user()->name }}</div>
                            <div class="user-role">{{ auth()->user()->role->name ?? 'Admin' }}</div>
                        </div>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('admin.profile') }}"><i class="fas fa-user me-2"></i>Profile</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.settings') }}"><i class="fas fa-cog me-2"></i>Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>
            
            <!-- Breadcrumb -->
            @hasSection('breadcrumb')
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        @yield('breadcrumb-items')
                    </ol>
                </nav>
            @endif
            
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <!-- Main Content -->
            <div class="admin-content">
                @yield('content')
            </div>
        </main>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Sidebar Toggle
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('adminSidebar').classList.toggle('show');
        });
        
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                let bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
    
    @stack('scripts')
</body>
</html>