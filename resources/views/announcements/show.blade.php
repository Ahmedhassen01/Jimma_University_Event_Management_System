@extends('layouts.app')

@section('title', $announcement->title . ' - Jimma University')
@section('page-title', 'Announcement Details')
@section('page-subtitle', 'University Official Announcement')

@section('breadcrumb-items')
<li class="breadcrumb-item"><a href="{{ route('announcements.index') }}">Announcements</a></li>
<li class="breadcrumb-item active">{{ Str::limit($announcement->title, 30) }}</li>
@endsection

@section('content')
<style>
    .announcement-header {
        border-left: 6px solid;
        padding-left: 25px;
        margin-bottom: 30px;
        position: relative;
    }
    
    .announcement-header.event { border-left-color: #3498db; }
    .announcement-header.campus { border-left-color: #2ecc71; }
    .announcement-header.general { border-left-color: #9b59b6; }
    .announcement-header.urgent { 
        border-left-color: #e74c3c; 
        animation: urgent-pulse 2s infinite;
    }
    
    @keyframes urgent-pulse {
        0%, 100% { border-left-color: #e74c3c; }
        50% { border-left-color: #ff7675; }
    }
    
    .announcement-type-badge {
        font-size: 0.8rem;
        padding: 5px 15px;
        border-radius: 20px;
        font-weight: 600;
    }
    
    .announcement-content {
        font-size: 1.1rem;
        line-height: 1.8;
    }
    
    .announcement-content h2,
    .announcement-content h3,
    .announcement-content h4 {
        margin-top: 2rem;
        margin-bottom: 1rem;
        color: var(--ju-primary);
    }
    
    .announcement-content ul,
    .announcement-content ol {
        padding-left: 2rem;
        margin-bottom: 1.5rem;
    }
    
    .announcement-content li {
        margin-bottom: 0.5rem;
    }
    
    .announcement-content a {
        color: var(--ju-primary);
        text-decoration: none;
        border-bottom: 1px dotted var(--ju-primary);
    }
    
    .announcement-content a:hover {
        color: var(--ju-primary-dark);
        border-bottom-style: solid;
    }
    
    .announcement-meta-item {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }
    
    .announcement-meta-icon {
        width: 40px;
        height: 40px;
        background: var(--ju-primary-lighter);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        color: var(--ju-primary);
        flex-shrink: 0;
    }
    
    .action-buttons .btn {
        min-width: 120px;
    }
    
    .share-buttons .btn {
        width: 40px;
        height: 40px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .expiry-alert {
        animation: fadeInOut 3s infinite alternate;
    }
    
    @keyframes fadeInOut {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    
    .view-count {
        font-size: 0.9rem;
        color: #6c757d;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="ju-card">
                <!-- Announcement Header -->
                <div class="announcement-header {{ $announcement->type }}">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="announcement-type-badge badge bg-{{ $announcement->type == 'urgent' ? 'danger' : 'primary' }}">
                                <i class="fas fa-{{ $announcement->type == 'event' ? 'calendar-alt' : ($announcement->type == 'campus' ? 'university' : ($announcement->type == 'urgent' ? 'exclamation-triangle' : 'bullhorn')) }} me-2"></i>
                                {{ $announcement->getTypeLabel() }}
                            </span>
                            
                            @if(!$announcement->is_published)
                            <span class="badge bg-warning ms-2">
                                <i class="fas fa-clock me-1"></i>Draft
                            </span>
                            @endif
                        </div>
                        
                        <div class="view-count">
                            <i class="fas fa-eye me-1"></i>
                            {{ number_format($announcement->views) }} views
                        </div>
                    </div>
                    
                    <h1 class="display-5 fw-bold mb-3">{{ $announcement->title }}</h1>
                    
                    <div class="d-flex flex-wrap gap-3 mb-4">
                        <div class="d-flex align-items-center">
                            <div class="announcement-meta-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <div class="text-muted">Author</div>
                                <div class="fw-semibold">{{ $announcement->creator->name ?? 'System' }}</div>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center">
                            <div class="announcement-meta-icon">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <div>
                                <div class="text-muted">Published</div>
                                <div class="fw-semibold">
                                    {{ $announcement->published_at ? $announcement->published_at->format('M d, Y h:i A') : 'Not Published' }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center">
                            <div class="announcement-meta-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <div class="text-muted">Audience</div>
                                <div class="fw-semibold">{{ $announcement->getAudienceLabel() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Announcement Content -->
                <div class="ju-card-body">
                    <div class="announcement-content">
                        {!! $announcement->content !!}
                    </div>
                    
                    @if($announcement->expires_at)
                    <div class="alert alert-warning expiry-alert mt-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-clock fa-lg me-3"></i>
                            <div>
                                <strong>This announcement will expire on:</strong>
                                {{ $announcement->expires_at->format('F j, Y \a\t h:i A') }}
                                ({{ $announcement->expires_at->diffForHumans() }})
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Tags/Categories -->
                    @if($announcement->audience === 'specific' && $announcement->target_ids)
                    <div class="mt-5">
                        <h5 class="mb-3">
                            <i class="fas fa-user-tag me-2"></i>Targeted Users
                        </h5>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($users->whereIn('id', $announcement->target_ids) as $user)
                            <span class="badge bg-info">
                                <i class="fas fa-user me-1"></i>{{ $user->name }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Action Buttons -->
                <div class="ju-card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="share-buttons">
                            <span class="text-muted me-2">Share:</span>
                            <div class="btn-group">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" 
                                   target="_blank" class="btn btn-outline-primary">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ urlencode($announcement->title) }}" 
                                   target="_blank" class="btn btn-outline-info">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ url()->current() }}&title={{ urlencode($announcement->title) }}" 
                                   target="_blank" class="btn btn-outline-primary">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                                <button onclick="copyToClipboard()" class="btn btn-outline-secondary">
                                    <i class="fas fa-link"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="action-buttons">
                            <div class="btn-group">
                                <a href="{{ route('announcements.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Back
                                </a>
                                
                                @can('update', $announcement)
                                <a href="{{ route('announcements.edit', $announcement) }}" 
                                   class="btn btn-outline-warning">
                                    <i class="fas fa-edit me-2"></i>Edit
                                </a>
                                @endcan
                                
                                @can('delete', $announcement)
                                <form action="{{ route('announcements.destroy', $announcement) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" 
                                            onclick="return confirm('Are you sure you want to delete this announcement?');">
                                        <i class="fas fa-trash me-2"></i>Delete
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Related Announcements -->
            @if($relatedAnnouncements->count() > 0)
            <div class="ju-card mt-4">
                <div class="ju-card-header">
                    <h5 class="ju-card-title">
                        <i class="fas fa-link me-2"></i>Related Announcements
                    </h5>
                </div>
                <div class="ju-card-body">
                    <div class="row">
                        @foreach($relatedAnnouncements as $related)
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 border-start border-{{ $related->type == 'urgent' ? 'danger' : 'primary' }} border-3">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <a href="{{ route('announcements.show', $related) }}" 
                                           class="text-decoration-none">
                                            {{ Str::limit($related->title, 50) }}
                                        </a>
                                    </h6>
                                    <p class="card-text text-muted small">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $related->created_at->format('M d, Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="ju-card mb-4">
                <div class="ju-card-header">
                    <h5 class="ju-card-title">
                        <i class="fas fa-info-circle me-2"></i>Announcement Details
                    </h5>
                </div>
                <div class="ju-card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3">
                            <div class="text-muted small">Announcement ID</div>
                            <div class="fw-semibold">#{{ $announcement->id }}</div>
                        </li>
                        <li class="mb-3">
                            <div class="text-muted small">Status</div>
                            <div>
                                @if($announcement->is_published)
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>Published
                                </span>
                                @else
                                <span class="badge bg-warning">
                                    <i class="fas fa-clock me-1"></i>Draft
                                </span>
                                @endif
                            </div>
                        </li>
                        <li class="mb-3">
                            <div class="text-muted small">Created</div>
                            <div class="fw-semibold">
                                {{ $announcement->created_at->format('M d, Y h:i A') }}
                            </div>
                        </li>
                        <li class="mb-3">
                            <div class="text-muted small">Last Updated</div>
                            <div class="fw-semibold">
                                {{ $announcement->updated_at->format('M d, Y h:i A') }}
                                <small class="text-muted">({{ $announcement->updated_at->diffForHumans() }})</small>
                            </div>
                        </li>
                        <li>
                            <div class="text-muted small">Visibility</div>
                            <div class="fw-semibold">
                                @if($announcement->audience === 'all')
                                <i class="fas fa-globe text-success me-1"></i>Public
                                @else
                                <i class="fas fa-user-shield text-primary me-1"></i>Restricted
                                @endif
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="ju-card mb-4">
                <div class="ju-card-header">
                    <h5 class="ju-card-title">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="ju-card-body">
                    <div class="d-grid gap-2">
                        @if($announcement->is_published)
                        <form action="{{ route('announcements.toggle-publish', $announcement) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-warning w-100">
                                <i class="fas fa-eye-slash me-2"></i>Unpublish
                            </button>
                        </form>
                        @else
                        <form action="{{ route('announcements.toggle-publish', $announcement) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-success w-100">
                                <i class="fas fa-paper-plane me-2"></i>Publish Now
                            </button>
                        </form>
                        @endif
                        
                        <a href="{{ route('announcements.create') }}" class="btn btn-outline-primary">
                            <i class="fas fa-plus-circle me-2"></i>Create New
                        </a>
                        
                        <a href="{{ route('announcements.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-list me-2"></i>All Announcements
                        </a>
                        
                        @can('view_announcement_stats')
                        <a href="{{ route('announcements.statistics') }}" class="btn btn-outline-info">
                            <i class="fas fa-chart-bar me-2"></i>View Statistics
                        </a>
                        @endcan
                    </div>
                </div>
            </div>
            
            <!-- Print/Download -->
            <div class="ju-card">
                <div class="ju-card-header">
                    <h5 class="ju-card-title">
                        <i class="fas fa-download me-2"></i>Download Options
                    </h5>
                </div>
                <div class="ju-card-body">
                    <div class="d-grid gap-2">
                        <button onclick="printAnnouncement()" class="btn btn-outline-primary">
                            <i class="fas fa-print me-2"></i>Print Announcement
                        </button>
                        
                        <button onclick="downloadAsPDF()" class="btn btn-outline-danger">
                            <i class="fas fa-file-pdf me-2"></i>Save as PDF
                        </button>
                        
                        <button onclick="exportAsText()" class="btn btn-outline-success">
                            <i class="fas fa-file-alt me-2"></i>Export as Text
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Print Styles -->
<style media="print">
    @media print {
        .ju-header, .ju-sidebar, .ju-footer, .action-buttons, .share-buttons,
        .ju-card:not(.main-announcement), .btn, .alert, .announcement-meta-icon {
            display: none !important;
        }
        
        .ju-main-content {
            padding: 0 !important;
            background: white !important;
            box-shadow: none !important;
        }
        
        .announcement-header {
            border-left: none !important;
            padding-left: 0 !important;
        }
        
        .announcement-content {
            font-size: 12pt !important;
        }
        
        .ju-card {
            border: none !important;
            box-shadow: none !important;
        }
        
        .ju-card-body {
            padding: 0 !important;
        }
    }
</style>
@endsection

@push('scripts')
<script>
// Copy link to clipboard
function copyToClipboard() {
    const url = window.location.href;
    navigator.clipboard.writeText(url).then(() => {
        showToast('Link copied to clipboard!', 'success');
    }).catch(err => {
        console.error('Failed to copy: ', err);
        showToast('Failed to copy link', 'error');
    });
}

// Print announcement
function printAnnouncement() {
    window.print();
}

// Download as PDF (simulated)
function downloadAsPDF() {
    showToast('PDF download feature coming soon!', 'info');
    // PDF download will be implemented in a future update
}

// Export as Text
function exportAsText() {
    const title = "{{ $announcement->title }}";
    const content = document.querySelector('.announcement-content') ? 
                    document.querySelector('.announcement-content').innerText : 
                    "{{ $announcement->content }}";
    const meta = `
Jimma University Announcement
============================

Title: ${title}
Author: {{ $announcement->creator->name ?? 'System' }}
Published: {{ $announcement->published_at ? $announcement->published_at->format('Y-m-d H:i') : 'Not Published' }}
Audience: {{ $announcement->getAudienceLabel() }}

${content}

---
This announcement was exported from Jimma University Event Management System
URL: {{ url()->current() }}
    `;
    
    const blob = new Blob([meta], { type: 'text/plain' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `announcement-{{ $announcement->id }}-{{ Str::slug($announcement->title) }}.txt`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
    
    showToast('Announcement exported as text file', 'success');
}

// Toast notification
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `ju-toast ju-toast-${type} animate__animated animate__fadeInRight`;
    toast.innerHTML = `
        <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} fa-2x"></i>
            </div>
            <div class="flex-grow-1 ms-3">
                <div class="fw-bold mb-1">${type.charAt(0).toUpperCase() + type.slice(1)}</div>
                <div class="toast-message">${message}</div>
            </div>
            <button type="button" class="btn-close btn-close-white" onclick="this.closest('.ju-toast').remove()"></button>
        </div>
    `;
    
    const container = document.querySelector('.toast-container') || document.body;
    container.appendChild(toast);
    
    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, 5000);
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Escape to go back
    if (e.key === 'Escape') {
        window.location.href = "{{ route('announcements.index') }}";
    }
    
    // Ctrl+P to print
    if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
        e.preventDefault();
        printAnnouncement();
    }
    
    // Ctrl+C to copy link
    if ((e.ctrlKey || e.metaKey) && e.key === 'c') {
        copyToClipboard();
    }
});

// Add reading time
document.addEventListener('DOMContentLoaded', function() {
    const contentElement = document.querySelector('.announcement-content');
    if (contentElement) {
        const content = contentElement.innerText;
        const wordCount = content.split(/\s+/).length;
        const readingTime = Math.ceil(wordCount / 200); // 200 words per minute
        
        // Add reading time to meta info
        const metaContainer = document.querySelector('.announcement-header');
        if (metaContainer) {
            const readingTimeElement = document.createElement('div');
            readingTimeElement.className = 'd-flex align-items-center mt-2';
            readingTimeElement.innerHTML = `
                <div class="announcement-meta-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <div class="text-muted">Reading Time</div>
                    <div class="fw-semibold">${readingTime} min read</div>
                </div>
            `;
            
            const flexContainer = metaContainer.querySelector('.d-flex.flex-wrap');
            if (flexContainer) {
                flexContainer.appendChild(readingTimeElement);
            }
        }
    }
});
</script>
@endpush