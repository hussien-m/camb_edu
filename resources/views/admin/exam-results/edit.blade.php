@extends('admin.layouts.app')

@section('title', 'Edit Exam Result')
@section('page-title', 'Edit Exam Result')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.exam-results.index') }}">Exam Results</a></li>
    <li class="breadcrumb-item active">Edit Attempt #{{ $attempt->id }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="fas fa-edit"></i> Edit Result</h5>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.exam-results.update', $attempt->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Score <span class="text-danger">*</span></label>
                                <input type="number"
                                       name="score"
                                       class="form-control @error('score') is-invalid @enderror"
                                       value="{{ old('score', $attempt->score) }}"
                                       step="0.01"
                                       min="0"
                                       max="{{ $attempt->exam->total_marks }}"
                                       required>
                                <small class="text-muted">Out of {{ $attempt->exam->total_marks }} marks</small>
                                @error('score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Percentage <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number"
                                           name="percentage"
                                           class="form-control @error('percentage') is-invalid @enderror"
                                           value="{{ old('percentage', $attempt->percentage) }}"
                                           step="0.01"
                                           min="0"
                                           max="100"
                                           required>
                                    <span class="input-group-text">%</span>
                                </div>
                                <small class="text-muted">Passing percentage required: {{ $attempt->exam->passing_percentage }}%</small>
                                @error('percentage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <div class="form-check">
                                <input class="form-check-input @error('passed') is-invalid @enderror"
                                       type="radio"
                                       name="passed"
                                       id="passed_yes"
                                       value="1"
                                       {{ old('passed', $attempt->passed) == 1 ? 'checked' : '' }}
                                       required>
                                <label class="form-check-label text-success" for="passed_yes">
                                    <i class="fas fa-check-circle"></i> Passed
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input @error('passed') is-invalid @enderror"
                                       type="radio"
                                       name="passed"
                                       id="passed_no"
                                       value="0"
                                       {{ old('passed', $attempt->passed) == 0 ? 'checked' : '' }}
                                       required>
                                <label class="form-check-label text-danger" for="passed_no">
                                    <i class="fas fa-times-circle"></i> Failed
                                </label>
                            </div>
                            @error('passed')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Admin Notes</label>
                            <textarea name="admin_notes"
                                      class="form-control @error('admin_notes') is-invalid @enderror"
                                      rows="4"
                                      placeholder="Add any notes here...">{{ old('admin_notes', $attempt->admin_notes) }}</textarea>
                            <small class="text-muted">These notes are for internal use only and won't be visible to the student</small>
                            @error('admin_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Note:</strong> If you change the status to "Passed" and the student doesn't have a certificate, one will be created automatically.
                            If you change the status to "Failed", the certificate will be deleted if it exists.
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                            <a href="{{ route('admin.exam-results.show', $attempt->id) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Current Info Card -->
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="fas fa-info-circle"></i> Current Information</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <th>Student:</th>
                            <td>{{ $attempt->student->name }}</td>
                        </tr>
                        <tr>
                            <th>Exam:</th>
                            <td>{{ $attempt->exam->title }}</td>
                        </tr>
                        <tr>
                            <th>Date:</th>
                            <td>{{ $attempt->created_at->format('Y-m-d h:i A') }}</td>
                        </tr>
                        <tr>
                            <th>Attempt:</th>
                            <td>#{{ $attempt->attempt_number }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Current Score Card -->
            <div class="card mb-3">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0"><i class="fas fa-chart-bar"></i> Current Score</h6>
                </div>
                <div class="card-body text-center">
                    <h2 class="{{ $attempt->passed ? 'text-success' : 'text-danger' }}">
                        {{ $attempt->score }} / {{ $attempt->exam->total_marks }}
                    </h2>
                    <p class="mb-2">
                        <strong class="{{ $attempt->passed ? 'text-success' : 'text-danger' }}">
                            {{ number_format($attempt->percentage, 2) }}%
                        </strong>
                    </p>
                    <div class="progress mb-2" style="height: 20px;">
                        <div class="progress-bar {{ $attempt->passed ? 'bg-success' : 'bg-danger' }}"
                             role="progressbar"
                             style="width: {{ $attempt->percentage }}%;">
                        </div>
                    </div>
                    @if($attempt->passed)
                        <span class="badge bg-success">
                            <i class="fas fa-check"></i> Passed
                        </span>
                    @else
                        <span class="badge bg-danger">
                            <i class="fas fa-times"></i> Failed
                        </span>
                    @endif
                </div>
            </div>

            <!-- Certificate Status Card -->
            <div class="card">
                <div class="card-header {{ $attempt->certificate ? 'bg-success' : 'bg-secondary' }} text-white">
                    <h6 class="mb-0"><i class="fas fa-certificate"></i> Certificate Status</h6>
                </div>
                <div class="card-body">
                    @if($attempt->certificate)
                        <div class="alert alert-success mb-0">
                            <i class="fas fa-check-circle"></i>
                            <strong>Issued</strong><br>
                            <small>Certificate Number: {{ $attempt->certificate->certificate_number }}</small>
                        </div>
                    @else
                        <div class="alert alert-secondary mb-0">
                            <i class="fas fa-minus-circle"></i>
                            <strong>Not Issued</strong><br>
                            <small>Certificate will be created when status is changed to "Passed"</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const scoreInput = document.querySelector('input[name="score"]');
    const percentageInput = document.querySelector('input[name="percentage"]');
    const totalMarks = {{ $attempt->exam->total_marks }};
    const passingPercentage = {{ $attempt->exam->passing_percentage }};

    // Auto-calculate percentage when score changes
    scoreInput.addEventListener('input', function() {
        const score = parseFloat(this.value) || 0;
        const percentage = (score / totalMarks) * 100;
        percentageInput.value = percentage.toFixed(2);

        // Auto-select pass/fail based on percentage
        if (percentage >= passingPercentage) {
            document.getElementById('passed_yes').checked = true;
        } else {
            document.getElementById('passed_no').checked = true;
        }
    });

    // Auto-calculate score when percentage changes
    percentageInput.addEventListener('input', function() {
        const percentage = parseFloat(this.value) || 0;
        const score = (percentage / 100) * totalMarks;
        scoreInput.value = score.toFixed(2);

        // Auto-select pass/fail based on percentage
        if (percentage >= passingPercentage) {
            document.getElementById('passed_yes').checked = true;
        } else {
            document.getElementById('passed_no').checked = true;
        }
    });
});
</script>
@endpush
