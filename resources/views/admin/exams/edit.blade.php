@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Edit Exam</h2>
        <a href="{{ route('admin.exams.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Exams
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.exams.update', $exam) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="course_id" class="form-label">Course <span class="text-danger">*</span></label>
                        <select class="form-select @error('course_id') is-invalid @enderror" id="course_id" name="course_id" required>
                            <option value="">-- Select Course --</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ (old('course_id', $exam->course_id) == $course->id) ? 'selected' : '' }}>
                                    {{ $course->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('course_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="title" class="form-label">Exam Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $exam->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $exam->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="duration" class="form-label">Duration (minutes) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('duration') is-invalid @enderror" id="duration" name="duration" value="{{ old('duration', $exam->duration) }}" min="1" required>
                        @error('duration')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="passing_score" class="form-label">Passing Score (%) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('passing_score') is-invalid @enderror" id="passing_score" name="passing_score" value="{{ old('passing_score', $exam->passing_score) }}" min="0" max="100" required>
                        @error('passing_score')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="max_attempts" class="form-label">Max Attempts <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('max_attempts') is-invalid @enderror" id="max_attempts" name="max_attempts" value="{{ old('max_attempts', $exam->max_attempts) }}" min="1" required>
                        @error('max_attempts')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="total_marks" class="form-label">Total Marks <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('total_marks') is-invalid @enderror" id="total_marks" name="total_marks" value="{{ old('total_marks', $exam->total_marks) }}" min="1" required>
                        @error('total_marks')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                        <option value="active" {{ old('status', $exam->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $exam->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
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
                            <input class="form-check-input" type="checkbox" id="is_scheduled" name="is_scheduled" value="1" {{ old('is_scheduled', $exam->is_scheduled) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_scheduled">
                                <strong>Schedule this exam</strong>
                                <small class="d-block text-muted">Enable to set specific start and end times for this exam</small>
                            </label>
                        </div>

                        <div id="schedulingFields" style="display: {{ old('is_scheduled', $exam->is_scheduled) ? 'block' : 'none' }};">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="scheduled_start_date" class="form-label">Start Date & Time <span class="text-danger">*</span></label>
                                    <input type="datetime-local" class="form-control @error('scheduled_start_date') is-invalid @enderror" 
                                           id="scheduled_start_date" name="scheduled_start_date" 
                                           value="{{ old('scheduled_start_date', $exam->scheduled_start_date ? $exam->scheduled_start_date->format('Y-m-d\TH:i') : '') }}">
                                    @error('scheduled_start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">When students can start taking the exam</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="scheduled_end_date" class="form-label">End Date & Time</label>
                                    <input type="datetime-local" class="form-control @error('scheduled_end_date') is-invalid @enderror" 
                                           id="scheduled_end_date" name="scheduled_end_date" 
                                           value="{{ old('scheduled_end_date', $exam->scheduled_end_date ? $exam->scheduled_end_date->format('Y-m-d\TH:i') : '') }}">
                                    @error('scheduled_end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Optional: When the exam window closes</small>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="timezone" class="form-label">Timezone</label>
                                <select class="form-control @error('timezone') is-invalid @enderror" id="timezone" name="timezone">
                                    <option value="UTC" {{ old('timezone', $exam->timezone) == 'UTC' ? 'selected' : '' }}>UTC (Coordinated Universal Time)</option>
                                    <option value="Asia/Gaza" {{ old('timezone', $exam->timezone) == 'Asia/Gaza' ? 'selected' : '' }}>Asia/Gaza (Palestine)</option>
                                    <option value="Asia/Jerusalem" {{ old('timezone', $exam->timezone) == 'Asia/Jerusalem' ? 'selected' : '' }}>Asia/Jerusalem</option>
                                    <option value="Asia/Amman" {{ old('timezone', $exam->timezone) == 'Asia/Amman' ? 'selected' : '' }}>Asia/Amman (Jordan)</option>
                                    <option value="Asia/Beirut" {{ old('timezone', $exam->timezone) == 'Asia/Beirut' ? 'selected' : '' }}>Asia/Beirut (Lebanon)</option>
                                    <option value="Asia/Damascus" {{ old('timezone', $exam->timezone) == 'Asia/Damascus' ? 'selected' : '' }}>Asia/Damascus (Syria)</option>
                                    <option value="Africa/Cairo" {{ old('timezone', $exam->timezone) == 'Africa/Cairo' ? 'selected' : '' }}>Africa/Cairo (Egypt)</option>
                                    <option value="Asia/Riyadh" {{ old('timezone', $exam->timezone) == 'Asia/Riyadh' ? 'selected' : '' }}>Asia/Riyadh (Saudi Arabia)</option>
                                    <option value="Asia/Dubai" {{ old('timezone', $exam->timezone) == 'Asia/Dubai' ? 'selected' : '' }}>Asia/Dubai (UAE)</option>
                                </select>
                                @error('timezone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="scheduling_notes" class="form-label">Scheduling Notes</label>
                                <textarea class="form-control @error('scheduling_notes') is-invalid @enderror" 
                                          id="scheduling_notes" name="scheduling_notes" rows="2" 
                                          placeholder="Any special instructions or notes about the exam schedule...">{{ old('scheduling_notes', $exam->scheduling_notes) }}</textarea>
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

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.exams.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Exam
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

    // Set minimum date to now for new schedules
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    const minDateTime = now.toISOString().slice(0, 16);
    
    // Only set min for future dates
    const currentStart = $('#scheduled_start_date').val();
    if (!currentStart || new Date(currentStart) > now) {
        $('#scheduled_start_date').attr('min', minDateTime);
    }
    $('#scheduled_end_date').attr('min', minDateTime);

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
