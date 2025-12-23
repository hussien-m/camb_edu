@extends('student.layouts.app')

@section('title', 'Exam Details')
@section('page-title', 'Exam Details')

@push('styles')
<style>
    .exam-details-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    .exam-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2.5rem;
        text-align: center;
    }
    .exam-stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        text-align: center;
        border: 2px solid #e5e7eb;
        transition: all 0.3s ease;
    }
    .exam-stat-card:hover {
        border-color: #667eea;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.15);
        transform: translateY(-3px);
    }
    .exam-stat-card i {
        font-size: 2.5rem;
        margin-bottom: 1rem;
    }
    .start-exam-btn {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border: none;
        color: white;
        padding: 1.25rem 3rem;
        border-radius: 12px;
        font-size: 1.25rem;
        font-weight: 700;
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
        transition: all 0.3s ease;
    }
    .start-exam-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(16, 185, 129, 0.5);
        color: white;
    }
    .instructions-box {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border-radius: 16px;
        padding: 2rem;
        border: 2px solid rgba(245, 158, 11, 0.2);
    }
    .countdown-container {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border-radius: 20px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 40px rgba(239, 68, 68, 0.3);
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%, 100% { box-shadow: 0 10px 40px rgba(239, 68, 68, 0.3); }
        50% { box-shadow: 0 10px 50px rgba(239, 68, 68, 0.5); }
    }
    .countdown-title {
        color: white;
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        text-align: center;
    }
    .countdown-timer {
        display: flex;
        justify-content: center;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .countdown-item {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 1.5rem 2rem;
        min-width: 120px;
        text-align: center;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }
    .countdown-number {
        font-size: 3rem;
        font-weight: 900;
        color: white;
        line-height: 1;
        display: block;
        margin-bottom: 0.5rem;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }
    .countdown-label {
        font-size: 1rem;
        color: rgba(255, 255, 255, 0.9);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .exam-start-info {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border-radius: 15px;
        padding: 1.5rem;
        color: white;
        text-align: center;
        margin-top: 1rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="exam-details-card">
                <div class="exam-header">
                    <i class="fas fa-clipboard-list fa-3x mb-3"></i>
                    <h2 class="mb-2 fw-bold">{{ $exam->title }}</h2>
                    @if($exam->description)
                        <p class="mb-0 lead opacity-90">{{ $exam->description }}</p>
                    @endif
                </div>
                <div class="card-body p-4">
                    @if($exam->is_scheduled && $exam->scheduled_start_date && $exam->scheduled_start_date->isFuture())
                        <!-- Countdown Timer for Scheduled Exam -->
                        <div class="countdown-container">
                            <div class="countdown-title">
                                <i class="fas fa-clock fa-lg me-2"></i>
                                Exam Starts In:
                            </div>
                            <div class="countdown-timer" id="countdownTimer">
                                <div class="countdown-item">
                                    <span class="countdown-number" id="days">00</span>
                                    <span class="countdown-label">Days</span>
                                </div>
                                <div class="countdown-item">
                                    <span class="countdown-number" id="hours">00</span>
                                    <span class="countdown-label">Hours</span>
                                </div>
                                <div class="countdown-item">
                                    <span class="countdown-number" id="minutes">00</span>
                                    <span class="countdown-label">Minutes</span>
                                </div>
                                <div class="countdown-item">
                                    <span class="countdown-number" id="seconds">00</span>
                                    <span class="countdown-label">Seconds</span>
                                </div>
                            </div>
                            <div class="exam-start-info">
                                <i class="fas fa-calendar-check me-2"></i>
                                <strong>Start Time:</strong> {{ $exam->scheduled_start_date->format('l, F j, Y \a\t g:i A') }}
                                @if($exam->scheduled_end_date)
                                    <br>
                                    <i class="fas fa-calendar-times me-2"></i>
                                    <strong>End Time:</strong> {{ $exam->scheduled_end_date->format('l, F j, Y \a\t g:i A') }}
                                @endif
                            </div>
                        </div>

                        <!-- Alert Message -->
                        <div class="alert alert-warning text-center mb-4" role="alert">
                            <i class="fas fa-info-circle fa-lg me-2"></i>
                            <strong>Exam Not Available Yet!</strong> Please wait until the scheduled start time above.
                        </div>
                    @endif

                    <div class="row g-4 mb-5">
                        <div class="col-md-3">
                            <div class="exam-stat-card">
                                <i class="fas fa-clock text-primary"></i>
                                <h6 class="fw-bold mb-2">Duration</h6>
                                <h4 class="text-primary mb-0">{{ $exam->duration }} min</h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="exam-stat-card">
                                <i class="fas fa-question-circle text-info"></i>
                                <h6 class="fw-bold mb-2">Questions</h6>
                                <h4 class="text-info mb-0">{{ $exam->questions->count() }}</h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="exam-stat-card">
                                <i class="fas fa-percentage text-success"></i>
                                <h6 class="fw-bold mb-2">Passing Score</h6>
                                <h4 class="text-success mb-0">{{ $exam->passing_score }}%</h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="exam-stat-card">
                                <i class="fas fa-redo text-warning"></i>
                                <h6 class="fw-bold mb-2">Attempts Left</h6>
                                <h4 class="text-warning mb-0">{{ $exam->max_attempts - $attemptCount }}</h4>
                            </div>
                        </div>
                    </div>

                    @if($previousAttempts->isNotEmpty())
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Previous Attempts</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Attempt</th>
                                                <th>Date</th>
                                                <th>Score</th>
                                                <th>Result</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($previousAttempts as $attempt)
                                                <tr>
                                                    <td>#{{ $attempt->attempt_number }}</td>
                                                    <td>{{ $attempt->created_at->format('M d, Y H:i') }}</td>
                                                    <td>{{ $attempt->percentage }}%</td>
                                                    <td>
                                                        @if($attempt->passed)
                                                            <span class="badge bg-success">Passed</span>
                                                        @else
                                                            <span class="badge bg-danger">Failed</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('student.exams.result', $attempt) }}" class="btn btn-sm btn-info">
                                                            View
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="instructions-box mb-5">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-exclamation-circle fa-2x text-warning me-3"></i>
                            <h5 class="mb-0 fw-bold" style="color: #92400e;">Important Instructions</h5>
                        </div>
                        <ul class="mb-0" style="line-height: 2; color: #92400e;">
                            <li><strong>Time Limit:</strong> You will have {{ $exam->duration }} minutes to complete this exam</li>
                            <li><strong>Auto-Save:</strong> Your answers will be automatically saved as you progress</li>
                            <li><strong>Navigation:</strong> You can navigate between questions using Previous/Next buttons</li>
                            <li><strong>Submission:</strong> Make sure to submit your exam before time runs out</li>
                            <li><strong>Final:</strong> Once submitted, you cannot change your answers</li>
                        </ul>
                    </div>

                    <div class="text-center mb-4">
                        <form action="{{ route('student.exams.start', $exam) }}" method="POST" class="d-inline-block" id="startExamForm">
                            @csrf
                            <button type="submit" class="start-exam-btn" id="startExamBtn"
                                    @if($exam->is_scheduled && $exam->scheduled_start_date && $exam->scheduled_start_date->isFuture()) disabled @endif>
                                <i class="fas fa-play-circle me-2"></i>
                                @if($exam->is_scheduled && $exam->scheduled_start_date && $exam->scheduled_start_date->isFuture())
                                    Exam Not Started Yet
                                @else
                                    Start Exam Now
                                @endif
                            </button>
                        </form>
                    </div>

                    <div class="text-center">
                        <a href="{{ route('student.courses.index') }}" class="btn btn-outline-secondary btn-lg" style="border-radius: 12px; padding: 0.75rem 2rem;">
                            <i class="fas fa-arrow-left me-2"></i>Back to Courses
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
@if($exam->is_scheduled && $exam->scheduled_start_date && $exam->scheduled_start_date->isFuture())
    // Countdown Timer
    const examStartDate = new Date("{{ $exam->scheduled_start_date->format('Y-m-d H:i:s') }}").getTime();

    function updateCountdown() {
        const now = new Date().getTime();
        const distance = examStartDate - now;

        if (distance < 0) {
            // Exam has started - reload page
            location.reload();
            return;
        }

        // Calculate time units
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Update display
        document.getElementById('days').textContent = String(days).padStart(2, '0');
        document.getElementById('hours').textContent = String(hours).padStart(2, '0');
        document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
        document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');
    }

    // Update countdown immediately
    updateCountdown();

    // Update every second
    const countdownInterval = setInterval(updateCountdown, 1000);

    // Cleanup on page unload
    window.addEventListener('beforeunload', function() {
        clearInterval(countdownInterval);
    });
@endif
</script>
@endpush
