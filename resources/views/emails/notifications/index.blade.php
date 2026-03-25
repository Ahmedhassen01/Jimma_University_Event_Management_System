{{-- resources/views/notifications/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Notifications')
@section('page-title', 'My Notifications')
@section('page-subtitle', 'Stay updated with event notifications')

@section('breadcrumb-items')
    <li class="breadcrumb-item active">Notifications</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Notification Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="stat-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <div class="stat-number">{{ $notifications->total() }}</div>
                <div class="stat-label">Total Notifications</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);">
                <div class="stat-icon">
                    <i class="fas fa-bell-slash"></i>
                </div>
                <div class="stat-number">{{ $unreadCount }}</div>
                <div class="stat-label">Unread Notifications</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="ju-card">
                <div class="ju-card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">Notification Actions</h5>
                            <p class="text-muted mb-0">Manage your notifications</p>
                        </div>
                        <div class="btn-group">
                            <button class="btn btn-ju" id="markAllReadBtn">
                                <i class="fas fa-check-double me-1"></i> Mark All as Read
                            </button>
                            <button class="btn btn-ju-outline" id="refreshNotifications">
                                <i class="fas fa-sync-alt me-1"></i> Refresh
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="ju-card">
        <div class="ju-card-header d-flex justify-content-between align-items-center">
            <h5 class="ju-card-title m-0">All Notifications</h5>
            <div class="btn-group">
                <a href="{{ route('notifications.index', ['status' => 'all']) }}" 
                   class="btn btn-sm {{ request('status') == 'all' || !request('status') ? 'btn-ju' : 'btn-outline-ju' }}">
                    All
                </a>
                <a href="{{ route('notifications.index', ['status' => 'unread']) }}" 
                   class="btn btn-sm {{ request('status') == 'unread' ? 'btn-ju' : 'btn-outline-ju' }}">
                    Unread
                </a>
                <a href="{{ route('notifications.index', ['status' => 'read']) }}" 
                   class="btn btn-sm {{ request('status') == 'read' ? 'btn-ju' : 'btn-outline-ju' }}">
                    Read
                </a>
            </div>
        </div>
        <div class="ju-card-body">
            @if($notifications->count() > 0)
                <div class="notifications-list">
                    @foreach($notifications as $userNotification)
                        @php
                            $notification = $userNotification->notification;
                            $isUnread = !$userNotification->read;
                        @endphp
                        
                        <div class="notification-item {{ $isUnread ? 'unread' : '' }} mb-3 p-3 rounded border" 
                             id="notification-{{ $userNotification->id }}">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="notification-icon me-3">
                                        @switch($notification->type)
                                            @case('event_update')
                                                <i class="fas fa-calendar-alt text-warning fa-2x"></i>
                                                @break
                                            @case('event_cancel')
                                                <i class="fas fa-calendar-times text-danger fa-2x"></i>
                                                @break
                                            @case('announcement')
                                                <i class="fas fa-bullhorn text-info fa-2x"></i>
                                                @break
                                            @default
                                                <i class="fas fa-bell text-primary fa-2x"></i>
                                        @endswitch
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">{{ $notification->title }}</h6>
                                            <p class="mb-2 text-muted">{{ $notification->message }}</p>
                                            
                                            @if($notification->data)
                                                <div class="notification-data mb-2">
                                                    @foreach($notification->data as $key => $value)
                                                        @if(is_string($value))
                                                            <span class="badge bg-light text-dark me-1">
                                                                {{ ucfirst($key) }}: {{ $value }}
                                                            </span>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                            
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $notification->created_at->diffForHumans() }}
                                                @if($notification->sender)
                                                    • Sent by: {{ $notification->sender->name }}
                                                @endif
                                            </small>
                                        </div>
                                        <div class="notification-actions">
                                            <div class="btn-group btn-group-sm">
                                                @if($isUnread)
                                                    <button class="btn btn-success mark-read-btn" 
                                                            data-id="{{ $userNotification->id }}"
                                                            title="Mark as Read">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @endif
                                                <button class="btn btn-danger delete-notification-btn" 
                                                        data-id="{{ $userNotification->id }}"
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if($notification->event)
                                        <div class="mt-2">
                                            <a href="{{ route('events.guest.show', $notification->event->slug) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-external-link-alt me-1"></i>
                                                View Event
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $notifications->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-bell-slash fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No notifications found</h4>
                    <p class="text-muted">You don't have any notifications at the moment.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.notification-item {
    transition: all 0.3s ease;
    border-left: 4px solid transparent !important;
}

.notification-item.unread {
    background-color: rgba(0, 100, 0, 0.05);
    border-left-color: var(--ju-primary) !important;
}

.notification-item:hover {
    transform: translateX(5px);
    box-shadow: var(--ju-shadow-sm);
}

.notification-icon {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: rgba(0, 0, 0, 0.05);
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mark as read
    document.querySelectorAll('.mark-read-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            markAsRead(id);
        });
    });
    
    // Delete notification
    document.querySelectorAll('.delete-notification-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            if (confirm('Are you sure you want to delete this notification?')) {
                deleteNotification(id);
            }
        });
    });
    
    // Mark all as read
    document.getElementById('markAllReadBtn')?.addEventListener('click', function() {
        if (confirm('Mark all notifications as read?')) {
            markAllAsRead();
        }
    });
    
    // Refresh notifications
    document.getElementById('refreshNotifications')?.addEventListener('click', function() {
        window.location.reload();
    });
    
    function markAsRead(id) {
        fetch(`/notifications/${id}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const notification = document.getElementById(`notification-${id}`);
                if (notification) {
                    notification.classList.remove('unread');
                    notification.querySelector('.mark-read-btn')?.remove();
                    
                    // Update unread count badge
                    updateUnreadCount(data.unread_count);
                }
            }
        })
        .catch(error => console.error('Error:', error));
    }
    
    function markAllAsRead() {
        fetch('/notifications/read-all', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove unread class from all notifications
                document.querySelectorAll('.notification-item.unread').forEach(item => {
                    item.classList.remove('unread');
                    item.querySelector('.mark-read-btn')?.remove();
                });
                
                // Update unread count
                updateUnreadCount(0);
                
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
    
    function deleteNotification(id) {
        fetch(`/notifications/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const notification = document.getElementById(`notification-${id}`);
                if (notification) {
                    notification.remove();
                    
                    // Show message
                    showToast('Notification deleted successfully', 'success');
                    
                    // Reload if no notifications left
                    if (document.querySelectorAll('.notification-item').length === 0) {
                        setTimeout(() => window.location.reload(), 1500);
                    }
                }
            }
        })
        .catch(error => console.error('Error:', error));
    }
    
    function updateUnreadCount(count) {
        // Update unread count in stat card
        const unreadCard = document.querySelector('.stat-card:nth-child(2) .stat-number');
        if (unreadCard) {
            unreadCard.textContent = count;
        }
        
        // Update badge in header if exists
        const unreadBadge = document.querySelector('.unread-badge');
        if (unreadBadge) {
            unreadBadge.textContent = count;
            if (count === 0) {
                unreadBadge.style.display = 'none';
            }
        }
    }
    
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type} border-0`;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        document.body.appendChild(toast);
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        setTimeout(() => toast.remove(), 3000);
    }
});
</script>
@endpush