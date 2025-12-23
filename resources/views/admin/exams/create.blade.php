@extends('admin.layouts.app')

@section('title', 'Create Exam')
@section('page-title', 'Create New Exam')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.exams.index') }}">Exams</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Create New Exam</h2>
        <a href="{{ route('admin.exams.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i>Back to Exams
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.exams.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="course_id" class="form-label">Course <span class="text-danger">*</span></label>
                        <select class="form-control @error('course_id') is-invalid @enderror" id="course_id" name="course_id" required>
                            <option value="">-- Select Course --</option>
                            @forelse($courses as $course)
                                <option value="{{ $course->id }}" 
                                    data-level="{{ $course->level_id ?? '' }}"
                                    {{ old('course_id', request('course_id')) == $course->id ? 'selected' : '' }}>
                                    {{ $course->title }} @if($course->level) - {{ $course->level->name }}@endif
                                </option>
                            @empty
                                <option value="" disabled>No active courses available</option>
                            @endforelse
                        </select>
                        @error('course_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if($courses->isEmpty())
                            <small class="text-muted">Please add courses first before creating exams.</small>
                        @endif
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="title" class="form-label">Exam Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="duration" class="form-label">Duration (minutes) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('duration') is-invalid @enderror" id="duration" name="duration" value="{{ old('duration', 60) }}" min="1" required>
                        @error('duration')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="passing_score" class="form-label">Passing Score (%) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('passing_score') is-invalid @enderror" id="passing_score" name="passing_score" value="{{ old('passing_score', 70) }}" min="0" max="100" required>
                        @error('passing_score')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="max_attempts" class="form-label">Max Attempts <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('max_attempts') is-invalid @enderror" id="max_attempts" name="max_attempts" value="{{ old('max_attempts', 3) }}" min="1" required>
                        @error('max_attempts')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="total_marks" class="form-label">Total Marks <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('total_marks') is-invalid @enderror" id="total_marks" name="total_marks" value="{{ old('total_marks', 100) }}" min="1" required>
                        @error('total_marks')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4">

                <!-- Scheduling Section -->
                <div class="card bg-light">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-alt mr-2"></i>Exam Scheduling (Optional)
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="is_scheduled" name="is_scheduled" value="1" {{ old('is_scheduled', '1') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_scheduled">
                                <strong>Schedule this exam</strong>
                                <small class="d-block text-muted">Enable to set specific start and end times for this exam</small>
                            </label>
                        </div>

                        @php
                            // Default times: Start = now + 1h 2min, End = now + 3 days
                            $defaultStart = now()->addHours(1)->addMinutes(2)->format('Y-m-d\TH:i');
                            $defaultEnd = now()->addDays(3)->format('Y-m-d\TH:i');
                        @endphp

                        <div id="schedulingFields" style="display: {{ old('is_scheduled', '1') ? 'block' : 'none' }};">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="scheduled_start_date" class="form-label">Start Date & Time <span class="text-danger">*</span></label>
                                    <input type="datetime-local" class="form-control @error('scheduled_start_date') is-invalid @enderror" 
                                           id="scheduled_start_date" name="scheduled_start_date" value="{{ old('scheduled_start_date', $defaultStart) }}">
                                    @error('scheduled_start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Default: 1 hour and 2 minutes from now</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="scheduled_end_date" class="form-label">End Date & Time</label>
                                    <input type="datetime-local" class="form-control @error('scheduled_end_date') is-invalid @enderror" 
                                           id="scheduled_end_date" name="scheduled_end_date" value="{{ old('scheduled_end_date', $defaultEnd) }}">
                                    @error('scheduled_end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Default: 3 days from now</small>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="timezone" class="form-label">Timezone</label>
                                <select class="form-control @error('timezone') is-invalid @enderror" id="timezone" name="timezone">
                                    <option value="UTC" {{ old('timezone', 'Asia/Dubai') == 'UTC' ? 'selected' : '' }}>UTC (Coordinated Universal Time)</option>
                                    <option value="Asia/Gaza" {{ old('timezone', 'Asia/Dubai') == 'Asia/Gaza' ? 'selected' : '' }}>Asia/Gaza (Palestine)</option>
                                    <option value="Asia/Jerusalem" {{ old('timezone', 'Asia/Dubai') == 'Asia/Jerusalem' ? 'selected' : '' }}>Asia/Jerusalem</option>
                                    <option value="Asia/Amman" {{ old('timezone', 'Asia/Dubai') == 'Asia/Amman' ? 'selected' : '' }}>Asia/Amman (Jordan)</option>
                                    <option value="Asia/Beirut" {{ old('timezone', 'Asia/Dubai') == 'Asia/Beirut' ? 'selected' : '' }}>Asia/Beirit (Lebanon)</option>
                                    <option value="Asia/Damascus" {{ old('timezone', 'Asia/Dubai') == 'Asia/Damascus' ? 'selected' : '' }}>Asia/Damascus (Syria)</option>
                                    <option value="Africa/Cairo" {{ old('timezone', 'Asia/Dubai') == 'Africa/Cairo' ? 'selected' : '' }}>Africa/Cairo (Egypt)</option>
                                    <option value="Asia/Riyadh" {{ old('timezone', 'Asia/Dubai') == 'Asia/Riyadh' ? 'selected' : '' }}>Asia/Riyadh (Saudi Arabia)</option>
                                    <option value="Asia/Dubai" {{ old('timezone', 'Asia/Dubai') == 'Asia/Dubai' ? 'selected' : '' }}>Asia/Dubai (UAE)</option>
                                </select>
                                @error('timezone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="scheduling_notes" class="form-label">Scheduling Notes</label>
                                <textarea class="form-control @error('scheduling_notes') is-invalid @enderror" 
                                          id="scheduling_notes" name="scheduling_notes" rows="2" 
                                          placeholder="Any special instructions or notes about the exam schedule...">{{ old('scheduling_notes') }}</textarea>
                                @error('scheduling_notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle mr-2"></i>
                                <strong>Automatic Reminders:</strong> Students will receive email reminders at:
                                <ul class="mb-0 mt-2">
                                    <li>24 hours before exam</li>
                                    <li>12 hours before exam</li>
                                    <li>6 hours before exam</li>
                                    <li>1.5 hours before exam</li>
                                    <li>10 minutes before exam</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('admin.exams.index') }}" class="btn btn-secondary mr-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i>Create Exam
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-select course if course_id is in URL
    const urlParams = new URLSearchParams(window.location.search);
    const courseId = urlParams.get('course_id');
    
    if (courseId) {
        $('#course_id').val(courseId);
        
        // Auto-fill exam title based on course name
        const selectedCourse = $('#course_id option:selected').text().split(' - ')[0];
        if (selectedCourse && !$('#title').val()) {
            $('#title').val(selectedCourse + ' - Final Exam');
        }
        
        // Highlight the form to show it's pre-filled
        $('#course_id').addClass('is-valid');
        
        // Show success message
        toastr.info('Course has been pre-selected from enrollment', 'Info', {
            timeOut: 3000,
            progressBar: true
        });
    }
    
    // Auto-fill title when course changes
    $('#course_id').on('change', function() {
        const selectedCourse = $(this).find('option:selected').text().split(' - ')[0];
        if (selectedCourse && selectedCourse !== '-- Select Course --' && !$('#title').val()) {
            $('#title').val(selectedCourse + ' - Final Exam');
        }
    });

    // Toggle scheduling fields
    $('#is_scheduled').on('change', function() {
        if ($(this).is(':checked')) {
            $('#schedulingFields').slideDown();
            $('#scheduled_start_date').attr('required', true);
        } else {
            $('#schedulingFields').slideUp();
            $('#scheduled_start_date').attr('required', false);
        }
    });
    
    // Since scheduling is enabled by default, make sure field is required
    if ($('#is_scheduled').is(':checked')) {
        $('#scheduled_start_date').attr('required', true);
    }

    // Set minimum date to now
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    const minDateTime = now.toISOString().slice(0, 16);
    $('#scheduled_start_date, #scheduled_end_date').attr('min', minDateTime);

    // Validate end date is after start date
    $('#scheduled_start_date, #scheduled_end_date').on('change', function() {
        const startDate = $('#scheduled_start_date').val();
        const endDate = $('#scheduled_end_date').val();
        
        if (startDate && endDate && endDate <= startDate) {
            toastr.error('End date must be after start date', 'Validation Error');
            $('#scheduled_end_date').val('');
        }
    });
});
</script>
@endpush
