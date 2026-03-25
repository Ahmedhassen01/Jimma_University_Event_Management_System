@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card ju-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-calendar-plus me-2"></i>Event Requests
                        </h4>
                        <a href="{{ route('event-requests.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i> New Request
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Status Tabs -->
                    <ul class="nav nav-tabs ju-tabs mb-4" id="requestsTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ !request()->has('status') ? 'active' : '' }}" 
                               href="{{ route('event-requests.index') }}">
                                All Requests
                                <span class="badge bg-secondary ms-1">{{ $totalCount ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ request('status') == 'pending' ? 'active' : '' }}" 
                               href="{{ route('event-requests.index', ['status' => 'pending']) }}">
                                Pending
                                <span class="badge bg-warning ms-1">{{ $pendingCount ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ request('status') == 'approved' ? 'active' : '' }}" 
                               href="{{ route('event-requests.index', ['status' => 'approved']) }}">
                                Approved
                                <span class="badge bg-success ms-1">{{ $approvedCount ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ request('status') == 'rejected' ? 'active' : '' }}" 
                               href="{{ route('event-requests.index', ['status' => 'rejected']) }}">
                                Rejected
                                <span class="badge bg-danger ms-1">{{ $rejectedCount ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ request('status') == 'cancelled' ? 'active' : '' }}" 
                               href="{{ route('event-requests.index', ['status' => 'cancelled']) }}">
                                Cancelled
                                <span class="badge bg-secondary ms-1">{{ $cancelledCount ?? 0 }}</span>
                            </a>
                        </li>
                    </ul>

                    <!-- Search and Filter -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <form method="GET" action="{{ route('event-requests.index') }}">
                                <div class="input-group">
                                    <input type="text" class="form-control ju-input" name="search" 
                                           placeholder="Search by event title, organizer name..." 
                                           value="{{ request('search') }}">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search"></i> Search
                                    </button>
                                    @if(request()->hasAny(['search', 'status']))
                                    <a href="{{ route('event-requests.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Clear
                                    </a>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Requests Table -->
                    <div class="table-responsive">
                        <table class="table table-hover ju-table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Requested By</th>
                                    <th>Proposed Date</th>
                                    <th>Venue</th>
                                    <th>Status</th>
                                    <th>Submitted</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($eventRequests as $request)
                                <tr>
                                    <td>
                                        <strong>{{ $request->title }}</strong><br>
                                        <small class="text-muted">{{ $request->event_type }}</small>
                                    </td>
                                    <td>
                                        {{ $request->user->name ?? 'N/A' }}<br>
                                        <small class="text-muted">{{ $request->organizer_email }}</small>
                                    </td>
                                    <td>
                                        {{ $request->proposed_start_date->format('M d, Y') }}<br>
                                        <small class="text-muted">{{ $request->proposed_start_date->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        {{ $request->proposed_venue ?? 'Not specified' }}<br>
                                        <small class="text-muted">{{ $request->proposed_campus ?? 'Not specified' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $request->status_color ?? 'secondary' }}">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                        @if($request->reviewed_by)
                                        <br>
                                        <small class="text-muted">
                                            Reviewed by {{ $request->reviewer->name ?? 'Admin' }}
                                        </small>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $request->created_at->format('M d, Y') }}<br>
                                        <small class="text-muted">{{ $request->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <!-- View Button -->
                                            <a href="{{ route('event-requests.show', $request) }}" 
                                               class="btn btn-info" 
                                               title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            @if($request->status === 'pending')
                                                <!-- Edit Button (Owner or Admin) -->
                                                @if(Auth::id() === $request->user_id || Auth::user()->hasPermission('manage_event_requests'))
                                                    <a href="{{ route('event-requests.edit', $request) }}" 
                                                       class="btn btn-warning" 
                                                       title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endif
                                                
                                                <!-- Cancel Button (Owner) -->
                                                @if(Auth::id() === $request->user_id)
                                                    <button type="button" 
                                                            class="btn btn-secondary" 
                                                            title="Cancel"
                                                            onclick="cancelRequest({{ $request->id }})">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                @endif
                                            @endif
                                            
                                            <!-- Admin Actions -->
                                            @if(Auth::user()->hasRole('super-admin') || Auth::user()->hasRole('super-administrator') || Auth::user()->hasRole('administrator') || Auth::user()->hasRole('admin') || Auth::user()->isAdmin())
                                                @if($request->status === 'pending')
                                                    <!-- Approve Button -->
                                                    <button type="button" 
                                                            class="btn btn-success"
                                                            title="Approve"
                                                            onclick="approveRequest({{ $request->id }})">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    
                                                    <!-- Reject Button -->
                                                    <button type="button" 
                                                            class="btn btn-danger"
                                                            title="Reject"
                                                            onclick="rejectRequest({{ $request->id }})">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="empty-state">
                                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                            <h5>No event requests found</h5>
                                            <p class="text-muted">
                                                @if(request()->hasAny(['search', 'status']))
                                                    Try adjusting your search or filter
                                                @else
                                                    There are no event requests yet
                                                @endif
                                            </p>
                                            @if(!request()->hasAny(['search', 'status']))
                                            <a href="{{ route('event-requests.create') }}" class="btn btn-primary mt-2">
                                                <i class="fas fa-plus me-1"></i> Create First Request
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($eventRequests->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $eventRequests->withQueryString()->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.ju-card {
    border-radius: 10px;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.ju-tabs .nav-link {
    border: none;
    color: #6c757d;
    padding: 10px 20px;
    font-weight: 500;
}

.ju-tabs .nav-link.active {
    color: #0d6efd;
    border-bottom: 3px solid #0d6efd;
    background-color: transparent;
}

.ju-input {
    border-radius: 5px;
    border: 1px solid #dee2e6;
    padding: 8px 15px;
}

.ju-table th {
    border-top: none;
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    color: #495057;
}

.ju-table tbody tr:hover {
    background-color: #f8f9fa;
}

.empty-state {
    padding: 40px 0;
    text-align: center;
}

.btn-group .btn {
    border-radius: 4px !important;
    margin: 0 2px;
}
</style>

<script>
// Approve request - SIMPLE WORKING VERSION
function approveRequest(requestId) {
    if (confirm('Are you sure you want to approve this event request?')) {
        // Create form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/event-requests/${requestId}/approve`;
        form.style.display = 'none';
        
        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // Add to body and submit
        document.body.appendChild(form);
        form.submit();
    }
}

// Reject request - SIMPLE WORKING VERSION  
function rejectRequest(requestId) {
    const reason = prompt('Please provide a reason for rejection:');
    if (reason !== null && reason.trim() !== '') {
        // Create form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/event-requests/${requestId}/reject`;
        form.style.display = 'none';
        
        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // Add review notes
        const reviewNotes = document.createElement('input');
        reviewNotes.type = 'hidden';
        reviewNotes.name = 'review_notes';
        reviewNotes.value = reason;
        form.appendChild(reviewNotes);
        
        // Add to body and submit
        document.body.appendChild(form);
        form.submit();
    }
}

// Cancel request - SIMPLE WORKING VERSION
function cancelRequest(requestId) {
    if (confirm('Are you sure you want to cancel this event request?')) {
        // Create form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/event-requests/${requestId}/cancel`;
        form.style.display = 'none';
        
        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // Add to body and submit
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection

