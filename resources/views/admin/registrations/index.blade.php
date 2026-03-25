{{-- resources/views/admin/registrations/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Manage Registrations | Jimma University')
@section('page-title', 'Event Registrations')
@section('page-subtitle', 'Manage and confirm participant registrations')

@section('breadcrumb-items')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Registrations</li>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold" style="color: #002789;">
                        <i class="fas fa-ticket-alt me-2"></i>Registration Management
                    </h5>
                    <div>
                        <a href="{{ route('admin.registrations.export', request()->query()) }}" class="btn btn-sm btn-outline-primary me-2">
                            <i class="fas fa-download me-1"></i> Export
                        </a>
                        <a href="{{ route('admin.registrations.pending') }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-clock me-1"></i> Pending ({{ $stats['pending'] }})
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-2 col-sm-6 mb-3">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <h6 class="text-white-50">Total</h6>
                <h3 class="mb-0">{{ $stats['total'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-sm-6 mb-3">
        <div class="card bg-warning text-white h-100">
            <div class="card-body">
                <h6 class="text-white-50">Pending</h6>
                <h3 class="mb-0">{{ $stats['pending'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-sm-6 mb-3">
        <div class="card bg-success text-white h-100">
            <div class="card-body">
                <h6 class="text-white-50">Confirmed</h6>
                <h3 class="mb-0">{{ $stats['confirmed'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-sm-6 mb-3">
        <div class="card bg-danger text-white h-100">
            <div class="card-body">
                <h6 class="text-white-50">Cancelled</h6>
                <h3 class="mb-0">{{ $stats['cancelled'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-sm-6 mb-3">
        <div class="card bg-info text-white h-100">
            <div class="card-body">
                <h6 class="text-white-50">Waitlisted</h6>
                <h3 class="mb-0">{{ $stats['waitlisted'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-sm-6 mb-3">
        <div class="card bg-secondary text-white h-100">
            <div class="card-body">
                <h6 class="text-white-50">Attended</h6>
                <h3 class="mb-0">{{ $stats['attended'] }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Filter Form -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.registrations.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="waitlisted" {{ request('status') == 'waitlisted' ? 'selected' : '' }}>Waitlisted</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Event</label>
                        <select name="event_id" class="form-select select2">
                            <option value="">All Events</option>
                            @foreach($events as $event)
                            <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                {{ $event->title }} ({{ $event->start_date->format('M d, Y') }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">From Date</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">To Date</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Name or Email" value="{{ request('search') }}">
                    </div>
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-1"></i> Apply Filters
                        </button>
                        <a href="{{ route('admin.registrations.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-redo-alt me-1"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Registrations Table -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th width="40">
                                    <input type="checkbox" id="selectAll">
                                </th>
                                <th>Registration #</th>
                                <th>Event</th>
                                <th>Registrant</th>
                                <th>Guests</th>
                                <th>Status</th>
                                <th>Registered</th>
                                <th>Attended</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($registrations as $registration)
                            <tr>
                                <td>
                                    <input type="checkbox" class="registration-checkbox" value="{{ $registration->id }}">
                                </td>
                                <td>
                                    <span class="fw-bold" style="color: #002789;">
                                        {{ $registration->registration_number }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.events.show', $registration->event) }}" class="text-decoration-none">
                                        {{ Str::limit($registration->event->title, 40) }}
                                    </a>
                                    <br>
                                    <small class="text-muted">{{ $registration->event->start_date->format('M d, Y') }}</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            {{ strtoupper(substr($registration->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div>{{ $registration->user->name }}</div>
                                            <small class="text-muted">{{ $registration->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $registration->guest_count }}</td>
                                <td>
                                    @php
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'confirmed' => 'success',
                                        'cancelled' => 'danger',
                                        'waitlisted' => 'info'
                                    ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$registration->status] ?? 'secondary' }}">
                                        {{ ucfirst($registration->status) }}
                                    </span>
                                </td>
                                <td>
                                    <small>{{ $registration->created_at->format('M d, H:i') }}</small>
                                </td>
                                <td>
                                    @if($registration->attended)
                                    <span class="badge bg-success">Yes</span>
                                    <small class="text-muted d-block">{{ $registration->check_in_time?->format('H:i') }}</small>
                                    @else
                                    <span class="badge bg-secondary">No</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.registrations.show', $registration->id) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           data-bs-toggle="tooltip" 
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @if($registration->status == 'pending')
                                        <a href="{{ route('admin.registrations.confirm-form', $registration->id) }}" 
                                           class="btn btn-sm btn-success" 
                                           data-bs-toggle="tooltip" 
                                           title="Confirm Registration">
                                            <i class="fas fa-check"></i>
                                        </a>
                                        @endif
                                        
                                        <a href="{{ route('admin.registrations.edit', $registration->id) }}" 
                                           class="btn btn-sm btn-warning" 
                                           data-bs-toggle="tooltip" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        @if(!$registration->attended && $registration->status == 'confirmed')
                                        <button type="button" 
                                                class="btn btn-sm btn-info" 
                                                onclick="checkIn({{ $registration->id }})"
                                                data-bs-toggle="tooltip" 
                                                title="Check In">
                                            <i class="fas fa-sign-in-alt"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <i class="fas fa-ticket-alt fa-3x mb-3" style="color: #ccc;"></i>
                                    <h5>No Registrations Found</h5>
                                    <p class="text-muted">Try adjusting your filters</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <button class="btn btn-success btn-sm" id="bulkConfirmBtn" disabled>
                            <i class="fas fa-check-circle me-1"></i> Confirm Selected
                        </button>
                        <button class="btn btn-info btn-sm" id="bulkCheckInBtn" disabled>
                            <i class="fas fa-sign-in-alt me-1"></i> Check In Selected
                        </button>
                    </div>
                    <div>
                        {{ $registrations->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="bulkActionForm" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="registration_ids" id="selectedIds">
</form>

<form id="checkInForm" method="POST" style="display: none;">
    @csrf
</form>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });

        // Select All functionality
        $('#selectAll').change(function() {
            $('.registration-checkbox').prop('checked', $(this).prop('checked'));
            updateBulkButtons();
        });

        // Individual checkbox change
        $('.registration-checkbox').change(function() {
            updateSelectAll();
            updateBulkButtons();
        });

        function updateSelectAll() {
            var allChecked = $('.registration-checkbox:checked').length === $('.registration-checkbox').length;
            $('#selectAll').prop('checked', allChecked);
        }

        function updateBulkButtons() {
            var selectedCount = $('.registration-checkbox:checked').length;
            $('#bulkConfirmBtn').prop('disabled', selectedCount === 0);
            $('#bulkCheckInBtn').prop('disabled', selectedCount === 0);
        }

        // Bulk Confirm
        $('#bulkConfirmBtn').click(function() {
            var selectedIds = $('.registration-checkbox:checked').map(function() {
                return $(this).val();
            }).get();

            if (selectedIds.length === 0) {
                alert('Please select at least one registration.');
                return;
            }

            if (confirm('Are you sure you want to confirm ' + selectedIds.length + ' registration(s)?')) {
                $('#selectedIds').val(JSON.stringify(selectedIds));
                $('#bulkActionForm').attr('action', '{{ route("admin.registrations.bulk-confirm") }}');
                $('#bulkActionForm').submit();
            }
        });

        // Bulk Check In
        $('#bulkCheckInBtn').click(function() {
            var selectedIds = $('.registration-checkbox:checked').map(function() {
                return $(this).val();
            }).get();

            if (selectedIds.length === 0) {
                alert('Please select at least one registration.');
                return;
            }

            if (confirm('Check in ' + selectedIds.length + ' participant(s)?')) {
                $('#selectedIds').val(JSON.stringify(selectedIds));
                $('#bulkActionForm').attr('action', '{{ route("admin.registrations.bulk-checkin") }}');
                $('#bulkActionForm').submit();
            }
        });

        // Initialize tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();
    });

    function checkIn(registrationId) {
        if (confirm('Check in this participant?')) {
            $('#checkInForm').attr('action', '{{ url("admin/registrations") }}/' + registrationId + '/check-in');
            $('#checkInForm').submit();
        }
    }
</script>
@endpush