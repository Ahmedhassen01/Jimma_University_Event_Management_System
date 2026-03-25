{{-- resources/views/admin/events/speakers.blade.php --}}
@extends('layouts.app')

@section('title', 'Manage Speakers - ' . $event->title . ' - Jimma University')

@section('page-title', 'Manage Event Speakers')
@section('page-subtitle', $event->title)

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.events.index') }}">Events</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.events.show', $event) }}">{{ Str::limit($event->title, 20) }}</a></li>
    <li class="breadcrumb-item active">Manage Speakers</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="ju-card shadow-sm">
                <div class="ju-card-header d-flex align-items-center justify-content-between py-3" style="background: linear-gradient(135deg, #003366 0%, #004080 100%);">
                    <h4 class="ju-card-title mb-0 text-white">
                        <i class="fas fa-users me-2"></i>
                        Speakers for: {{ $event->title }}
                    </h4>
                    <div>
                        <a href="{{ route('admin.events.show', $event) }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-2"></i>Back to Event
                        </a>
                    </div>
                </div>
                
                <div class="ju-card-body">
                    <div class="row">
                        <!-- Assigned Speakers -->
                        <div class="col-md-6">
                            <div class="ju-sub-card mb-4">
                                <div class="ju-sub-card-header" style="background: linear-gradient(135deg, #003366 0%, #004080 100%);">
                                    <h5 class="mb-0 text-white">
                                        <i class="fas fa-user-check me-2"></i>
                                        Assigned Speakers
                                    </h5>
                                </div>
                                <div class="ju-sub-card-body">
                                    <div id="assigned-speakers-list" class="assigned-speakers">
                                        @forelse($event->speakers as $speaker)
                                            @php
                                                // Parse session time if it exists
                                                $sessionTime = $speaker->pivot->session_time ? 
                                                    \Carbon\Carbon::parse($speaker->pivot->session_time)->format('Y-m-d\TH:i') : '';
                                            @endphp
                                            <div class="speaker-card mb-3" data-speaker-id="{{ $speaker->id }}" data-order="{{ $speaker->pivot->order }}">
                                                <div class="card border" style="border-color: #003366 !important;">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <div class="drag-handle me-3" style="cursor: move; color: #003366;">
                                                                <i class="fas fa-grip-vertical"></i>
                                                            </div>
                                                            @if($speaker->photo)
                                                                <img src="{{ $speaker->photo_url }}" 
                                                                     alt="{{ $speaker->name }}"
                                                                     class="rounded-circle me-3"
                                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                                            @else
                                                                <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                                                                     style="width: 50px; height: 50px; background: linear-gradient(135deg, #003366 0%, #004080 100%); color: white;">
                                                                    <i class="fas fa-user"></i>
                                                                </div>
                                                            @endif
                                                            <div class="flex-grow-1">
                                                                <h6 class="mb-1">{{ $speaker->full_name }}</h6>
                                                                <small class="text-muted d-block">{{ $speaker->position }} at {{ $speaker->organization }}</small>
                                                                @if($speaker->pivot->session_title)
                                                                    <small class="text-primary d-block mt-1">
                                                                        <i class="fas fa-microphone me-1"></i>{{ $speaker->pivot->session_title }}
                                                                    </small>
                                                                @endif
                                                                <div class="mt-1">
                                                                    @if($speaker->pivot->is_keynote)
                                                                        <span class="badge" style="background-color: #003366; color: white;">Keynote</span>
                                                                    @endif
                                                                    @if($speaker->pivot->is_moderator)
                                                                        <span class="badge bg-success">Moderator</span>
                                                                    @endif
                                                                    @if($speaker->pivot->is_panelist)
                                                                        <span class="badge bg-info">Panelist</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="dropdown">
                                                                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                                                    <i class="fas fa-ellipsis-v"></i>
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    <li>
                                                                        <a class="dropdown-item edit-speaker-btn" href="#" 
                                                                           data-speaker-id="{{ $speaker->id }}"
                                                                           data-speaker-name="{{ $speaker->name }}"
                                                                           data-session-title="{{ $speaker->pivot->session_title }}"
                                                                           data-session-time="{{ $sessionTime }}"
                                                                           data-session-duration="{{ $speaker->pivot->session_duration }}"
                                                                           data-session-description="{{ $speaker->pivot->session_description }}"
                                                                           data-is-keynote="{{ $speaker->pivot->is_keynote }}"
                                                                           data-is-moderator="{{ $speaker->pivot->is_moderator }}"
                                                                           data-is-panelist="{{ $speaker->pivot->is_panelist }}">
                                                                            <i class="fas fa-edit me-2"></i>Edit Details
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <form action="{{ route('admin.events.speakers.remove', [$event, $speaker]) }}" 
                                                                              method="POST" 
                                                                              class="d-inline"
                                                                              onsubmit="return confirm('Are you sure you want to remove this speaker from the event?')">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="dropdown-item text-danger">
                                                                                <i class="fas fa-trash me-2"></i>Remove
                                                                            </button>
                                                                        </form>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-center py-4">
                                                <i class="fas fa-user-slash fa-3x mb-3" style="color: #003366;"></i>
                                                <h6>No Speakers Assigned</h6>
                                                <p class="text-muted">Assign speakers from the list on the right.</p>
                                            </div>
                                        @endforelse
                                    </div>
                                    
                                    @if($event->speakers->count() > 1)
                                        <div class="mt-3">
                                            <button class="btn btn-sm" style="background-color: #003366; color: white;" id="saveOrderBtn">
                                                <i class="fas fa-save me-2"></i>Save Order
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Available Speakers -->
                        <div class="col-md-6">
                            <div class="ju-sub-card mb-4">
                                <div class="ju-sub-card-header" style="background: linear-gradient(135deg, #003366 0%, #004080 100%);">
                                    <h5 class="mb-0 text-white">
                                        <i class="fas fa-user-plus me-2"></i>
                                        Available Speakers
                                    </h5>
                                </div>
                                <div class="ju-sub-card-body">
                                    <div class="mb-3">
                                        <div class="input-group">
                                            <span class="input-group-text" style="border-color: #003366;">
                                                <i class="fas fa-search" style="color: #003366;"></i>
                                            </span>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="searchSpeakers" 
                                                   placeholder="Search speakers..."
                                                   style="border-color: #003366;">
                                        </div>
                                    </div>
                                    
                                    <div id="available-speakers-list">
                                        @php
                                            $assignedIds = $event->speakers->pluck('id')->toArray();
                                            $availableSpeakers = $availableSpeakers->whereNotIn('id', $assignedIds);
                                        @endphp
                                        
                                        @forelse($availableSpeakers as $speaker)
                                            <div class="speaker-card mb-2 available-speaker" data-speaker-id="{{ $speaker->id }}" data-search="{{ strtolower($speaker->name . ' ' . $speaker->position . ' ' . $speaker->organization) }}">
                                                <div class="card border" style="border-color: #dee2e6;">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            @if($speaker->photo)
                                                                <img src="{{ $speaker->photo_url }}" 
                                                                     alt="{{ $speaker->name }}"
                                                                     class="rounded-circle me-3"
                                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                                            @else
                                                                <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                                                                     style="width: 40px; height: 40px; background: linear-gradient(135deg, #003366 0%, #004080 100%); color: white;">
                                                                    <i class="fas fa-user fa-sm"></i>
                                                                </div>
                                                            @endif
                                                            <div class="flex-grow-1">
                                                                <h6 class="mb-1">{{ $speaker->full_name }}</h6>
                                                                <small class="text-muted d-block">{{ $speaker->position }} at {{ $speaker->organization }}</small>
                                                                @if($speaker->expertise)
                                                                    <small class="text-muted">
                                                                        <i class="fas fa-tag me-1"></i>{{ is_array($speaker->expertise) ? implode(', ', $speaker->expertise) : $speaker->expertise }}
                                                                    </small>
                                                                @endif
                                                            </div>
                                                            <button class="btn btn-sm assign-speaker-btn" 
                                                                    data-speaker-id="{{ $speaker->id }}"
                                                                    style="background-color: #003366; color: white;">
                                                                <i class="fas fa-plus me-1"></i>Assign
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-center py-4">
                                                <i class="fas fa-user-check fa-3x mb-3" style="color: #003366;"></i>
                                                <h6>No Available Speakers</h6>
                                                <p class="text-muted">All speakers have been assigned to this event.</p>
                                                <a href="{{ route('speakers.create') }}" class="btn btn-sm" style="background-color: #003366; color: white;">
                                                    <i class="fas fa-plus me-2"></i>Create New Speaker
                                                </a>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Speaker Modal -->
