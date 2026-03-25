{{-- resources/views/admin/events/partials/speaker-assignment.blade.php --}}

<!-- Speakers Section - Full Width -->
<div class="row mt-4">
    <div class="col-12">
        <div class="ju-sub-card mb-4">
            <div class="ju-sub-card-header" style="background: linear-gradient(135deg, #003366 0%, #004080 100%);">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-users me-2"></i>
                    Event Speakers
                </h5>
            </div>
            <div class="ju-sub-card-body">
                <div class="alert alert-info" style="background-color: #e6f0ff; border-color: #003366; color: #003366;">
                    <i class="fas fa-info-circle me-2"></i>
                    Add speakers to your event. You can specify their session details and role.
                </div>
                
                <div id="speakers-container">
                    <!-- Existing speakers will be loaded here -->
                    @if(isset($event) && $event->speakers->count() > 0)
                        @foreach($event->speakers as $index => $speaker)
                            @php
                                $sessionTime = $speaker->pivot->session_time ? 
                                    \Carbon\Carbon::parse($speaker->pivot->session_time)->format('Y-m-d\TH:i') : '';
                            @endphp
                            <div class="speaker-item card mb-3 border" style="border-color: #003366 !important;" data-speaker-id="{{ $speaker->id }}">
                                <div class="card-header" style="background-color: #f0f5ff; border-bottom-color: #003366;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0" style="color: #003366;">
                                            <i class="fas fa-user-tie me-2"></i>
                                            Speaker <span class="speaker-number">{{ $index + 1 }}</span>: {{ $speaker->name }}
                                        </h6>
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-speaker">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <input type="hidden" name="speakers[{{ $index }}][speaker_id]" class="speaker-id-input" value="{{ $speaker->id }}">
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label required" style="color: #003366;">Speaker</label>
                                                <select class="form-select speaker-select" required>
                                                    <option value="">Select a speaker</option>
                                                    @foreach($speakers as $availableSpeaker)
                                                        <option value="{{ $availableSpeaker->id }}" 
                                                                data-name="{{ $availableSpeaker->name }}"
                                                                data-title="{{ $availableSpeaker->title }}"
                                                                data-organization="{{ $availableSpeaker->organization }}"
                                                                data-photo="{{ $availableSpeaker->photo_url }}"
                                                                {{ $availableSpeaker->id == $speaker->id ? 'selected' : '' }}>
                                                            {{ $availableSpeaker->name }} - {{ $availableSpeaker->title ?? '' }} {{ $availableSpeaker->organization ? '('.$availableSpeaker->organization.')' : '' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label" style="color: #003366;">Session Title</label>
                                                <input type="text" 
                                                       class="form-control" 
                                                       name="speakers[{{ $index }}][session_title]" 
                                                       value="{{ $speaker->pivot->session_title }}"
                                                       placeholder="e.g., Keynote Address, Workshop Session">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class="form-label" style="color: #003366;">Session Time</label>
                                                <input type="datetime-local" 
                                                       class="form-control" 
                                                       name="speakers[{{ $index }}][session_time]"
                                                       value="{{ $sessionTime }}">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class="form-label" style="color: #003366;">Duration (minutes)</label>
                                                <input type="number" 
                                                       class="form-control" 
                                                       name="speakers[{{ $index }}][session_duration]" 
                                                       value="{{ $speaker->pivot->session_duration }}"
                                                       min="1" 
                                                       placeholder="e.g., 60">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label class="form-label" style="color: #003366;">Order</label>
                                                <input type="number" 
                                                       class="form-control" 
                                                       name="speakers[{{ $index }}][order]" 
                                                       value="{{ $speaker->pivot->order }}" 
                                                       min="0">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label" style="color: #003366;">Session Description</label>
                                        <textarea class="form-control" 
                                                  name="speakers[{{ $index }}][session_description]" 
                                                  rows="2" 
                                                  placeholder="Describe the speaker's session...">{{ $speaker->pivot->session_description }}</textarea>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" 
                                                       type="checkbox" 
                                                       name="speakers[{{ $index }}][is_keynote]" 
                                                       value="1"
                                                       id="keynote-{{ $index }}"
                                                       {{ $speaker->pivot->is_keynote ? 'checked' : '' }}>
                                                <label class="form-check-label" for="keynote-{{ $index }}" style="color: #003366;">
                                                    Keynote Speaker
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" 
                                                       type="checkbox" 
                                                       name="speakers[{{ $index }}][is_moderator]" 
                                                       value="1"
                                                       id="moderator-{{ $index }}"
                                                       {{ $speaker->pivot->is_moderator ? 'checked' : '' }}>
                                                <label class="form-check-label" for="moderator-{{ $index }}" style="color: #003366;">
                                                    Moderator
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" 
                                                       type="checkbox" 
                                                       name="speakers[{{ $index }}][is_panelist]" 
                                                       value="1"
                                                       id="panelist-{{ $index }}"
                                                       {{ $speaker->pivot->is_panelist ? 'checked' : '' }}>
                                                <label class="form-check-label" for="panelist-{{ $index }}" style="color: #003366;">
                                                    Panelist
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                
                <div class="text-center mt-3">
                    <button type="button" class="btn" style="background-color: #003366; color: white;" id="addSpeakerBtn">
                        <i class="fas fa-plus-circle me-2"></i>
                        Add Speaker
                    </button>
                </div>
                
                <template id="speaker-template">
                    <div class="speaker-item card mb-3 border" style="border-color: #003366 !important;">
                        <div class="card-header" style="background-color: #f0f5ff; border-bottom-color: #003366;">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0" style="color: #003366;">
                                    <i class="fas fa-user-tie me-2"></i>
                                    Speaker <span class="speaker-number"></span>
                                </h6>
                                <button type="button" class="btn btn-sm btn-outline-danger remove-speaker">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <input type="hidden" name="speakers[INDEX][speaker_id]" class="speaker-id-input">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label required" style="color: #003366;">Select Speaker</label>
                                        <select class="form-select speaker-select" required>
                                            <option value="">Select a speaker</option>
                                            @foreach($speakers as $speaker)
                                                <option value="{{ $speaker->id }}" 
                                                        data-name="{{ $speaker->name }}"
                                                        data-title="{{ $speaker->title }}"
                                                        data-organization="{{ $speaker->organization }}"
                                                        data-photo="{{ $speaker->photo_url }}">
                                                    {{ $speaker->name }} - {{ $speaker->title ?? '' }} {{ $speaker->organization ? '('.$speaker->organization.')' : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label" style="color: #003366;">Session Title</label>
                                        <input type="text" 
                                               class="form-control" 
                                               name="speakers[INDEX][session_title]" 
                                               placeholder="e.g., Keynote Address, Workshop Session">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label" style="color: #003366;">Session Time</label>
                                        <input type="datetime-local" 
                                               class="form-control" 
                                               name="speakers[INDEX][session_time]">
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label" style="color: #003366;">Duration (minutes)</label>
                                        <input type="number" 
                                               class="form-control" 
                                               name="speakers[INDEX][session_duration]" 
                                               min="1" 
                                               placeholder="e.g., 60">
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label" style="color: #003366;">Order</label>
                                        <input type="number" 
                                               class="form-control" 
                                               name="speakers[INDEX][order]" 
                                               value="0" 
                                               min="0">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label class="form-label" style="color: #003366;">Session Description</label>
                                <textarea class="form-control" 
                                          name="speakers[INDEX][session_description]" 
                                          rows="2" 
                                          placeholder="Describe the speaker's session..."></textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="speakers[INDEX][is_keynote]" 
                                               value="1"
                                               id="keynote-INDEX">
                                        <label class="form-check-label" for="keynote-INDEX" style="color: #003366;">
                                            Keynote Speaker
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="speakers[INDEX][is_moderator]" 
                                               value="1"
                                               id="moderator-INDEX">
                                        <label class="form-check-label" for="moderator-INDEX" style="color: #003366;">
                                            Moderator
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="speakers[INDEX][is_panelist]" 
                                               value="1"
                                               id="panelist-INDEX">
                                        <label class="form-check-label" for="panelist-INDEX" style="color: #003366;">
                                            Panelist
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function() {
    'use strict';
    
    // Wait for DOM to be fully loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSpeakerAssignment);
    } else {
        initSpeakerAssignment();
    }
    
    function initSpeakerAssignment() {
        console.log('Initializing speaker assignment...');
        
        let speakerCount = {{ isset($event) ? $event->speakers->count() : 0 }};
        const speakersContainer = document.getElementById('speakers-container');
        const speakerTemplate = document.getElementById('speaker-template');
        const addSpeakerBtn = document.getElementById('addSpeakerBtn');
        
        if (!speakersContainer || !speakerTemplate || !addSpeakerBtn) {
            console.error('Required elements not found:', {
                container: !!speakersContainer,
                template: !!speakerTemplate,
                button: !!addSpeakerBtn
            });
            return;
        }
        
        // Add speaker function
        function addSpeaker() {
            try {
                const template = speakerTemplate.content.cloneNode(true);
                const speakerDiv = document.createElement('div');
                speakerDiv.innerHTML = template.querySelector('.speaker-item').outerHTML.replace(/INDEX/g, speakerCount);
                const speakerItem = speakerDiv.firstElementChild;
                
                speakersContainer.appendChild(speakerItem);
                
                // Initialize the new speaker item
                initializeSpeakerItem(speakerItem, speakerCount + 1);
                
                speakerCount++;
                console.log('Speaker added, total:', speakerCount);
            } catch (error) {
                console.error('Error adding speaker:', error);
            }
        }
        
        // Initialize a speaker item
        function initializeSpeakerItem(item, number) {
            // Set speaker number
            const numberSpan = item.querySelector('.speaker-number');
            if (numberSpan) {
                numberSpan.textContent = number;
            }
            
            // Initialize select change handler
            const select = item.querySelector('.speaker-select');
            const speakerIdInput = item.querySelector('.speaker-id-input');
            
            if (select && speakerIdInput) {
                select.addEventListener('change', function() {
                    speakerIdInput.value = this.value;
                });
            }
            
            // Initialize remove button
            const removeBtn = item.querySelector('.remove-speaker');
            if (removeBtn) {
                removeBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (confirm('Are you sure you want to remove this speaker?')) {
                        item.remove();
                        updateSpeakerNumbers();
                    }
                });
            }
        }
        
        // Update speaker numbers after removal
        function updateSpeakerNumbers() {
            const speakers = document.querySelectorAll('.speaker-item');
            speakers.forEach((speaker, index) => {
                const numberSpan = speaker.querySelector('.speaker-number');
                if (numberSpan) {
                    numberSpan.textContent = index + 1;
                }
                
                // Update all input names with new index
                const inputs = speaker.querySelectorAll('input, select, textarea');
                inputs.forEach(input => {
                    const name = input.getAttribute('name');
                    if (name) {
                        const newName = name.replace(/\[\d+\]/, '[' + index + ']');
                        input.setAttribute('name', newName);
                    }
                });
                
                // Update checkbox IDs
                const checkboxes = speaker.querySelectorAll('input[type="checkbox"]');
                checkboxes.forEach(checkbox => {
                    const id = checkbox.getAttribute('id');
                    if (id) {
                        const newId = id.replace(/-\d+/, '-' + index);
                        checkbox.setAttribute('id', newId);
                        
                        // Update associated label
                        const label = speaker.querySelector('label[for="' + id + '"]');
                        if (label) {
                            label.setAttribute('for', newId);
                        }
                    }
                });
            });
            
            speakerCount = speakers.length;
        }
        
        // Initialize existing speakers
        document.querySelectorAll('.speaker-item').forEach((item, index) => {
            initializeSpeakerItem(item, index + 1);
        });
        
        // Add click event listener to add button
        addSpeakerBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            addSpeaker();
        });
        
        console.log('Speaker assignment initialized successfully');
    }
})();
</script>
@endpush