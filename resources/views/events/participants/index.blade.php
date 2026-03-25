@extends('layouts.app')

@section('title', 'Participants - ' . $event->title)
@section('page-title', 'Event Participants')
@section('page-subtitle', $event->title)

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.events.index') }}">Events</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.events.show', $event) }}">{{ Str::limit($event->title, 30) }}</a></li>
    <li class="breadcrumb-item active">Participants</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Event Header -->
    <div class="ju-card mb-4">
        <div class="ju-card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="mb-1">{{ $event->title }}</h4>
                    <div class="d-flex flex-wrap gap-2 mb-2">
                        <span class="badge bg-primary">
                            <i class="fas fa-calendar me-1"></i> {{ $event->start_date->format('M d, Y') }}
                        </span>
                        <span class="badge bg-secondary">
                            <i class="fas fa-clock me-1"></i> {{ $event->start_date->format('h:i A') }}
                        </span>
                        <span class="badge bg-info">
                            <i class="fas fa-map-marker-alt me-1"></i> {{ $event->venue }}, {{ $event->campus }}
                        </span>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <div class="btn-group">
                        <a href="{{ route('events.participants.export.excel', $event) }}" class="btn btn-success">
                            <i class="fas fa-file-excel me-1"></i> Export Excel
                        </a>
                        <a href="{{ route('events.participants.export.pdf', $event) }}" class="btn btn-danger ms-2">
                            <i class="fas fa-file-pdf me-1"></i> Export PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="stat-icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-number">{{ $confirmedCount }}</div>
                <div class="stat-label">Confirmed Participants</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-number">{{ $attendedCount }}</div>
                <div class="stat-label">Attended</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card" style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-number">{{ $event->waitlist_count }}</div>
                <div class="stat-label">Waitlist</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card" style="background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);">
                <div class="stat-icon">
                    <i class="fas fa-chair"></i>
                </div>
                <div class="stat-number">{{ $event->available_seats ?? 'Unlimited' }}</div>
                <div class="stat-label">Available Seats</div>
            </div>
        </div>
    </div>

    <!-- Participants List -->
    <div class="ju-card mb-4">
        <div class="ju-card-header d-flex justify-content-between align-items-center">
            <h5 class="ju-card-title m-0">Registered Participants ({{ $participants->total() }})</h5>
            <div class="dropdown">
                <button class="btn btn-ju btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-filter me-1"></i> Filter by Status
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('events.participants.index', $event) }}">All</a>
                    <a class="dropdown-item" href="{{ route('events.participants.index', ['event' => $event, 'status' => 'confirmed']) }}">Confirmed</a>
                    <a class="dropdown-item" href="{{ route('events.participants.index', ['event' => $event, 'status' => 'pending']) }}">Pending</a>
                    <a class="dropdown-item" href="{{ route('events.participants.index', ['event' => $event, 'status' => 'cancelled']) }}">Cancelled</a>
                </div>
            </div>
        </div>
        <div class="ju-card-body">
            @if($participants->count() > 0)
            <div class="table-responsive">
                <table class="table table-ju data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Participant</th>
                            <th>Registration No.</th>
                            <th>Registration Date</th>
                            <th>Guest Count</th>
                            <th>Status</th>
                            <th>Check-in</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($participants as $participant)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $participant->user->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $participant->user->email }}</small>
                                @if($participant->user->phone)
                                <br>
                                <small class="text-muted">{{ $participant->user->phone }}</small>
                                @endif
                            </td>
                            <td>
                                <code>{{ $participant->registration_number }}</code>
                            </td>
                            <td>{{ $participant->registration_date->format('M d, Y') }}</td>
                            <td>{{ $participant->guest_count }}</td>
                            <td>
                                <span class="badge bg-{{ $participant->status_color }}">
                                    {{ ucfirst($participant->status) }}
                                </span>
                            </td>
                            <td>
                                @if($participant->attended)
                                <span class="badge bg-success">
                                    <i class="fas fa-check"></i> Checked In
                                    @if($participant->check_in_time)
                                    <br><small>{{ $participant->check_in_time->format('h:i A') }}</small>
                                    @endif
                                </span>
                                @else
                                <span class="badge bg-secondary">Not Checked In</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    @if(!$participant->attended && $participant->isConfirmed())
                                    <form action="{{ route('events.participants.check-in', $participant) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success" title="Check In">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    @endif
                                    
                                    <button type="button" class="btn btn-info" data-bs-toggle="modal" 
                                            data-bs-target="#participantModal{{ $participant->id }}" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    
                                    <div class="dropdown d-inline">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" 
                                                data-bs-toggle="dropdown" title="Change Status">
                                            <i class="fas fa-cog"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            @if($participant->status !== 'confirmed')
                                            <form action="{{ route('events.participants.update-status', $participant) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="confirmed">
                                                <button type="submit" class="dropdown-item">Mark as Confirmed</button>
                                            </form>
                                            @endif
                                            @if($participant->status !== 'cancelled')
                                            <form action="{{ route('events.participants.update-status', $participant) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="dropdown-item text-danger">Mark as Cancelled</button>
                                            </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Participant Details Modal -->
                                <div class="modal fade" id="participantModal{{ $participant->id }}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Participant Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h6>Participant Information</h6>
                                                        <p><strong>Name:</strong> {{ $participant->user->name }}</p>
                                                        <p><strong>Email:</strong> {{ $participant->user->email }}</p>
                                                        <p><strong>Phone:</strong> {{ $participant->user->phone ?? 'N/A' }}</p>
                                                        <p><strong>Role:</strong> {{ $participant->user->role->name ?? 'N/A' }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6>Registration Details</h6>
                                                        <p><strong>Registration No:</strong> {{ $participant->registration_number }}</p>
                                                        <p><strong>Status:</strong> 
                                                            <span class="badge bg-{{ $participant->status_color }}">
                                                                {{ ucfirst($participant->status) }}
                                                            </span>
                                                        </p>
                                                        <p><strong>Guest Count:</strong> {{ $participant->guest_count }}</p>
                                                        <p><strong>Registered:</strong> {{ $participant->registration_date->format('M d, Y h:i A') }}</p>
                                                        @if($participant->confirmed_at)
                                                        <p><strong>Confirmed:</strong> {{ $participant->confirmed_at->format('M d, Y h:i A') }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                @if($participant->additional_info)
                                                <div class="row mt-3">
                                                    <div class="col-12">
                                                        <h6>Additional Information</h6>
                                                        <div class="card">
                                                            <div class="card-body">
                                                                {{ $participant->additional_info }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                
                                                @if($participant->notes)
                                                <div class="row mt-3">
                                                    <div class="col-12">
                                                        <h6>Admin Notes</h6>
                                                        <div class="card">
                                                            <div class="card-body">
                                                                {{ $participant->notes }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-ju-outline" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $participants->withQueryString()->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No participants found</h4>
                <p class="text-muted">No one has registered for this event yet.</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Waitlist Section -->
    @if($event->waitlist_count > 0)
    <div class="ju-card">
        <div class="ju-card-header d-flex justify-content-between align-items-center">
            <h5 class="ju-card-title m-0">
                Waitlist ({{ $event->waitlist_count }} people)
            </h5>
            @if($event->available_seats > 0)
            <button type="button" class="btn btn-success btn-sm" 
                    onclick="promoteFromWaitlist({{ $event->id }})">
                <i class="fas fa-user-plus me-1"></i> Promote Next in Line
            </button>
            @endif
        </div>
        <div class="ju-card-body">
            <div class="table-responsive">
                <table class="table table-ju">
                    <thead>
                        <tr>
                            <th>Position</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Joined Waitlist</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($event->waitlists as $waitlist)
                        <tr>
                            <td>
                                <div class="waitlist-position d-inline-flex">
                                    {{ $waitlist->position }}
                                </div>
                            </td>
                            <td>{{ $waitlist->user->name }}</td>
                            <td>{{ $waitlist->user->email }}</td>
                            <td>{{ $waitlist->joined_at->format('M d, Y') }}</td>
                            <td>
                                <form action="{{ route('events.waitlist.remove', $waitlist) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" 
                                            onclick="return confirm('Remove from waitlist?')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Waitlist Information -->
            <div class="alert alert-info mt-3">
                <h6><i class="fas fa-info-circle me-2"></i> Waitlist Management</h6>
                <ul class="mb-0">
                    <li>Waitlist positions are based on first-come, first-served</li>
                    <li>Click "Promote Next in Line" to move to confirmed registration</li>
                    <li>Promoted participants receive automatic notifications</li>
                    <li>You can remove participants from waitlist if needed</li>
                </ul>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function promoteFromWaitlist(eventId) {
        if(confirm('Promote next person from waitlist to confirmed registration?')) {
            $.ajax({
                url: `{{ url('events') }}/${eventId}/participants/waitlist/promote`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if(response.success) {
                        location.reload();
                    } else {
                        alert(response.message || 'Error promoting from waitlist.');
                    }
                },
                error: function() {
                    alert('Error promoting from waitlist.');
                }
            });
        }
    }
    
    $(document).ready(function() {
        // Initialize DataTable
        $('.data-table').DataTable({
            "pageLength": 25,
            "responsive": true,
            "order": [[3, 'desc']],
            "language": {
                "search": "Search participants:",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ participants",
                "paginate": {
                    "first": "First",
                    "last": "Last",
                    "next": "Next",
                    "previous": "Previous"
                }
            }
        });
    });
</script>
@endpush