<div class="modal fade" id="editSpeakerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #003366 0%, #004080 100%);">
                <h5 class="modal-title text-white">
                    <i class="fas fa-edit me-2"></i>Edit Speaker Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editSpeakerForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="edit_speaker_id" name="speaker_id">
                    
                    <div class="mb-3">
                        <label class="form-label required" style="color: #003366;">Speaker Name</label>
                        <input type="text" class="form-control" id="edit_speaker_name" readonly disabled>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label" style="color: #003366;">Session Title</label>
                        <input type="text" class="form-control" name="session_title" id="edit_session_title" 
                               placeholder="e.g., Keynote Address, Workshop Session">
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label" style="color: #003366;">Session Time</label>
                            <input type="datetime-local" class="form-control" name="session_time" id="edit_session_time">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="color: #003366;">Duration (minutes)</label>
                            <input type="number" class="form-control" name="session_duration" id="edit_session_duration" 
                                   min="1" placeholder="e.g., 60">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label" style="color: #003366;">Session Description</label>
                        <textarea class="form-control" name="session_description" id="edit_session_description" 
                                  rows="3" placeholder="Describe the speaker's session..."></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_keynote" value="1" id="edit_is_keynote">
                                <label class="form-check-label" for="edit_is_keynote" style="color: #003366;">
                                    Keynote Speaker
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_moderator" value="1" id="edit_is_moderator">
                                <label class="form-check-label" for="edit_is_moderator" style="color: #003366;">
                                    Moderator
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_panelist" value="1" id="edit_is_panelist">
                                <label class="form-check-label" for="edit_is_panelist" style="color: #003366;">
                                    Panelist
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn" style="background-color: #003366; color: white;">
                        <i class="fas fa-save me-2"></i>Update Details
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Assign Speaker Modal -->
<div class="modal fade" id="assignSpeakerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #003366 0%, #004080 100%);">
                <h5 class="modal-title text-white">
                    <i class="fas fa-user-plus me-2"></i>Assign Speaker to Event
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.events.speakers.assign', $event) }}" method="POST" id="assignSpeakerForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="assign_speaker_id" name="speakers[0][speaker_id]">
                    
                    <div class="mb-3">
                        <label class="form-label required" style="color: #003366;">Speaker</label>
                        <input type="text" class="form-control" id="assign_speaker_name" readonly disabled>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label" style="color: #003366;">Session Title</label>
                        <input type="text" class="form-control" name="speakers[0][session_title]" 
                               placeholder="e.g., Keynote Address, Workshop Session">
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label" style="color: #003366;">Session Time</label>
                            <input type="datetime-local" class="form-control" name="speakers[0][session_time]">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="color: #003366;">Duration (minutes)</label>
                            <input type="number" class="form-control" name="speakers[0][session_duration]" 
                                   min="1" placeholder="e.g., 60">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label" style="color: #003366;">Session Description</label>
                        <textarea class="form-control" name="speakers[0][session_description]" 
                                  rows="3" placeholder="Describe the speaker's session..."></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="speakers[0][is_keynote]" value="1" id="assign_is_keynote">
                                <label class="form-check-label" for="assign_is_keynote" style="color: #003366;">
                                    Keynote Speaker
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="speakers[0][is_moderator]" value="1" id="assign_is_moderator">
                                <label class="form-check-label" for="assign_is_moderator" style="color: #003366;">
                                    Moderator
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="speakers[0][is_panelist]" value="1" id="assign_is_panelist">
                                <label class="form-check-label" for="assign_is_panelist" style="color: #003366;">
                                    Panelist
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn" style="background-color: #003366; color: white;">
                        <i class="fas fa-plus me-2"></i>Assign Speaker
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .required::after {
        content: " *";
        color: #dc3545;
    }
    
    .ju-sub-card {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .ju-sub-card-header {
        color: white;
        padding: 12px 20px;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    
    .ju-sub-card-body {
        padding: 20px;
    }
    
    .assigned-speakers {
        min-height: 200px;
    }
    
    .speaker-card {
        transition: transform 0.2s;
    }
    
    .speaker-card:hover {
        transform: translateX(5px);
    }
    
    .drag-handle {
        cursor: move;
        opacity: 0.5;
        transition: opacity 0.2s;
    }
    
    .drag-handle:hover {
        opacity: 1;
    }
    
    .speaker-card.dragging {
        opacity: 0.5;
    }
    
    .speaker-card.over {
        border-top: 2px solid #003366;
    }
    
    .available-speaker {
        cursor: pointer;
    }
    
    .available-speaker:hover {
        background-color: #f8f9fa;
    }
    
    .form-check-input:checked {
        background-color: #003366;
        border-color: #003366;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Sortable for drag and drop reordering
        const assignedSpeakersList = document.getElementById('assigned-speakers-list');
        
        if (assignedSpeakersList) {
            new Sortable(assignedSpeakersList, {
                animation: 150,
                handle: '.drag-handle',
                ghostClass: 'dragging',
                dragClass: 'dragging',
                onEnd: function() {
                    // Enable save order button
                    document.getElementById('saveOrderBtn')?.classList.remove('d-none');
                }
            });
        }
        
        // Save order
        const saveOrderBtn = document.getElementById('saveOrderBtn');
        if (saveOrderBtn) {
            saveOrderBtn.addEventListener('click', function() {
                const speakers = [];
                document.querySelectorAll('.speaker-card[data-speaker-id]').forEach((card, index) => {
                    speakers.push({
                        id: card.dataset.speakerId,
                        order: index
                    });
                });
                
                fetch('{{ route("admin.events.speakers.reorder", $event) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ speakers: speakers })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('Speakers reordered successfully!', 'success');
                        saveOrderBtn.classList.add('d-none');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Error reordering speakers', 'error');
                });
            });
        }
        
        // Search speakers
        const searchInput = document.getElementById('searchSpeakers');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                document.querySelectorAll('.available-speaker').forEach(speaker => {
                    const searchData = speaker.dataset.search || '';
                    if (searchTerm === '' || searchData.includes(searchTerm)) {
                        speaker.style.display = '';
                    } else {
                        speaker.style.display = 'none';
                    }
                });
            });
        }
        
        // Assign speaker
        document.querySelectorAll('.assign-speaker-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const speakerId = this.dataset.speakerId;
                const speakerCard = this.closest('.available-speaker');
                const speakerName = speakerCard.querySelector('h6').textContent;
                
                document.getElementById('assign_speaker_id').value = speakerId;
                document.getElementById('assign_speaker_name').value = speakerName;
                
                new bootstrap.Modal(document.getElementById('assignSpeakerModal')).show();
            });
        });
        
        // Edit speaker
        document.querySelectorAll('.edit-speaker-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                
                const speakerId = this.dataset.speakerId;
                const speakerName = this.dataset.speakerName;
                const sessionTitle = this.dataset.sessionTitle;
                const sessionTime = this.dataset.sessionTime;
                const sessionDuration = this.dataset.sessionDuration;
                const sessionDescription = this.dataset.sessionDescription;
                const isKeynote = this.dataset.isKeynote === '1';
                const isModerator = this.dataset.isModerator === '1';
                const isPanelist = this.dataset.isPanelist === '1';
                
                document.getElementById('edit_speaker_id').value = speakerId;
                document.getElementById('edit_speaker_name').value = speakerName;
                document.getElementById('edit_session_title').value = sessionTitle || '';
                document.getElementById('edit_session_time').value = sessionTime || '';
                document.getElementById('edit_session_duration').value = sessionDuration || '';
                document.getElementById('edit_session_description').value = sessionDescription || '';
                document.getElementById('edit_is_keynote').checked = isKeynote;
                document.getElementById('edit_is_moderator').checked = isModerator;
                document.getElementById('edit_is_panelist').checked = isPanelist;
                
                const form = document.getElementById('editSpeakerForm');
                form.action = `{{ route('admin.events.speakers.update', [$event, 'SPEAKER_ID']) }}`.replace('SPEAKER_ID', speakerId);
                
                new bootstrap.Modal(document.getElementById('editSpeakerModal')).show();
            });
        });
        
        // Show toast function
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
            toast.style.top = '20px';
            toast.style.right = '20px';
            toast.style.zIndex = '9999';
            toast.style.minWidth = '300px';
            toast.innerHTML = `
                <strong>${type === 'error' ? 'Error' : type === 'success' ? 'Success' : 'Info'}!</strong> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 5000);
        }
    });
</script>
@endpush