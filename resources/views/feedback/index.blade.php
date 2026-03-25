{{-- resources/views/feedback/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Feedback Management | Jimma University')
@section('page-title', 'Feedback Management')
@section('page-subtitle', 'Review, assign, and process submitted feedback')

@section('breadcrumb-items')
<li class="breadcrumb-item active" aria-current="page">Feedback Management</li>
@endsection

@push('styles')
<style>
    .fb-shell {
        display: grid;
        gap: 1.25rem;
    }

    .fb-card {
        background: #fff;
        border: 1px solid #e8edf4;
        border-radius: 14px;
        box-shadow: 0 10px 26px rgba(0, 39, 137, 0.08);
    }

    .fb-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1.1rem 1.2rem;
        border-bottom: 1px solid #edf2f7;
    }

    .fb-head h5 {
        margin: 0;
        font-size: 1.05rem;
        font-weight: 700;
        color: #002789;
    }

    .fb-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 0.9rem;
    }

    .fb-stat {
        padding: 1rem;
        border-radius: 12px;
        border: 1px solid #e8edf4;
        background: linear-gradient(160deg, #ffffff, #f7faff);
    }

    .fb-stat .label {
        font-size: 0.72rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #5f6b7a;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .fb-stat .value {
        font-size: 1.8rem;
        line-height: 1;
        font-weight: 800;
        color: #002789;
    }

    .fb-filters {
        padding: 1rem;
    }

    .fb-filter-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 0.85rem;
    }

    .fb-filter-grid .form-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #556274;
        font-weight: 700;
        margin-bottom: 0.35rem;
    }

    .fb-filter-grid .form-control,
    .fb-filter-grid .form-select {
        border-radius: 10px;
        border-color: #dbe3ee;
    }

    .fb-toolbar {
        margin-top: 0.85rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .fb-pill {
        border-radius: 999px;
        padding: 0.28rem 0.68rem;
        font-size: 0.72rem;
        font-weight: 700;
        border: 1px solid transparent;
        display: inline-flex;
        align-items: center;
        gap: 0.32rem;
    }

    .fb-pill-status-pending { background: #fff4d8; color: #7f5a00; border-color: #f6deb4; }
    .fb-pill-status-reviewed { background: #e8f1ff; color: #0d47a1; border-color: #cfe1ff; }
    .fb-pill-status-resolved { background: #e8f8ee; color: #196f3d; border-color: #ccefd8; }
    .fb-pill-status-closed { background: #f1f3f5; color: #495057; border-color: #dee2e6; }

    .fb-pill-type-event { background: #eaf2ff; color: #144aa0; border-color: #d6e6ff; }
    .fb-pill-type-system { background: #f0edff; color: #5b3cc4; border-color: #e1d9ff; }
    .fb-pill-type-general { background: #e9f7ef; color: #1f7a4d; border-color: #d2efdf; }
    .fb-pill-type-suggestion { background: #fff5e8; color: #9a5a00; border-color: #ffe5c4; }
    .fb-pill-type-complaint { background: #fdecee; color: #9b1c2b; border-color: #f8cfd5; }

    .fb-pill-priority-low { background: #eef6ff; color: #1d5fa8; border-color: #d5e9ff; }
    .fb-pill-priority-medium { background: #fff6e5; color: #8a5a00; border-color: #ffe2b0; }
    .fb-pill-priority-high { background: #ffecec; color: #9f2937; border-color: #ffd4d8; }
    .fb-pill-priority-urgent { background: #ffe2e2; color: #7d0f1a; border-color: #ffc1c6; }

    .fb-table-wrap {
        overflow: auto;
    }

    .fb-table {
        margin: 0;
        min-width: 980px;
    }

    .fb-table thead th {
        background: linear-gradient(145deg, #002789, #001a5c);
        color: #fff;
        border: 0;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        white-space: nowrap;
        padding: 0.85rem 0.8rem;
    }

    .fb-table tbody td {
        padding: 0.85rem 0.8rem;
        vertical-align: middle;
        border-color: #edf2f7;
    }

    .fb-row {
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .fb-row:hover {
        background: #f7fbff;
    }

    .fb-subject {
        font-weight: 700;
        color: #1f2b3d;
        margin-bottom: 0.2rem;
    }

    .fb-message {
        color: #6b7685;
        font-size: 0.84rem;
        margin-bottom: 0.45rem;
        max-width: 460px;
    }

    .fb-actions {
        display: flex;
        justify-content: center;
        gap: 0.35rem;
    }

    .fb-action-btn {
        width: 32px;
        height: 32px;
        border: 1px solid #d5deea;
        border-radius: 8px;
        background: #fff;
        color: #334155;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: 0.2s ease;
    }

    .fb-action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(0, 39, 137, 0.15);
        color: #fff;
    }

    .fb-action-btn.view:hover { background: #0d6efd; border-color: #0d6efd; }
    .fb-action-btn.public:hover { background: #0ea5e9; border-color: #0ea5e9; }
    .fb-action-btn.feature:hover { background: #f59e0b; border-color: #f59e0b; }
    .fb-action-btn.delete:hover { background: #dc3545; border-color: #dc3545; }

    .fb-empty {
        padding: 2.8rem 1.2rem;
        text-align: center;
    }

    .fb-empty i {
        font-size: 2.2rem;
        color: #93a3b8;
        margin-bottom: 0.75rem;
    }

    .fb-toast {
        position: fixed;
        top: 88px;
        right: 16px;
        z-index: 1065;
        min-width: 280px;
        max-width: 360px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-left: 4px solid #2563eb;
        border-radius: 10px;
        box-shadow: 0 18px 38px rgba(0, 0, 0, 0.14);
        padding: 0.75rem 0.9rem;
    }

    .fb-toast.success { border-left-color: #16a34a; }
    .fb-toast.error { border-left-color: #dc2626; }
    .fb-toast.warning { border-left-color: #d97706; }

    @media (max-width: 1200px) {
        .fb-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .fb-filter-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }

    @media (max-width: 640px) {
        .fb-stats,
        .fb-filter-grid { grid-template-columns: 1fr; }

        .fb-head { align-items: flex-start; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
    <div class="fb-shell">
        <div class="fb-card">
            <div class="fb-head">
                <h5><i class="fas fa-comments me-2"></i>Feedback Overview</h5>
                <small class="text-muted">Updated {{ now()->format('M d, Y h:i A') }}</small>
            </div>
            <div class="p-3">
                <div class="fb-stats">
                    <div class="fb-stat">
                        <div class="label">Total</div>
                        <div class="value">{{ $statistics['total'] ?? 0 }}</div>
                    </div>
                    <div class="fb-stat">
                        <div class="label">Pending</div>
                        <div class="value" style="color:#b77a00;">{{ $statistics['pending'] ?? 0 }}</div>
                    </div>
                    <div class="fb-stat">
                        <div class="label">Reviewed</div>
                        <div class="value" style="color:#1d4ed8;">{{ $statistics['reviewed'] ?? 0 }}</div>
                    </div>
                    <div class="fb-stat">
                        <div class="label">Resolved</div>
                        <div class="value" style="color:#15803d;">{{ $statistics['resolved'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="fb-card fb-filters">
            <form action="{{ route('feedback.index') }}" method="GET">
                <div class="fb-filter-grid">
                    <div>
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="reviewed" {{ request('status') === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                            <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select">
                            <option value="">All</option>
                            <option value="event" {{ request('type') === 'event' ? 'selected' : '' }}>Event</option>
                            <option value="system" {{ request('type') === 'system' ? 'selected' : '' }}>System</option>
                            <option value="general" {{ request('type') === 'general' ? 'selected' : '' }}>General</option>
                            <option value="suggestion" {{ request('type') === 'suggestion' ? 'selected' : '' }}>Suggestion</option>
                            <option value="complaint" {{ request('type') === 'complaint' ? 'selected' : '' }}>Complaint</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Minimum Rating</label>
                        <select name="rating" class="form-select">
                            <option value="">Any</option>
                            <option value="5" {{ request('rating') === '5' ? 'selected' : '' }}>5 Stars</option>
                            <option value="4" {{ request('rating') === '4' ? 'selected' : '' }}>4+ Stars</option>
                            <option value="3" {{ request('rating') === '3' ? 'selected' : '' }}>3+ Stars</option>
                            <option value="2" {{ request('rating') === '2' ? 'selected' : '' }}>2+ Stars</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Subject, message, user...">
                    </div>
                </div>

                <div class="fb-toolbar">
                    <div class="d-flex gap-2 flex-wrap">
                        <button class="btn btn-primary btn-sm" type="submit">
                            <i class="fas fa-search me-1"></i>Apply Filters
                        </button>
                        @if(request()->hasAny(['status','type','rating','search']))
                        <a href="{{ route('feedback.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-xmark me-1"></i>Clear
                        </a>
                        @endif
                    </div>

                    <div class="d-flex gap-2 flex-wrap">
                        @if(auth()->user()->hasPermission('view_feedback_analytics'))
                        <a href="{{ route('feedback.analytics') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-chart-line me-1"></i>Analytics
                        </a>
                        @endif
                        @if(auth()->user()->hasPermission('export_feedback'))
                        <a href="{{ route('feedback.export') }}" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-file-export me-1"></i>Export
                        </a>
                        @endif
                        <button type="button" onclick="location.reload()" class="btn btn-outline-dark btn-sm">
                            <i class="fas fa-rotate-right me-1"></i>Refresh
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="fb-card">
            <div class="fb-head">
                <h5><i class="fas fa-list me-2"></i>Feedback Queue</h5>
                <small class="text-muted">
                    @if($feedbacks->total() > 0)
                    Showing {{ $feedbacks->firstItem() }}-{{ $feedbacks->lastItem() }} of {{ $feedbacks->total() }}
                    @else
                    No entries
                    @endif
                </small>
            </div>

            @if($feedbacks->count() > 0)
            <div class="fb-table-wrap">
                <table class="table fb-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Feedback</th>
                            <th>Submitter</th>
                            <th>Rating</th>
                            <th>Status</th>
                            <th>Assigned</th>
                            <th>Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($feedbacks as $feedback)
                        @php
                            $typeClass = 'fb-pill-type-' . $feedback->type;
                            $statusClass = 'fb-pill-status-' . $feedback->status;
                            $priority = data_get($feedback->metadata, 'priority');
                            $priorityClass = $priority ? 'fb-pill-priority-' . $priority : '';
                        @endphp
                        <tr class="fb-row" data-url="{{ route('feedback.show', $feedback) }}">
                            <td><strong>#{{ $feedback->id }}</strong></td>
                            <td>
                                <div class="fb-subject">{{ $feedback->subject ?: 'No subject' }}</div>
                                <div class="fb-message">{{ Str::limit(strip_tags($feedback->message), 95) }}</div>
                                <div class="d-flex flex-wrap gap-1">
                                    <span class="fb-pill {{ $typeClass }}">{{ ucfirst($feedback->type) }}</span>
                                    @if($priority)
                                    <span class="fb-pill {{ $priorityClass }}">Priority: {{ ucfirst($priority) }}</span>
                                    @endif
                                    @if($feedback->is_public)
                                    <span class="fb-pill" style="background:#e8f8ee;color:#0f766e;border-color:#cdeee4;">Public</span>
                                    @endif
                                    @if($feedback->featured)
                                    <span class="fb-pill" style="background:#fff7db;color:#8a5a00;border-color:#f9e8b2;">Featured</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $feedback->user->name ?? ($feedback->name ?: 'Anonymous') }}</div>
                                @if($feedback->email)
                                <small class="text-muted">{{ $feedback->email }}</small>
                                @endif
                            </td>
                            <td>
                                @if($feedback->rating)
                                <span class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="{{ $i <= $feedback->rating ? 'fas' : 'far' }} fa-star"></i>
                                    @endfor
                                </span>
                                <div><small class="text-muted">{{ $feedback->rating }}/5</small></div>
                                @else
                                <small class="text-muted">Not rated</small>
                                @endif
                            </td>
                            <td>
                                <span class="fb-pill {{ $statusClass }}">{{ ucfirst($feedback->status) }}</span>
                            </td>
                            <td>
                                @if($feedback->assignee)
                                    <span class="fb-pill" style="background:#e9f7ef;color:#14532d;border-color:#d5efdd;">{{ $feedback->assignee->name }}</span>
                                @elseif($canManageAll)
                                    <span class="fb-pill" style="background:#fff4d8;color:#7f5a00;border-color:#f6deb4;">Unassigned</span>
                                @else
                                    <small class="text-muted">-</small>
                                @endif
                            </td>
                            <td>
                                <div>{{ $feedback->created_at->format('M d, Y') }}</div>
                                <small class="text-muted">{{ $feedback->created_at->format('h:i A') }}</small>
                            </td>
                            <td class="text-center" onclick="event.stopPropagation();">
                                <div class="fb-actions">
                                    <a href="{{ route('feedback.show', $feedback) }}" class="fb-action-btn view" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($canManageAll)
                                    <button type="button" class="fb-action-btn public" title="Toggle Public" onclick="togglePublic({{ $feedback->id }})">
                                        <i class="fas fa-eye{{ $feedback->is_public ? '' : '-slash' }}"></i>
                                    </button>
                                    @if($feedback->is_public)
                                    <button type="button" class="fb-action-btn feature" title="Toggle Featured" onclick="toggleFeatured({{ $feedback->id }})">
                                        <i class="fas fa-star"></i>
                                    </button>
                                    @endif
                                    @endif
                                    @if(auth()->user()->hasPermission('delete_feedback') || $canManageAll)
                                    <button type="button" class="fb-action-btn delete" title="Delete" onclick="deleteFeedback({{ $feedback->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                <small class="text-muted">
                    Showing {{ $feedbacks->firstItem() }} to {{ $feedbacks->lastItem() }} of {{ $feedbacks->total() }} entries
                </small>
                {{ $feedbacks->withQueryString()->links() }}
            </div>
            @else
            <div class="fb-empty">
                <i class="fas fa-inbox"></i>
                <h6 class="mb-2">No feedback found</h6>
                <p class="text-muted mb-3">Try changing your filters or come back later.</p>
                <a href="{{ route('feedback.index') }}" class="btn btn-outline-primary btn-sm">Reset Filters</a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const togglePublicUrlTemplate = @json(route('feedback.toggle-public', ['feedback' => '__ID__']));
const toggleFeaturedUrlTemplate = @json(route('feedback.toggle-featured', ['feedback' => '__ID__']));
const deleteFeedbackUrlTemplate = @json(route('feedback.destroy', ['feedback' => '__ID__']));

function notify(message, type = 'success') {
    const el = document.createElement('div');
    el.className = `fb-toast ${type}`;
    el.innerHTML = `<div class="d-flex justify-content-between gap-2"><div>${message}</div><button class="btn-close btn-sm" type="button"></button></div>`;
    document.body.appendChild(el);
    el.querySelector('.btn-close').addEventListener('click', () => el.remove());
    setTimeout(() => {
        el.style.opacity = '0';
        el.style.transition = 'opacity .25s ease';
        setTimeout(() => el.remove(), 260);
    }, 2400);
}

function postAction(url, successMessage) {
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            notify(successMessage, 'success');
            setTimeout(() => location.reload(), 700);
            return;
        }
        notify(data.message || 'Request failed.', 'warning');
    })
    .catch(() => notify('Request failed.', 'error'));
}

function togglePublic(feedbackId) {
    if (!confirm('Change public visibility for this feedback?')) return;
    postAction(togglePublicUrlTemplate.replace('__ID__', feedbackId), 'Visibility updated.');
}

function toggleFeatured(feedbackId) {
    if (!confirm('Toggle featured status for this feedback?')) return;
    postAction(toggleFeaturedUrlTemplate.replace('__ID__', feedbackId), 'Featured status updated.');
}

function deleteFeedback(feedbackId) {
    if (!confirm('Delete this feedback permanently?')) return;
    fetch(deleteFeedbackUrlTemplate.replace('__ID__', feedbackId), {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            notify('Feedback deleted.', 'success');
            setTimeout(() => location.reload(), 700);
            return;
        }
        notify(data.message || 'Delete failed.', 'warning');
    })
    .catch(() => notify('Delete failed.', 'error'));
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.fb-row').forEach(row => {
        row.addEventListener('click', function (e) {
            if (e.target.closest('a') || e.target.closest('button')) return;
            const url = this.getAttribute('data-url');
            if (url) window.location.href = url;
        });
    });
});
</script>
@endpush
