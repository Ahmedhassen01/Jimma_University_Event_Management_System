@extends('layouts.app')

@section('title', 'Sent Notifications - Jimma University Events')
@section('page-title', 'Sent Notifications')
@section('page-subtitle', 'View all notifications that have been sent')

@section('breadcrumb-items')
    <li class="breadcrumb-item">
        <a href="{{ route('notifications.index') }}">Notifications</a>
    </li>
    <li class="breadcrumb-item active">Sent</li>
@endsection

@section('content')
@can('send_notifications')
<!-- Statistics -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="stat-card stat-card-success">
            <i class="fas fa-paper-plane stat-icon"></i>
            <div class="stat-number">{{ number_format($totalSent ?? 0) }}</div>
            <div class="stat-label">Total Sent</div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-card stat-card-info">
            <i class="fas fa-calendar-day stat-icon"></i>
            <div class="stat-number">{{ number_format($todaySent ?? 0) }}</div>
            <div class="stat-label">Sent Today</div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-card stat-card-warning">
            <i class="fas fa-list-ol stat-icon"></i>
            <div class="stat-number">{{ number_format($notifications->total()) }}</div>
            <div class="stat-label">Total Records</div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-card stat-card-purple">
            <i class="fas fa-eye stat-icon"></i>
            <div class="stat-number">{{ number_format($currentPageCount ?? 0) }}</div>
            <div class="stat-label">Showing</div>
        </div>
    </div>
</div>

<!-- Notifications Table -->
<div class="row">
    <div class="col-12">
        <div class="ju-card">
            <div class="ju-card-header">
                <h5 class="ju-card-title mb-0">
                    <i class="fas fa-history me-2"></i>Sent Notifications History
                </h5>
            </div>
            <div class="ju-card-body">
                @if($notifications->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Priority</th>
                                <th>Recipients</th>
                                <th>Sent By</th>
                                <th>Sent At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($notifications as $notification)
                            <tr>
                                <td>
                                    <strong>{{ $notification->title }}</strong>
                                    @if($notification->event)
                                    <br>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $notification->event->title }}
                                    </small>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $typeColors = [
                                            'event_update' => 'primary',
                                            'event_cancel' => 'danger',
                                            'custom' => 'info',
                                            'announcement' => 'success'
                                        ];
                                        $color = $typeColors[$notification->type] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $color }}">
                                        {{ str_replace('_', ' ', $notification->type) }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $priorityColors = [
                                            'low' => 'secondary',
                                            'normal' => 'primary',
                                            'high' => 'warning',
                                            'urgent' => 'danger'
                                        ];
                                        $priorityColor = $priorityColors[$notification->priority] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $priorityColor }}">
                                        <i class="fas fa-{{ $notification->priority == 'urgent' ? 'exclamation-triangle' : 'circle' }} me-1"></i>
                                        {{ ucfirst($notification->priority) }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $recipientTypes = [
                                            'all' => 'All Users',
                                            'specific' => 'Specific Users',
                                            'event_participants' => 'Event Participants',
                                            'waitlisted' => 'Event Waitlist'
                                        ];
                                        $recipientType = $recipientTypes[$notification->recipient_type] ?? $notification->recipient_type;
                                    @endphp
                                    {{ $recipientType }}
                                    @if($notification->recipient_ids)
                                    <br>
                                    <small class="text-muted">
                                        {{ count(json_decode($notification->recipient_ids, true) ?? []) }} recipient(s)
                                    </small>
                                    @endif
                                </td>
                                <td>
                                    @if($notification->sender)
                                    {{ $notification->sender->name }}
                                    @else
                                    <span class="text-muted">System</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $notification->sent_at->format('M d, Y') }}
                                    <br>
                                    <small class="text-muted">
                                        {{ $notification->sent_at->format('h:i A') }}
                                    </small>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-ju" 
                                            onclick="viewNotification({{ $notification->id }})"
                                            data-bs-toggle="tooltip" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    
                                    @can('manage_notifications')
                                    <button type="button" class="btn btn-sm btn-outline-danger ms-1"
                                            onclick="confirmDelete({{ $notification->id }})"
                                            data-bs-toggle="tooltip" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Showing {{ $notifications->firstItem() ?? 0 }} to {{ $notifications->lastItem() ?? 0 }} 
                        of {{ $notifications->total() }} entries
                    </div>
                    {{ $notifications->links() }}
                </div>
                
                @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted mb-3">No Sent Notifications</h4>
                    <p class="text-muted mb-4">
                        No notifications have been sent yet. Send your first notification to see it here.
                    </p>
                    <a href="{{ route('notifications.send-custom') }}" class="btn btn-ju">
                        <i class="fas fa-paper-plane me-2"></i>Send First Notification
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- View Notification Modal -->
<div class="modal fade" id="viewNotificationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content ju-card">
            <div class="modal-header ju-card-header">
                <h5 class="modal-title">Notification Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body ju-card-body" id="notificationDetails">
                <!-- Details will be loaded here via AJAX -->
            </div>
            <div class="modal-footer ju-card-footer">
                <button type="button" class="btn btn-outline-ju" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Close
                </button>
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
                <a href="{{ route('notifications.statistics') }}" class="btn btn-outline-ju me-2">
                    <i class="fas fa-chart-bar me-2"></i>View Statistics
                </a>
                <a href="{{ route('notifications.send-custom') }}" class="btn btn-ju">
                    <i class="fas fa-paper-plane me-2"></i>Send New Notification
                </a>
            </div>
        </div>
    </div>
