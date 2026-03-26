@extends('layouts.app')

@section('title', 'Create Event - Jimma University')
@section('page-title', 'Create New Event')
@section('page-subtitle', 'Plan and publish a university event')

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.events.index') }}">Events</a></li>
    <li class="breadcrumb-item active">Create Event</li>
@endsection

@section('content')
@php($isEventManagerCreator = auth()->user()->hasRole('event-manager'))
<div class="container-fluid py-3 modern-event-create">
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the following:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data" id="eventCreateForm">
        @csrf
        <div class="row g-4">
            <div class="col-xl-8">
                <div class="modern-card mb-4">
                    <div class="modern-card-head">
                        <h5><i class="fas fa-pen-ruler me-2"></i>Event Details</h5>
                    </div>
                    <div class="modern-card-body row g-3">
                        <div class="col-12">
                            <label class="form-label required">Event Title</label>
                            <input type="text" name="title" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror" required>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Event Type</label>
                            <select name="event_type" class="form-select @error('event_type') is-invalid @enderror" required>
                                <option value="">Select event type</option>
                                @foreach($eventTypes as $value => $label)
                                    <option value="{{ $value }}" {{ old('event_type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('event_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Organizer</label>
                            <input type="text" name="organizer" value="{{ old('organizer', auth()->user()->name) }}" class="form-control @error('organizer') is-invalid @enderror" required>
                            @error('organizer')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label required">Description</label>
                            <textarea name="description" rows="6" class="form-control @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="modern-card mb-4">
                    <div class="modern-card-head">
                        <h5><i class="fas fa-calendar-day me-2"></i>Schedule</h5>
                    </div>
                    <div class="modern-card-body row g-3">
                        <div class="col-md-6">
                            <label class="form-label required">Start Date & Time</label>
                            <input type="datetime-local" id="start_date" name="start_date" value="{{ old('start_date') }}" class="form-control @error('start_date') is-invalid @enderror" required>
                            @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">End Date & Time</label>
                            <input type="datetime-local" id="end_date" name="end_date" value="{{ old('end_date') }}" class="form-control @error('end_date') is-invalid @enderror" required>
                            @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Maximum Attendees</label>
                            <input type="number" min="1" name="max_attendees" value="{{ old('max_attendees') }}" class="form-control @error('max_attendees') is-invalid @enderror">
                            @error('max_attendees')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="modern-card mb-4">
                    <div class="modern-card-head">
                        <h5><i class="fas fa-location-dot me-2"></i>Location</h5>
                    </div>
                    <div class="modern-card-body row g-3">
                        <div class="col-md-4">
                            <label class="form-label required">Campus</label>
                            <select id="campus_id" name="campus_id" class="form-select @error('campus_id') is-invalid @enderror" required>
                                <option value="">Select campus</option>
                                @foreach($campuses as $campus)
                                    <option value="{{ $campus->id }}" {{ old('campus_id') == $campus->id ? 'selected' : '' }}>{{ $campus->name }}</option>
                                @endforeach
                            </select>
                            @error('campus_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Building</label>
                            <select id="building_id" name="building_id" class="form-select @error('building_id') is-invalid @enderror">
                                <option value="">Select building</option>
                            </select>
                            @error('building_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Venue</label>
                            <select id="venue_id" name="venue_id" class="form-select @error('venue_id') is-invalid @enderror">
                                <option value="">Select venue</option>
                            </select>
                            @error('venue_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Additional Venue Info</label>
                            <textarea name="additional_venue_info[notes]" rows="3" class="form-control @error('additional_venue_info') is-invalid @enderror">{{ old('additional_venue_info.notes') }}</textarea>
                            @error('additional_venue_info')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="modern-card mb-4">
                    <div class="modern-card-head">
                        <h5><i class="fas fa-image me-2"></i>Event Image</h5>
                    </div>
                    <div class="modern-card-body">
                        <label id="imageDropzone" class="upload-zone">
                            <input type="file" id="image" name="image" accept="image/*" class="d-none @error('image') is-invalid @enderror">
                            <div id="imageUploadState">
                                <i class="fas fa-cloud-arrow-up mb-2"></i>
                                <p class="mb-0">Click or drag image here</p>
                            </div>
                            <div id="imagePreviewState" class="d-none">
                                <img id="imagePreview" alt="Event image preview">
                                <button type="button" class="btn btn-sm btn-danger mt-2" id="removeImageBtn">Remove</button>
                            </div>
                        </label>
                        @error('image')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="modern-card mb-4">
                    <div class="modern-card-head">
                        <h5><i class="fas fa-sliders me-2"></i>Visibility & Registration</h5>
                    </div>
                    <div class="modern-card-body">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="is_public" name="is_public" value="1" {{ old('is_public', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_public">Public Event</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">Featured Event</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="requires_registration" name="requires_registration" value="1" {{ old('requires_registration') ? 'checked' : '' }}>
                            <label class="form-check-label" for="requires_registration">Requires Registration</label>
                        </div>
                        <div id="registrationLinkWrapper" class="{{ old('requires_registration') ? '' : 'd-none' }}">
                            <label class="form-label">Registration Link</label>
                            <input type="url" id="registration_link" name="registration_link" value="{{ old('registration_link') }}" class="form-control @error('registration_link') is-invalid @enderror">
                            @error('registration_link')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="modern-card mb-4">
                    <div class="modern-card-head">
                        <h5><i class="fas fa-address-book me-2"></i>Contact & Tags</h5>
                    </div>
                    <div class="modern-card-body">
                        <div class="mb-3">
                            <label class="form-label">Contact Email</label>
                            <input type="email" name="contact_email" value="{{ old('contact_email', auth()->user()->email) }}" class="form-control @error('contact_email') is-invalid @enderror">
                            @error('contact_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contact Phone</label>
                            <input type="text" name="contact_phone" value="{{ old('contact_phone') }}" class="form-control @error('contact_phone') is-invalid @enderror">
                            @error('contact_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="form-label">Tags</label>
                            <input type="text" name="tags" value="{{ old('tags') }}" class="form-control @error('tags') is-invalid @enderror" placeholder="workshop, seminar, technology">
                            @error('tags')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="modern-card">
                    <div class="modern-card-body d-grid gap-2">
                        <button type="submit" id="submitBtn" class="btn btn-primary btn-lg">
                            <i class="fas fa-paper-plane me-1"></i>
                            {{ $isEventManagerCreator ? 'Send For Approval' : 'Create Event' }}
                        </button>
                        <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .modern-event-create .modern-card {
        border: 1px solid #e4ebf7;
        border-radius: 16px;
        box-shadow: 0 8px 25px rgba(12, 43, 99, .07);
        overflow: hidden;
        background: #fff;
    }
    .modern-event-create .modern-card-head {
        padding: 1rem 1.1rem;
        background: linear-gradient(135deg, #f8fbff 0%, #eef5ff 100%);
        border-bottom: 1px solid #e4ebf7;
    }
    .modern-event-create .modern-card-head h5 {
        margin: 0;
        color: #0a2a66;
        font-size: 1rem;
        font-weight: 700;
    }
    .modern-event-create .modern-card-body {
        padding: 1.1rem;
    }
    .modern-event-create .required::after {
        content: ' *';
        color: #dc3545;
    }
    .modern-event-create .upload-zone {
        width: 100%;
        min-height: 220px;
        border: 2px dashed #c7d7f2;
        border-radius: 12px;
        background: #f8fbff;
        padding: 1rem;
        text-align: center;
        cursor: pointer;
    }
    .modern-event-create .upload-zone.dragover {
        border-color: #0a2a66;
        background: #edf4ff;
    }
    .modern-event-create #imagePreview {
        width: 100%;
        max-height: 220px;
        border-radius: 10px;
        object-fit: cover;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    const minDate = now.toISOString().slice(0, 16);
    startDateInput.min = minDate;
    endDateInput.min = minDate;

    startDateInput.addEventListener('change', function () {
        endDateInput.min = this.value || minDate;
        if (endDateInput.value && this.value && endDateInput.value < this.value) {
            endDateInput.value = '';
        }
    });

    const requiresRegistration = document.getElementById('requires_registration');
    const registrationLinkWrapper = document.getElementById('registrationLinkWrapper');
    const registrationLinkInput = document.getElementById('registration_link');
    function toggleRegistrationLink() {
        const show = requiresRegistration.checked;
        registrationLinkWrapper.classList.toggle('d-none', !show);
        if (!show) registrationLinkInput.value = '';
    }
    requiresRegistration.addEventListener('change', toggleRegistrationLink);
    toggleRegistrationLink();

    const campusSelect = document.getElementById('campus_id');
    const buildingSelect = document.getElementById('building_id');
    const venueSelect = document.getElementById('venue_id');
    const oldCampusId = @json(old('campus_id'));
    const oldBuildingId = @json(old('building_id'));
    const oldVenueId = @json(old('venue_id'));
    const buildingsEndpointTemplate = @json(route('events.get-buildings', ['campusId' => '__CAMPUS__']));
    const venuesEndpointTemplate = @json(route('events.get-venues', ['buildingId' => '__BUILDING__']));

    function fillSelect(selectEl, placeholder, rows, labelFn, selectedId = null) {
        selectEl.innerHTML = `<option value="">${placeholder}</option>`;
        rows.forEach((row) => {
            const option = document.createElement('option');
            option.value = row.id;
            option.textContent = labelFn(row);
            if (selectedId && String(selectedId) === String(row.id)) option.selected = true;
            selectEl.appendChild(option);
        });
    }

    async function loadBuildings(campusId, selectedBuildingId = null, selectedVenueId = null) {
        fillSelect(buildingSelect, 'Loading buildings...', [], () => '');
        fillSelect(venueSelect, 'Select venue', [], () => '');
        if (!campusId) {
            fillSelect(buildingSelect, 'Select building', [], () => '');
            return;
        }
        try {
            const response = await fetch(buildingsEndpointTemplate.replace('__CAMPUS__', campusId));
            if (!response.ok) throw new Error();
            const rows = await response.json();
            fillSelect(buildingSelect, rows.length ? 'Select building' : 'No buildings available', rows, row => `${row.name}${row.code ? ' (' + row.code + ')' : ''}`, selectedBuildingId);
            if (selectedBuildingId) {
                await loadVenues(selectedBuildingId, selectedVenueId);
            }
        } catch (e) {
            fillSelect(buildingSelect, 'Unable to load buildings', [], () => '');
        }
    }

    async function loadVenues(buildingId, selectedVenueId = null) {
        fillSelect(venueSelect, 'Loading venues...', [], () => '');
        if (!buildingId) {
            fillSelect(venueSelect, 'Select venue', [], () => '');
            return;
        }
        try {
            const response = await fetch(venuesEndpointTemplate.replace('__BUILDING__', buildingId));
            if (!response.ok) throw new Error();
            const rows = await response.json();
            fillSelect(venueSelect, rows.length ? 'Select venue' : 'No venues available', rows, row => `${row.name}${row.type ? ' - ' + row.type : ''}${row.capacity ? ' (Cap: ' + row.capacity + ')' : ''}`, selectedVenueId);
        } catch (e) {
            fillSelect(venueSelect, 'Unable to load venues', [], () => '');
        }
    }

    campusSelect.addEventListener('change', () => loadBuildings(campusSelect.value));
    buildingSelect.addEventListener('change', () => loadVenues(buildingSelect.value));
    if (oldCampusId) {
        campusSelect.value = oldCampusId;
        loadBuildings(oldCampusId, oldBuildingId, oldVenueId);
    }

    const imageInput = document.getElementById('image');
    const imageDropzone = document.getElementById('imageDropzone');
    const imageUploadState = document.getElementById('imageUploadState');
    const imagePreviewState = document.getElementById('imagePreviewState');
    const imagePreview = document.getElementById('imagePreview');
    const removeImageBtn = document.getElementById('removeImageBtn');

    function renderPreview(file) {
        if (!file || !file.type.startsWith('image/')) return;
        const reader = new FileReader();
        reader.onload = function (event) {
            imagePreview.src = event.target.result;
            imageUploadState.classList.add('d-none');
            imagePreviewState.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }

    imageInput.addEventListener('change', function () {
        renderPreview(this.files[0]);
    });

    removeImageBtn.addEventListener('click', function (event) {
        event.preventDefault();
        imageInput.value = '';
        imagePreview.src = '';
        imagePreviewState.classList.add('d-none');
        imageUploadState.classList.remove('d-none');
    });

    ['dragenter', 'dragover'].forEach((name) => {
        imageDropzone.addEventListener(name, function (event) {
            event.preventDefault();
            imageDropzone.classList.add('dragover');
        });
    });

    ['dragleave', 'drop'].forEach((name) => {
        imageDropzone.addEventListener(name, function (event) {
            event.preventDefault();
            imageDropzone.classList.remove('dragover');
        });
    });

    imageDropzone.addEventListener('drop', function (event) {
        const files = event.dataTransfer.files;
        if (files && files.length) {
            try { imageInput.files = files; } catch (e) {}
            renderPreview(files[0]);
        }
    });

    const form = document.getElementById('eventCreateForm');
    const submitBtn = document.getElementById('submitBtn');
    form.addEventListener('submit', function (event) {
        if (startDateInput.value && endDateInput.value && new Date(endDateInput.value) <= new Date(startDateInput.value)) {
            event.preventDefault();
            alert('End date must be after start date.');
            return;
        }
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class=\"fas fa-spinner fa-spin me-1\"></i> Processing...';
    });
});
</script>
@endpush
