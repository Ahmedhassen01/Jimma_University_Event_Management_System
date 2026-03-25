{{-- resources/views/admin/registrations/pending.blade.php --}}
@extends('layouts.app')

@section('title', 'Pending Registrations | Jimma University')
@section('page-title', 'Pending Registrations')
@section('page-subtitle', 'Registrations waiting for confirmation')

@section('breadcrumb-items')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.registrations.index') }}">Registrations</a></li>
<li class="breadcrumb-item active">Pending</li>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-warning d-flex align-items-center">
            <i class="fas fa-clock fa-2x me-3"></i>
            <div>
                <strong>{{ $pendingCount }} Pending Registrations</strong> need your confirmation.
                <span class="d-block mt-1 small">Oldest pending registrations are shown first.</span>
            </div>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Filter by Event</label>
                        <select name="event_id" class="form-select">
                            <option value="">All Events</option>
                            @foreach($events as $event)
                            <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                {{ $event->title }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-1"></i> Apply Filter
                        </button>
                        <a href="{{ route('admin.registrations.pending') }}" class="btn btn-outline-secondary ms-2">
                            <i class="fas fa-redo-alt"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Pending Registrations List -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-0">
                @if($registrations->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th width="40">
                                    <input type="checkbox" id="selectAll">
                                </th>
                                <th>Registrant</th>
                                <th>Event</th>
                                <th>Registered</th>
                                <th>Guests</th>
                                <th>Wait Time</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($registrations as $registration)
                            <tr>
                                <td>
                                    <input type="checkbox" class="registration-checkbox" value="{{ $registration->id }}">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-warning text-dark rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                            {{ strtoupper(substr($registration->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <strong>{{ $registration->user->name }}</strong>
                                            <br>
                                            <small>{{ $registration->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('admin.events.show', $registration->event) }}" class="text-decoration-none fw-bold">
                                        {{ Str::limit($registration->event->title, 40) }}
                                    </a>
                                    <br>
                                    <small class="text-muted">
                                        {{ $registration->event->start_date->format('M d, Y • h:i A') }}
                                    </small>
                                </td>
                                <td>
                                    {{ $registration->created_at->format('M d, H:i') }}
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $registration->guest_count }}</span>
                                </td>
                                <td>
                                    @php
                                    $waitHours = $registration->created_at->diffInHours(now());
                                    $waitColor = $waitHours > 48 ? 'danger' : ($waitHours > 24 ? 'warning' : 'success');
                                    @endphp
                                    <span class="badge bg-{{ $waitColor }}">
                                        {{ $registration->created_at->diffForHumans() }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.registrations.confirm-form', $registration->id) }}" 
                                           class="btn btn-sm btn-success" 
                                           data-bs-toggle="tooltip" 
                                           title="Confirm">
                                            <i class="fas fa-check"></i>
                                        </a>
                                        <a href="{{ route('admin.registrations.show', $registration->id) }}" 
                                           class="btn btn-sm btn-info" 
                                           data-bs-toggle="tooltip" 
                                           title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <button class="btn btn-success" id="bulkConfirmBtn" disabled>
                                <i class="fas fa-check-circle me-1"></i> Confirm Selected
                            </button>
                        </div>
                        <div>
                            {{ $registrations->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fas fa-check-circle fa-4x mb-3" style="color: #28a745;"></i>
                    <h5>No Pending Registrations!</h5>
                    <p class="text-muted">All registrations have been processed.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<form id="bulkConfirmForm" method="POST" action="{{ route('admin.registrations.bulk-confirm') }}" style="display: none;">
    @csrf
    <input type="hidden" name="registration_ids" id="selectedIds">
    <input type="hidden" name="send_email" value="1">
</form>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#selectAll').change(function() {
            $('.registration-checkbox').prop('checked', $(this).prop('checked'));
            updateBulkButton();
        });

        $('.registration-checkbox').change(function() {
            updateSelectAll();
            updateBulkButton();
        });

        function updateSelectAll() {
            var allChecked = $('.registration-checkbox:checked').length === $('.registration-checkbox').length;
            $('#selectAll').prop('checked', allChecked);
        }

        function updateBulkButton() {
            var selectedCount = $('.registration-checkbox:checked').length;
            $('#bulkConfirmBtn').prop('disabled', selectedCount === 0);
        }

        $('#bulkConfirmBtn').click(function() {
            var selectedIds = $('.registration-checkbox:checked').map(function() {
                return $(this).val();
            }).get();

            if (selectedIds.length === 0) return;

            if (confirm('Confirm ' + selectedIds.length + ' pending registration(s)?')) {
                $('#selectedIds').val(JSON.stringify(selectedIds));
                $('#bulkConfirmForm').submit();
            }
        });

        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>
@endpush