</div>
@else
<!-- Access Denied -->
<div class="row">
    <div class="col-12">
        <div class="ju-card">
            <div class="ju-card-body text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-ban fa-4x text-danger mb-3"></i>
                    <h4 class="text-danger mb-3">Access Denied</h4>
                    <p class="text-muted mb-4">
                        You don't have permission to view sent notifications. Please contact your administrator 
                        if you believe this is an error.
                    </p>
                    <a href="{{ route('notifications.index') }}" class="btn btn-ju">
                        <i class="fas fa-arrow-left me-2"></i>Back to Notifications
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endcan
@endsection

@push('styles')
<style>
.table th {
    background-color: rgba(0, 100, 0, 0.05);
    border-bottom: 2px solid var(--ju-primary);
}
</style>
@endpush

@push('scripts')
<script>
function viewNotification(notificationId) {
    // Load notification details via AJAX
    fetch(`/api/notifications/${notificationId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const modal = document.getElementById('viewNotificationModal');
                const detailsContainer = document.getElementById('notificationDetails');
                
                // Format recipients
                let recipients = 'All Users';
                if (data.notification.recipient_type === 'specific') {
                    recipients = data.notification.recipient_ids.length + ' specific user(s)';
                } else if (data.notification.recipient_type === 'event_participants') {
                    recipients = 'Event Participants';
                } else if (data.notification.recipient_type === 'waitlisted') {
                    recipients = 'Event Waitlist';
                }
                
                // Build details HTML
                const html = `
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <h5 class="text-ju-primary">${data.notification.title}</h5>
                            <p class="mb-0">${data.notification.message}</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong><i class="fas fa-tag me-2"></i>Type:</strong>
                            <span class="badge bg-primary ms-2">${data.notification.type}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong><i class="fas fa-exclamation-circle me-2"></i>Priority:</strong>
                            <span class="badge bg-${data.notification.priority === 'urgent' ? 'danger' : 'warning'} ms-2">
                                ${data.notification.priority}
                            </span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong><i class="fas fa-users me-2"></i>Recipients:</strong>
                            <span class="ms-2">${recipients}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong><i class="fas fa-user me-2"></i>Sent By:</strong>
                            <span class="ms-2">${data.notification.sender?.name || 'System'}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong><i class="fas fa-clock me-2"></i>Sent At:</strong>
                            <span class="ms-2">${data.notification.sent_at_formatted}</span>
                        </div>
                        ${data.notification.event ? `
                        <div class="col-md-6 mb-3">
                            <strong><i class="fas fa-calendar me-2"></i>Related Event:</strong>
                            <span class="ms-2">${data.notification.event.title}</span>
                        </div>
                        ` : ''}
                    </div>
                    
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Note:</strong> This notification was delivered to all selected recipients.
                    </div>
                `;
                
                detailsContainer.innerHTML = html;
                new bootstrap.Modal(modal).show();
            } else {
                alert('Failed to load notification details.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while loading notification details.');
        });
}

function confirmDelete(notificationId) {
    if (confirm('Are you sure you want to delete this notification? This action cannot be undone.')) {
        fetch(`/notifications/${notificationId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Failed to delete notification.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the notification.');
        });
    }
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush