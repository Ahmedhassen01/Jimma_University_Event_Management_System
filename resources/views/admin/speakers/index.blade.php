@extends('layouts.app')

@section('title', 'Speakers Management - Jimma University')

@section('page-title', 'Speakers Management')
@section('page-subtitle', 'Manage all event speakers')

@section('breadcrumb-items')
    <li class="breadcrumb-item active">Speakers</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Quick Stats Row -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card" style="background: linear-gradient(135deg, #003366 0%, #004080 100%);">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number">{{ $totalCount }}</div>
                <div class="stat-label">Total Speakers</div>
                <div class="stat-trend">
                    <i class="fas fa-arrow-up me-1"></i> All time
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card" style="background: linear-gradient(135deg, #28a745 0%, #34ce57 100%);">
                <div class="stat-icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-number">{{ $activeCount }}</div>
                <div class="stat-label">Active Speakers</div>
                <div class="stat-trend">
                    <i class="fas fa-circle me-1" style="color: #fff;"></i> Currently active
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card" style="background: linear-gradient(135deg, #ffc107 0%, #ffdb6d 100%);">
                <div class="stat-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-number">{{ $featuredCount }}</div>
                <div class="stat-label">Featured Speakers</div>
                <div class="stat-trend">
                    <i class="fas fa-star me-1"></i> Top performers
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card" style="background: linear-gradient(135deg, #17a2b8 0%, #4dd0e1 100%);">
                <div class="stat-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stat-number">{{ $upcomingTalks }}</div>
                <div class="stat-label">Upcoming Talks</div>
                <div class="stat-trend">
                    <i class="fas fa-clock me-1"></i> Next 30 days
                </div>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="ju-card shadow-sm">
        <div class="ju-card-header d-flex justify-content-between align-items-center py-3" style="background: linear-gradient(135deg, #003366 0%, #004080 100%);">
            <h5 class="ju-card-title text-white mb-0">
                <i class="fas fa-microphone-alt me-2"></i>
                Speakers Directory
            </h5>
            <div>
                <a href="{{ route('speakers.create') }}" class="btn btn-light">
                    <i class="fas fa-plus-circle me-2"></i> Add New Speaker
                </a>
            </div>
        </div>
        
        <div class="ju-card-body">
            <!-- Advanced Filters -->
            <div class="filters-section mb-4 p-3 bg-light rounded">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold" style="color: #003366;">
                            <i class="fas fa-search me-2"></i>Search
                        </label>
                        <div class="input-group">
                            <span class="input-group-text" style="border-color: #003366; background-color: white;">
                                <i class="fas fa-search" style="color: #003366;"></i>
                            </span>
                            <input type="text" 
                                   class="form-control" 
                                   id="searchInput" 
                                   placeholder="Search by name, title, organization..." 
                                   value="{{ request('search') }}"
                                   style="border-color: #003366;">
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label fw-bold" style="color: #003366;">
                            <i class="fas fa-filter me-2"></i>Status
                        </label>
                        <select class="form-select" id="statusFilter" style="border-color: #003366;">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label fw-bold" style="color: #003366;">
                            <i class="fas fa-tag me-2"></i>Department
                        </label>
                        <select class="form-select" id="departmentFilter" style="border-color: #003366;">
                            <option value="">All Departments</option>
                            <option value="Computer Science" {{ request('department') == 'Computer Science' ? 'selected' : '' }}>Computer Science</option>
                            <option value="Engineering" {{ request('department') == 'Engineering' ? 'selected' : '' }}>Engineering</option>
                            <option value="Medicine" {{ request('department') == 'Medicine' ? 'selected' : '' }}>Medicine</option>
                            <option value="Business" {{ request('department') == 'Business' ? 'selected' : '' }}>Business</option>
                            <option value="Arts" {{ request('department') == 'Arts' ? 'selected' : '' }}>Arts</option>
                            <option value="Science" {{ request('department') == 'Science' ? 'selected' : '' }}>Science</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2 d-flex align-items-end">
                        <div class="d-flex gap-2 w-100">
                            <button class="btn flex-grow-1" style="background-color: #003366; color: white;" id="applyFilters">
                                <i class="fas fa-filter me-2"></i> Apply
                            </button>
                            <a href="{{ route('speakers.index') }}" class="btn btn-outline-secondary" style="border-color: #003366; color: #003366;">
                                <i class="fas fa-redo-alt"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Active Filters Display -->
                @if(request()->has('search') || request()->has('status') || request()->has('department'))
                <div class="active-filters mt-3 pt-2 border-top">
                    <span class="badge bg-light text-dark me-2 p-2">
                        <i class="fas fa-filter me-1" style="color: #003366;"></i> Active Filters:
                    </span>
                    @if(request('search'))
                    <span class="badge bg-white text-dark border me-2 p-2">
                        Search: "{{ request('search') }}"
                        <a href="{{ route('speakers.index', array_merge(request()->except('search'), ['page' => null])) }}" class="text-danger ms-2">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                    @endif
                    @if(request('status'))
                    <span class="badge bg-white text-dark border me-2 p-2">
                        Status: {{ ucfirst(request('status')) }}
                        <a href="{{ route('speakers.index', array_merge(request()->except('status'), ['page' => null])) }}" class="text-danger ms-2">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                    @endif
                    @if(request('department'))
                    <span class="badge bg-white text-dark border me-2 p-2">
                        Department: {{ request('department') }}
                        <a href="{{ route('speakers.index', array_merge(request()->except('department'), ['page' => null])) }}" class="text-danger ms-2">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                    @endif
                </div>
                @endif
            </div>

            <!-- Speakers Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="speakersTable">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th class="ps-3" style="color: #003366; font-weight: 600;">Speaker</th>
                            <th style="color: #003366; font-weight: 600;">Title & Department</th>
                            <th style="color: #003366; font-weight: 600;">Contact</th>
                            <th style="color: #003366; font-weight: 600;">Events</th>
                            <th style="color: #003366; font-weight: 600;">Status</th>
                            <th style="color: #003366; font-weight: 600; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($speakers as $speaker)
                        <tr class="speaker-row" data-speaker-id="{{ $speaker->id }}">
                            <td class="ps-3">
                                <div class="d-flex align-items-center">
                                    <div class="speaker-avatar me-3">
                                        @if($speaker->photo)
                                        <img src="{{ $speaker->photo_url }}" alt="{{ $speaker->name }}" 
                                             class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover; border: 2px solid #003366;">
                                        @else
                                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px; background: linear-gradient(135deg, #003366 0%, #004080 100%); color: white;">
                                            <i class="fas fa-user fa-lg"></i>
                                        </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $speaker->full_name }}</div>
                                        <small class="text-muted">
                                            <i class="fas fa-tag me-1" style="color: #003366;"></i>
                                            {{ $speaker->title }}
                                        </small>
                                        @if($speaker->is_featured)
                                        <span class="badge ms-2" style="background: linear-gradient(135deg, #ffc107 0%, #ffdb6d 100%); color: #000;">
                                            <i class="fas fa-star me-1"></i> Featured
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $speaker->position ?: 'Not specified' }}</div>
                                <small class="text-muted">
                                    <i class="fas fa-building me-1" style="color: #003366;"></i>
                                    {{ $speaker->organization ?: 'No organization' }}
                                </small>
                            </td>
                            <td>
                                <div class="mb-1">
                                    <i class="fas fa-envelope me-2" style="color: #003366;"></i>
                                    <a href="mailto:{{ $speaker->email }}" class="text-decoration-none">{{ $speaker->email }}</a>
                                </div>
                                @if($speaker->phone)
                                <div>
                                    <i class="fas fa-phone me-2" style="color: #003366;"></i>
                                    <a href="tel:{{ $speaker->phone }}" class="text-decoration-none">{{ $speaker->phone }}</a>
                                </div>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="event-count-badge me-2" style="width: 40px; height: 40px; background: rgba(0, 51, 102, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <span class="fw-bold" style="color: #003366;">{{ $speaker->events_count }}</span>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Total events</small>
                                        @if($speaker->upcoming_events_count > 0)
                                        <small class="text-success">
                                            <i class="fas fa-clock me-1"></i>{{ $speaker->upcoming_events_count }} upcoming
                                        </small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <form action="{{ route('speakers.toggle-active', $speaker) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="btn btn-sm status-toggle" 
                                            style="{{ $speaker->is_active ? 'background-color: #28a745; color: white;' : 'background-color: #6c757d; color: white;' }}"
                                            title="{{ $speaker->is_active ? 'Click to deactivate' : 'Click to activate' }}">
                                        <i class="fas {{ $speaker->is_active ? 'fa-check-circle' : 'fa-circle' }} me-1"></i>
                                        {{ $speaker->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('speakers.show', $speaker) }}" 
                                       class="btn btn-sm btn-outline-info" 
                                       title="View Details"
                                       style="border-color: #17a2b8; color: #17a2b8;">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('speakers.edit', $speaker) }}" 
                                       class="btn btn-sm btn-outline-warning" 
                                       title="Edit Speaker"
                                       style="border-color: #ffc107; color: #ffc107;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.events.speakers.manage', ['event' => $speaker->events->first() ?? 0]) }}" 
                                       class="btn btn-sm btn-outline-success" 
                                       title="Manage Events"
                                       style="border-color: #28a745; color: #28a745;">
                                        <i class="fas fa-calendar-alt"></i>
                                    </a>
                                    <form action="{{ route('speakers.destroy', $speaker) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete" 
                                                style="border-color: #dc3545; color: #dc3545;"
                                                onclick="return confirm('Are you sure you want to delete this speaker? This action cannot be undone.')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="empty-state">
                                    <div class="mb-4">
                                        <i class="fas fa-microphone-alt fa-4x" style="color: #003366; opacity: 0.5;"></i>
                                    </div>
                                    <h4 class="text-muted mb-3">No Speakers Found</h4>
                                    <p class="text-muted mb-4">Get started by adding your first speaker to the system.</p>
                                    <a href="{{ route('speakers.create') }}" class="btn btn-lg" style="background-color: #003366; color: white;">
                                        <i class="fas fa-plus-circle me-2"></i> Add Your First Speaker
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination and Info -->
            @if($speakers->hasPages() || $speakers->total() > 0)
            <div class="d-flex flex-wrap justify-content-between align-items-center mt-4 pt-3 border-top">
                <div class="text-muted small">
                    <i class="fas fa-info-circle me-1" style="color: #003366;"></i>
                    Showing <span class="fw-semibold">{{ $speakers->firstItem() }}</span> to 
                    <span class="fw-semibold">{{ $speakers->lastItem() }}</span> of 
                    <span class="fw-semibold">{{ $speakers->total() }}</span> entries
                </div>
                <div class="mt-2 mt-sm-0">
                    {{ $speakers->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Stat Cards */
    .stat-card {
        padding: 1.5rem;
        border-radius: 15px;
        position: relative;
        overflow: hidden;
        color: white;
        box-shadow: 0 5px 15px rgba(0, 51, 102, 0.2);
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 51, 102, 0.3);
    }
    
    .stat-card .stat-icon {
        position: absolute;
        right: 20px;
        top: 20px;
        font-size: 3rem;
        opacity: 0.3;
    }
    
    .stat-card .stat-number {
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }
    
    .stat-card .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
        margin-bottom: 0.5rem;
    }
    
    .stat-card .stat-trend {
        font-size: 0.8rem;
        opacity: 0.8;
    }
    
    /* Table Styles */
    .table {
        margin-bottom: 0;
    }
    
    .table thead th {
        border-bottom-width: 2px;
        border-bottom-color: #003366;
        padding: 1rem 0.75rem;
    }
    
    .speaker-row {
        transition: all 0.2s ease;
    }
    
    .speaker-row:hover {
        background-color: rgba(0, 51, 102, 0.05);
    }
    
    /* Button Styles */
    .btn-outline-info:hover,
    .btn-outline-warning:hover,
    .btn-outline-success:hover,
    .btn-outline-danger:hover {
        color: white !important;
    }
    
    .btn-outline-info:hover {
        background-color: #17a2b8;
    }
    
    .btn-outline-warning:hover {
        background-color: #ffc107;
        color: #000 !important;
    }
    
    .btn-outline-success:hover {
        background-color: #28a745;
    }
    
    .btn-outline-danger:hover {
        background-color: #dc3545;
    }
    
    /* Status Toggle Button */
    .status-toggle {
        border: none;
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        transition: all 0.2s ease;
        min-width: 90px;
    }
    
    .status-toggle:hover {
        transform: scale(1.05);
        opacity: 0.9;
    }
    
    /* Event Count Badge */
    .event-count-badge {
        transition: all 0.2s ease;
    }
    
    .speaker-row:hover .event-count-badge {
        transform: scale(1.1);
        background: rgba(0, 51, 102, 0.2) !important;
    }
    
    /* Empty State */
    .empty-state {
        padding: 3rem;
    }
    
    /* Filter Section */
    .filters-section {
        border-left: 4px solid #003366;
    }
    
    /* Form Controls Focus */
    .form-control:focus,
    .form-select:focus {
        border-color: #003366;
        box-shadow: 0 0 0 0.2rem rgba(0, 51, 102, 0.25);
    }
    
    /* Pagination Styling */
    .pagination {
        gap: 5px;
    }
    
    .page-link {
        color: #003366;
        border-radius: 5px;
        border: 1px solid #dee2e6;
        padding: 0.5rem 0.9rem;
    }
    
    .page-link:hover {
        background-color: #003366;
        color: white;
        border-color: #003366;
    }
    
    .page-item.active .page-link {
        background-color: #003366;
        border-color: #003366;
    }
    
    .page-item.disabled .page-link {
        color: #6c757d;
    }
    
    /* Active Filters */
    .active-filters .badge {
        font-size: 0.85rem;
        padding: 0.5rem 1rem;
    }
    
    .active-filters .badge a {
        text-decoration: none;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .stat-card {
            margin-bottom: 1rem;
        }
        
        .d-flex.gap-2 {
            flex-wrap: wrap;
        }
        
        .btn-group-sm {
            margin-top: 0.5rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Filter functionality
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const departmentFilter = document.getElementById('departmentFilter');
        const applyFilters = document.getElementById('applyFilters');
        
        applyFilters.addEventListener('click', function() {
            const params = new URLSearchParams();
            
            if (searchInput.value.trim()) {
                params.set('search', searchInput.value.trim());
            }
            
            if (statusFilter.value) {
                params.set('status', statusFilter.value);
            }
            
            if (departmentFilter.value) {
                params.set('department', departmentFilter.value);
            }
            
            window.location.href = '{{ route("speakers.index") }}?' + params.toString();
        });
        
        // Enter key to search
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                applyFilters.click();
            }
        });
        
        // Clear individual filters
        document.querySelectorAll('.active-filters .badge a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                window.location.href = this.href;
            });
        });
        
        // Status toggle confirmation
        document.querySelectorAll('.status-toggle').forEach(button => {
            button.addEventListener('click', function(e) {
                const currentStatus = this.textContent.trim();
                const newStatus = currentStatus === 'Active' ? 'deactivate' : 'activate';
                
                if (!confirm(`Are you sure you want to ${newStatus} this speaker?`)) {
                    e.preventDefault();
                }
            });
        });
        
        // Add smooth hover effects
        const rows = document.querySelectorAll('.speaker-row');
        rows.forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.transition = 'all 0.2s ease';
            });
        });
        
        // Tooltip initialization if using Bootstrap tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush