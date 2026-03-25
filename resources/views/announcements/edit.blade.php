{{-- resources/views/announcements/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Announcement: ' . $announcement->title)
@section('page-title', 'Edit Announcement')

@section('content')
<style>
    .editor-toolbar {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-bottom: none;
        padding: 10px;
        border-radius: 5px 5px 0 0;
    }
    
    .editor-content {
        min-height: 300px;
        border: 1px solid #dee2e6;
        padding: 15px;
        border-radius: 0 0 5px 5px;
    }
    
    .audience-options {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 10px;
    }
    
    .audience-option {
        border: 2px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .audience-option:hover {
        border-color: #4361ee;
        background-color: #f8f9ff;
    }
    
    .audience-option.selected {
        border-color: #4361ee;
        background-color: #eef2ff;
    }
    
    .audience-option input[type="radio"] {
        display: none;
    }
    
    .target-users-list {
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        padding: 10px;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Edit Announcement</h5>
                        <span class="badge bg-{{ $announcement->is_published ? 'success' : 'warning' }}">
                            {{ $announcement->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('announcements.update', $announcement) }}" method="POST" id="announcementForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Title -->
                        <div class="mb-4">
                            <label for="title" class="form-label">Announcement Title *</label>
                            <input type="text" name="title" id="title" class="form-control form-control-lg" 
                                   placeholder="Enter announcement title..." required
                                   value="{{ old('title', $announcement->title) }}">
                            @error('title')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Type Selection -->
                        <div class="mb-4">
                            <label class="form-label">Announcement Type *</label>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="type" 
                                               id="typeEvent" value="event" {{ old('type', $announcement->type) == 'event' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="typeEvent">
                                            <i class="fas fa-calendar-alt me-2"></i>Event
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="type" 
                                               id="typeCampus" value="campus" {{ old('type', $announcement->type) == 'campus' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="typeCampus">
                                            <i class="fas fa-university me-2"></i>Campus
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="type" 
                                               id="typeGeneral" value="general" {{ old('type', $announcement->type) == 'general' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="typeGeneral">
                                            <i class="fas fa-bullhorn me-2"></i>General
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="type" 
                                               id="typeUrgent" value="urgent" {{ old('type', $announcement->type) == 'urgent' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="typeUrgent">
                                            <i class="fas fa-exclamation-triangle me-2"></i>Urgent
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @error('type')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Content -->
                        <div class="mb-4">
                            <label for="content" class="form-label">Announcement Content *</label>
                            
                            <!-- Simple Editor Toolbar -->
                            <div class="editor-toolbar">
                                <div class="btn-group btn-group-sm me-2 mb-1" role="group">
                                    <button type="button" class="btn btn-outline-secondary" onclick="formatText('bold')">
                                        <i class="fas fa-bold"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="formatText('italic')">
                                        <i class="fas fa-italic"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="formatText('underline')">
                                        <i class="fas fa-underline"></i>
                                    </button>
                                </div>
                                <div class="btn-group btn-group-sm me-2 mb-1" role="group">
                                    <button type="button" class="btn btn-outline-secondary" onclick="insertList('ul')">
                                        <i class="fas fa-list-ul"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="insertList('ol')">
                                        <i class="fas fa-list-ol"></i>
                                    </button>
                                </div>
                                <div class="btn-group btn-group-sm me-2 mb-1" role="group">
                                    <button type="button" class="btn btn-outline-secondary" onclick="insertHeading(2)">
                                        <i class="fas fa-heading"></i> H2
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="insertHeading(3)">
                                        <i class="fas fa-heading"></i> H3
                                    </button>
                                </div>
                                <div class="btn-group btn-group-sm mb-1" role="group">
                                    <button type="button" class="btn btn-outline-secondary" onclick="insertLink()">
                                        <i class="fas fa-link"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="clearFormat()">
                                        <i class="fas fa-eraser"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Content Textarea -->
                            <textarea name="content" id="content" class="form-control editor-content" 
                                      rows="12" placeholder="Enter announcement content here..." required>{{ old('content', $announcement->content) }}</textarea>
                            @error('content')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                            <div class="mt-2 text-muted">
                                <small>You can use basic HTML formatting: &lt;b&gt;, &lt;i&gt;, &lt;u&gt;, &lt;ul&gt;, &lt;ol&gt;, &lt;li&gt;, &lt;h2&gt;, &lt;h3&gt;, &lt;a&gt;</small>
                            </div>
                        </div>
                        
                        <!-- Audience Selection -->
                        <div class="mb-4">
                            <label class="form-label">Target Audience *</label>
                            <div class="audience-options mb-3">
                                <label class="audience-option {{ old('audience', $announcement->audience) == 'all' ? 'selected' : '' }}">
                                    <input type="radio" name="audience" value="all" 
                                           {{ old('audience', $announcement->audience) == 'all' ? 'checked' : '' }}>
                                    <div>
                                        <i class="fas fa-users fa-2x mb-2"></i>
                                        <div class="fw-semibold">Everyone</div>
                                        <small class="text-muted">All users</small>
                                    </div>
                                </label>
                                
                                <label class="audience-option {{ old('audience', $announcement->audience) == 'students' ? 'selected' : '' }}">
                                    <input type="radio" name="audience" value="students" 
                                           {{ old('audience', $announcement->audience) == 'students' ? 'checked' : '' }}>
                                    <div>
                                        <i class="fas fa-user-graduate fa-2x mb-2"></i>
                                        <div class="fw-semibold">Students</div>
                                        <small class="text-muted">Students only</small>
                                    </div>
                                </label>
                                
                                <label class="audience-option {{ old('audience', $announcement->audience) == 'faculty' ? 'selected' : '' }}">
                                    <input type="radio" name="audience" value="faculty" 
                                           {{ old('audience', $announcement->audience) == 'faculty' ? 'checked' : '' }}>
                                    <div>
                                        <i class="fas fa-chalkboard-teacher fa-2x mb-2"></i>
                                        <div class="fw-semibold">Faculty</div>
                                        <small class="text-muted">Faculty only</small>
                                    </div>
                                </label>
                                
                                <label class="audience-option {{ old('audience', $announcement->audience) == 'staff' ? 'selected' : '' }}">
                                    <input type="radio" name="audience" value="staff" 
                                           {{ old('audience', $announcement->audience) == 'staff' ? 'checked' : '' }}>
                                    <div>
                                        <i class="fas fa-user-tie fa-2x mb-2"></i>
                                        <div class="fw-semibold">Staff</div>
                                        <small class="text-muted">Staff only</small>
                                    </div>
                                </label>

                                <label class="audience-option {{ old('audience', $announcement->audience) == 'event_managers' ? 'selected' : '' }}">
                                    <input type="radio" name="audience" value="event_managers" 
                                           {{ old('audience', $announcement->audience) == 'event_managers' ? 'checked' : '' }}>
                                    <div>
                                        <i class="fas fa-calendar-check fa-2x mb-2"></i>
                                        <div class="fw-semibold">Event Managers</div>
                                        <small class="text-muted">Event managers only</small>
                                    </div>
                                </label>
                                
                                <label class="audience-option {{ old('audience', $announcement->audience) == 'specific' ? 'selected' : '' }}">
                                    <input type="radio" name="audience" value="specific" 
                                           {{ old('audience', $announcement->audience) == 'specific' ? 'checked' : '' }}>
                                    <div>
                                        <i class="fas fa-user-friends fa-2x mb-2"></i>
                                        <div class="fw-semibold">Specific</div>
                                        <small class="text-muted">Select users</small>
                                    </div>
                                </label>
                            </div>
                            @error('audience')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                            
                            <!-- Specific Users Selection -->
                            <div id="specificUsersSection" class="mt-3" style="display: {{ old('audience', $announcement->audience) == 'specific' ? 'block' : 'none' }};">
                                <label class="form-label">Select Specific Users</label>
                                <div class="target-users-list">
                                    @php
                                        $selectedUsers = old('target_ids', $announcement->target_ids ?? []);
                                    @endphp
                                    @foreach($users as $user)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="target_ids[]" 
                                               value="{{ $user->id }}" id="user{{ $user->id }}"
                                               {{ in_array($user->id, (array)$selectedUsers) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="user{{ $user->id }}">
                                            {{ $user->name }} ({{ $user->email }})
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                @error('target_ids')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                                <div class="mt-2">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="selectAllUsers()">
                                        <i class="fas fa-check-double me-1"></i>Select All
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deselectAllUsers()">
                                        <i class="fas fa-times me-1"></i>Deselect All
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Expiration Date -->
                        <div class="mb-4">
                            <label for="expires_at" class="form-label">Expiration Date (Optional)</label>
                            <input type="datetime-local" name="expires_at" id="expires_at" 
                                   class="form-control" 
                                   value="{{ old('expires_at', $announcement->expires_at ? $announcement->expires_at->format('Y-m-d\TH:i') : '') }}">
                            <small class="text-muted">Leave empty if the announcement should not expire</small>
                            @error('expires_at')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Publish Status -->
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" 
                                       name="is_published" id="is_published" value="1"
                                       {{ old('is_published', $announcement->is_published) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_published">
                                    <strong>Published Status</strong>
                                </label>
                                <div class="text-muted">
                                    <small>Toggle to publish/unpublish the announcement</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <div>
                                <a href="{{ route('announcements.show', $announcement) }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                                <button type="button" class="btn btn-outline-danger ms-2" 
                                        onclick="if(confirm('Are you sure you want to delete this announcement?')) { document.getElementById('deleteForm').submit(); }">
                                    <i class="fas fa-trash me-2"></i>Delete
                                </button>
                            </div>
                            <div class="btn-group">
                                <a href="{{ route('announcements.show', $announcement) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-eye me-2"></i>Preview
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Save Changes
                                </button>
                            </div>
                        </div>
                    </form>
                    
                    <!-- Delete Form (hidden) -->
                    <form id="deleteForm" action="{{ route('announcements.destroy', $announcement) }}" method="POST" class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Announcement Info Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Announcement Information
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-hashtag text-muted me-2"></i>
                            <strong>ID:</strong> #{{ $announcement->id }}
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-user text-muted me-2"></i>
                            <strong>Created By:</strong> {{ $announcement->creator->name ?? 'System' }}
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-calendar text-muted me-2"></i>
                            <strong>Created On:</strong> {{ $announcement->created_at->format('M d, Y h:i A') }}
                        </li>
                        @if($announcement->published_at)
                        <li class="mb-2">
                            <i class="fas fa-paper-plane text-muted me-2"></i>
                            <strong>Published On:</strong> {{ $announcement->published_at->format('M d, Y h:i A') }}
                        </li>
                        @endif
                        <li class="mb-2">
                            <i class="fas fa-eye text-muted me-2"></i>
                            <strong>Views:</strong> {{ $announcement->views }}
                        </li>
                        <li>
                            <i class="fas fa-history text-muted me-2"></i>
                            <strong>Last Updated:</strong> {{ $announcement->updated_at->diffForHumans() }}
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Quick Actions Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('announcements.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-list me-2"></i>All Announcements
                        </a>
                        <a href="{{ route('announcements.create') }}" class="btn btn-outline-success">
                            <i class="fas fa-plus-circle me-2"></i>Create New
                        </a>
                        @if($announcement->is_published)
                        <form action="{{ route('announcements.toggle-publish', $announcement) }}" method="POST" class="d-grid">
                            @csrf
                            <button type="submit" class="btn btn-outline-warning">
                                <i class="fas fa-eye-slash me-2"></i>Unpublish
                            </button>
                        </form>
                        @else
                        <form action="{{ route('announcements.toggle-publish', $announcement) }}" method="POST" class="d-grid">
                            @csrf
                            <button type="submit" class="btn btn-outline-info">
                                <i class="fas fa-eye me-2"></i>Publish Now
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Handle audience selection
document.querySelectorAll('input[name="audience"]').forEach(radio => {
    radio.addEventListener('change', function() {
        // Update UI for selected option
        document.querySelectorAll('.audience-option').forEach(option => {
            option.classList.remove('selected');
        });
        this.closest('.audience-option').classList.add('selected');
        
        // Show/hide specific users section
        const specificUsersSection = document.getElementById('specificUsersSection');
        if (this.value === 'specific') {
            specificUsersSection.style.display = 'block';
        } else {
            specificUsersSection.style.display = 'none';
        }
    });
});

// Text editor functions
function formatText(command) {
    const textarea = document.getElementById('content');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const selectedText = textarea.value.substring(start, end);
    
    let formattedText = selectedText;
    switch(command) {
        case 'bold':
            formattedText = `<b>${selectedText}</b>`;
            break;
        case 'italic':
            formattedText = `<i>${selectedText}</i>`;
            break;
        case 'underline':
            formattedText = `<u>${selectedText}</u>`;
            break;
    }
    
    textarea.value = textarea.value.substring(0, start) + formattedText + textarea.value.substring(end);
    textarea.focus();
    textarea.setSelectionRange(start + formattedText.length, start + formattedText.length);
}

function insertList(type) {
    const textarea = document.getElementById('content');
    const start = textarea.selectionStart;
    const list = type === 'ul' 
        ? '<ul>\n  <li>Item 1</li>\n  <li>Item 2</li>\n  <li>Item 3</li>\n</ul>'
        : '<ol>\n  <li>Item 1</li>\n  <li>Item 2</li>\n  <li>Item 3</li>\n</ol>';
    
    textarea.value = textarea.value.substring(0, start) + '\n' + list + '\n' + textarea.value.substring(start);
    textarea.focus();
}

function insertHeading(level) {
    const textarea = document.getElementById('content');
    const start = textarea.selectionStart;
    const heading = `<h${level}>Heading Text</h${level}>`;
    
    textarea.value = textarea.value.substring(0, start) + '\n' + heading + '\n' + textarea.value.substring(start);
    textarea.focus();
}

function insertLink() {
    const textarea = document.getElementById('content');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const selectedText = textarea.value.substring(start, end) || 'Link Text';
    const link = `<a href="https://example.com" target="_blank">${selectedText}</a>`;
    
    textarea.value = textarea.value.substring(0, start) + link + textarea.value.substring(end);
    textarea.focus();
}

function clearFormat() {
    const textarea = document.getElementById('content');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const selectedText = textarea.value.substring(start, end);
    
    // Remove HTML tags
    const plainText = selectedText.replace(/<[^>]*>/g, '');
    
    textarea.value = textarea.value.substring(0, start) + plainText + textarea.value.substring(end);
    textarea.focus();
}

// User selection functions
function selectAllUsers() {
    document.querySelectorAll('input[name="target_ids[]"]').forEach(checkbox => {
        checkbox.checked = true;
    });
}

function deselectAllUsers() {
    document.querySelectorAll('input[name="target_ids[]"]').forEach(checkbox => {
        checkbox.checked = false;
    });
}

// Set minimum datetime for expiration (current time)
document.getElementById('expires_at').min = new Date().toISOString().slice(0, 16);

// Form validation
document.getElementById('announcementForm').addEventListener('submit', function(e) {
    const title = document.getElementById('title').value.trim();
    const content = document.getElementById('content').value.trim();
    
    if (!title) {
        e.preventDefault();
        alert('Please enter a title for the announcement.');
        document.getElementById('title').focus();
        return;
    }
    
    if (!content) {
        e.preventDefault();
        alert('Please enter content for the announcement.');
        document.getElementById('content').focus();
        return;
    }
    
    // If specific audience is selected, check if users are selected
    const specificAudience = document.querySelector('input[name="audience"]:checked').value === 'specific';
    if (specificAudience) {
        const selectedUsers = document.querySelectorAll('input[name="target_ids[]"]:checked').length;
        if (selectedUsers === 0) {
            e.preventDefault();
            alert('Please select at least one user for specific audience.');
            return;
        }
    }
});
</script>
@endpush
