@extends('layouts.app')

@section('title', 'My Event Requests')
@section('page-title', 'My Event Requests')
@section('page-subtitle', 'Track and manage your submitted event requests')

@section('breadcrumb-items')
<li class="breadcrumb-item active">My Event Requests</li>
@endsection

@section('content')
@php
    $isEventManager = auth()->user()->hasRole('event-manager');
@endphp
<div class="row">
    <div class="col-12">
        <div class="ju-card mb-4">
            <div class="ju-card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                <h5 class="mb-0">
                    <i class="fas fa-clipboard-list me-2"></i>My Requests
                </h5>
                <a href="{{ route('event-requests.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-1"></i>New Request
                </a>
            </div>
            <div class="ju-card-body">
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <a href="{{ route('event-requests.my-requests') }}" class="btn btn-sm {{ !request()->has('status') ? 'btn-primary' : 'btn-outline-primary' }}">
                        All <span class="badge bg-light text-dark ms-1">{{ $totalCount ?? 0 }}</span>
                    </a>
                    @if($isEventManager)
                    <a href="{{ route('event-requests.my-requests', ['status' => 'manager_review']) }}" class="btn btn-sm {{ request('status') === 'manager_review' ? 'btn-info' : 'btn-outline-info' }}">
                        Manager Review <span class="badge bg-light text-dark ms-1">{{ $managerReviewCount ?? 0 }}</span>
                    </a>
                    @endif
                    <a href="{{ route('event-requests.my-requests', ['status' => 'pending']) }}" class="btn btn-sm {{ request('status') === 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">
                        Pending <span class="badge bg-light text-dark ms-1">{{ $pendingCount ?? 0 }}</span>
                    </a>
                    <a href="{{ route('event-requests.my-requests', ['status' => 'approved']) }}" class="btn btn-sm {{ request('status') === 'approved' ? 'btn-success' : 'btn-outline-success' }}">
                        Approved <span class="badge bg-light text-dark ms-1">{{ $approvedCount ?? 0 }}</span>
                    </a>
                    <a href="{{ route('event-requests.my-requests', ['status' => 'rejected']) }}" class="btn btn-sm {{ request('status') === 'rejected' ? 'btn-danger' : 'btn-outline-danger' }}">
                        Rejected <span class="badge bg-light text-dark ms-1">{{ $rejectedCount ?? 0 }}</span>
                    </a>
                    <a href="{{ route('event-requests.my-requests', ['status' => 'cancelled']) }}" class="btn btn-sm {{ request('status') === 'cancelled' ? 'btn-secondary' : 'btn-outline-secondary' }}">
                        Cancelled <span class="badge bg-light text-dark ms-1">{{ $cancelledCount ?? 0 }}</span>
                    </a>
                </div>

                <form method="GET" action="{{ route('event-requests.my-requests') }}" class="mb-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search by title or organizer..." value="{{ request('search') }}">
                        @if(request()->filled('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search me-1"></i>Search
                        </button>
                        @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('event-requests.my-requests') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Clear
                        </a>
                        @endif
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Submitted</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($eventRequests as $eventRequest)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $eventRequest->title }}</div>
                                    <div class="text-muted small">{{ \Illuminate\Support\Str::limit($eventRequest->description, 70) }}</div>
                                </td>
                                <td>{{ ucfirst($eventRequest->event_type) }}</td>
                                <td>
                                    {{ $eventRequest->proposed_start_date?->format('M d, Y h:i A') }}
                                </td>
                                <td>
                                    <span class="badge bg-{{ $eventRequest->status_color ?? 'secondary' }}">
                                        {{ ucwords(str_replace('_', ' ', $eventRequest->status)) }}
                                    </span>
                                </td>
                                <td>{{ $eventRequest->created_at?->diffForHumans() }}</td>
                                <td class="text-end">
                                    <a href="{{ route('event-requests.show', $eventRequest) }}" class="btn btn-sm btn-outline-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($isEventManager && $eventRequest->status === 'manager_review')
                                    <a href="{{ route('event-requests.edit', $eventRequest) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('event-requests.manager-reject', $eventRequest) }}" method="POST" class="d-inline" id="managerRejectForm-{{ $eventRequest->id }}">
                                        @csrf
                                        <input type="hidden" name="review_notes" id="managerRejectNotes-{{ $eventRequest->id }}" value="">
                                        <button type="button" class="btn btn-sm btn-outline-danger" title="Reject"
                                            onclick="managerRejectRequest({{ $eventRequest->id }})">
                                            <i class="fas fa-times-circle"></i>
                                        </button>
                                    </form>
                                    @elseif(!$isEventManager && !in_array($eventRequest->status, ['approved', 'cancelled']))
                                    <form action="{{ route('event-requests.cancel', $eventRequest) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-secondary" title="Cancel" onclick="return confirm('Cancel this event request?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                    @endif
                                    @if($eventRequest->status === 'approved' && $eventRequest->event && !$eventRequest->event->is_cancelled)
                                    <a href="{{ route('admin.events.show-cancellation-form', $eventRequest->event) }}"
                                        class="btn btn-sm btn-outline-danger"
                                        title="Cancel Event">
                                        <i class="fas fa-ban"></i>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    No event requests found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($eventRequests->hasPages())
                <div class="mt-3">
                    {{ $eventRequests->withQueryString()->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function managerRejectRequest(requestId) {
    const reason = prompt('Enter rejection reason (optional):', 'Rejected by Event Manager');
    if (reason === null) {
        return;
    }

    const notesField = document.getElementById(`managerRejectNotes-${requestId}`);
    const form = document.getElementById(`managerRejectForm-${requestId}`);
    if (!notesField || !form) {
        return;
    }

    notesField.value = reason.trim() || 'Rejected by Event Manager';
    form.submit();
}
</script>
@endpush
