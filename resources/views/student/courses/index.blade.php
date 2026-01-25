@extends('student.layouts.app')

@section('title', 'My Courses')
@section('page-title', 'My Courses')

@push('styles')
<style>
    .course-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border: none;
        margin-bottom: 2rem;
    }
    .course-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(14, 107, 80, 0.2);
    }
    .course-header {
        background: linear-gradient(135deg, #0e6b50 0%, #1a9b74 100%);
        padding: 1.5rem;
        color: white;
    }
    .course-badge {
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.3);
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    .progress-card {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        border-radius: 15px;
        padding: 1.5rem;
        border: 2px solid rgba(14, 107, 80, 0.15);
    }
    .exam-card {
        background: white;
        border-radius: 16px;
        padding: 1.75rem;
        border: 2px solid #e5e7eb;
        transition: all 0.3s ease;
    }
    
    .exam-status-section {
        margin: 1rem 0;
    }
    
    .score-badge {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 12px 16px;
        border-left: 4px solid;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 8px;
    }
    
    .score-badge.score-best {
        border-left-color: #f59e0b;
        background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);
    }
    
    .score-badge.score-passed {
        border-left-color: #10b981;
        background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
    }
    
    .score-badge.score-failed {
        border-left-color: #ef4444;
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
    }
    
    .score-badge .score-value {
        font-size: 18px;
        font-weight: 700;
        color: #1e3a8a;
    }
    
    .score-badge.score-best .score-value {
        color: #d97706;
    }
    
    .score-badge.score-passed .score-value {
        color: #059669;
    }
    
    .score-badge.score-failed .score-value {
        color: #dc2626;
    }
    
    .status-badge {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .score-badge.score-passed .status-badge {
        background: #10b981;
        color: white;
    }
    
    .score-badge.score-failed .status-badge {
        background: #ef4444;
        color: white;
    }
    
    .alert-sm {
        font-size: 13px;
        padding: 10px 14px;
    }
    
    @media (max-width: 768px) {
        .score-badge {
            flex-direction: column;
            align-items: flex-start;
            gap: 6px;
        }
        
        .score-badge .score-value {
            font-size: 16px;
        }
    }
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .exam-card:hover {
        border-color: #0e6b50;
        box-shadow: 0 8px 25px rgba(14, 107, 80, 0.15);
        transform: translateY(-2px);
    }
    .page-header {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h2 class="mb-2 fw-bold" style="color: #0e6b50;">
                    <i class="fas fa-book-open me-3"></i>My Courses
                </h2>
                <p class="text-muted mb-0">Continue your learning journey</p>
            </div>
            <div class="text-end">
                <span class="badge bg-primary" style="font-size: 1rem; padding: 0.75rem 1.5rem;">
                    {{ count($courses) }} {{ Str::plural('Course', count($courses)) }}
                </span>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 15px; border: none; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2);">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @forelse($courses as $courseData)
        @php
            $course = $courseData['course'];
            $enrollment = $courseData['enrollment'];
            $progress = $courseData['progress'];
            $examsData = $courseData['examsData'];
            // Check enrollment-level first, then course-level
            $contentDisabled = ($enrollment && $enrollment->content_disabled !== null) 
                ? $enrollment->content_disabled 
                : ($course->content_disabled ?? false);
        @endphp
        <div class="course-card">
            <div class="course-header">
                <h4 class="mb-3 fw-bold">{{ $course->title }}</h4>
                <div class="d-flex gap-2 flex-wrap">
                    @if($course->category)
                    <span class="course-badge">
                        <i class="fas fa-book me-1"></i>{{ $course->category->name }}
                    </span>
                    @endif
                    @if($course->duration)
                    <span class="course-badge">
                        <i class="fas fa-clock me-1"></i>{{ $course->duration }}
                    </span>
                    @endif
                    @if($course->level)
                    <span class="course-badge">
                        <i class="fas fa-signal me-1"></i>{{ $course->level->name }}
                    </span>
                    @endif
                </div>
            </div>
            <div class="card-body p-4">
                @if($contentDisabled)
                    <div class="alert alert-warning border-warning mb-4" style="border-left: 4px solid #f59e0b;">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-lock fa-2x me-3 mt-1" style="color: #f59e0b;"></i>
                            <div class="flex-grow-1">
                                <h5 class="alert-heading mb-2">
                                    <i class="fas fa-exclamation-triangle me-2"></i>Course Content Disabled
                                </h5>
                                <p class="mb-3">
                                    The content for this course is currently disabled. You cannot view the course materials or take exams until you contact the administration.
                                </p>
                                <div class="d-flex align-items-center gap-3 flex-wrap">
                                    <a href="mailto:{{ setting('contact_email', 'info@example.com') }}?subject=Course Content Access Request - {{ $course->title }}&body=Hello,%0D%0A%0D%0AI am enrolled in the course '{{ $course->title }}' and would like to request access to the course content.%0D%0A%0D%0AThank you." 
                                       class="btn btn-primary btn-sm">
                                        <i class="fas fa-envelope me-2"></i>Contact Administration
                                    </a>
                                    <span class="text-muted small">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Email: <strong>{{ setting('contact_email', 'info@example.com') }}</strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-8">
                        @if($course->short_description)
                            <p class="text-muted mb-4">{{ Str::limit($course->short_description, 200) }}</p>
                        @elseif($course->description)
                            <p class="text-muted mb-4">{{ Str::limit(strip_tags($course->description), 200) }}</p>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <div class="progress-card">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <small class="fw-bold" style="color: #667eea;">
                                    <i class="fas fa-chart-line me-1"></i>Progress
                                </small>
                                <span class="fw-bold" style="color: #667eea; font-size: 1.1rem;">{{ $progress }}%</span>
                            </div>
                            <div class="progress" style="height: 12px; border-radius: 10px; background: rgba(255,255,255,0.7);">
                                <div class="progress-bar" role="progressbar"
                                     style="width: {{ $progress }}%; background: linear-gradient(90deg, #667eea 0%, #764ba2 100%); border-radius: 10px; box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);"
                                     aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>

                        @if(!empty($examsData) && count($examsData) > 0)
                            @foreach($examsData as $examItem)
                                @php
                                    $exam = $examItem['exam'];
                                    $attempts = $examItem['attempts'];
                                    $lastAttempt = $examItem['lastAttempt'];
                                    $bestAttempt = $examItem['bestAttempt'];
                                    $attemptCount = $examItem['attemptCount'];
                                    $inProgress = $examItem['inProgress'];
                                    $canStart = $examItem['canStart'];
                                    $hasCompletedAttempts = $examItem['hasCompletedAttempts'];
                                    $highestScore = $examItem['highestScore'];
                                    $lastScore = $examItem['lastScore'];
                                    $hasPassed = $examItem['hasPassed'];
                                    $examEnded = $examItem['examEnded'];
                                    $examNotStarted = $examItem['examNotStarted'];
                                    $maxAttemptsReached = $examItem['maxAttemptsReached'];
                                @endphp

                                <div class="exam-card">
                                    <div class="d-flex align-items-start justify-content-between mb-3">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-2 fw-bold" style="color: #1e3a8a;">
                                                <i class="fas fa-clipboard-check me-2" style="color: #667eea;"></i>{{ $exam->title }}
                                            </h6>
                                            <div class="d-flex gap-3 flex-wrap">
                                                <small class="text-muted">
                                                    <i class="fas fa-clock me-1"></i>{{ $exam->duration }} min
                                                </small>
                                                <small class="text-muted">
                                                    <i class="fas fa-redo me-1"></i>{{ $attemptCount }}/{{ $exam->max_attempts > 0 ? $exam->max_attempts : '∞' }} Attempts
                                                </small>
                                                @if($exam->passing_percentage)
                                                    <small class="text-muted">
                                                        <i class="fas fa-trophy me-1"></i>Pass: {{ $exam->passing_percentage }}%
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Exam Status & Scores -->
                                    <div class="exam-status-section mb-3">
                                        @if($hasCompletedAttempts)
                                            <div class="row g-2 mb-2">
                                                @if($bestAttempt && $bestAttempt->id !== ($lastAttempt->id ?? null))
                                                    <div class="col-6">
                                                        <div class="score-badge score-best">
                                                            <i class="fas fa-star me-1"></i>
                                                            <strong>Best Score:</strong>
                                                            <span class="score-value">{{ number_format($highestScore, 1) }}%</span>
                                                            @if($hasPassed)
                                                                <i class="fas fa-check-circle ms-1 text-success"></i>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                                @if($lastAttempt)
                                                    <div class="col-{{ $bestAttempt && $bestAttempt->id !== $lastAttempt->id ? '6' : '12' }}">
                                                        <div class="score-badge score-{{ $lastAttempt->passed ? 'passed' : 'failed' }}">
                                                            <i class="fas fa-{{ $lastAttempt->passed ? 'check' : 'times' }}-circle me-1"></i>
                                                            <strong>Last Score:</strong>
                                                            <span class="score-value">{{ number_format($lastScore, 1) }}%</span>
                                                            <span class="status-badge">{{ $lastAttempt->passed ? 'Passed' : 'Failed' }}</span>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <div class="alert alert-info alert-sm mb-0 py-2">
                                                <i class="fas fa-info-circle me-2"></i>
                                                <strong>Status:</strong> Not attempted yet
                                            </div>
                                        @endif
                                    </div>

                                    @if($examNotStarted)
                                        <!-- Countdown Timer -->
                                        <div class="alert alert-warning mb-3" style="border-radius: 12px; border-left: 4px solid #f59e0b;">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div>
                                                    <i class="fas fa-clock me-2"></i>
                                                    <strong>Scheduled Exam</strong>
                                                </div>
                                                <div class="countdown-display-course-{{ $exam->id }}" 
                                                     data-exam-start="{{ $exam->scheduled_start_date->toIso8601String() }}" 
                                                     data-exam-id="{{ $exam->id }}"
                                                     style="font-weight: 900; font-size: 1.1rem; color: #dc2626;">
                                                    <span class="days">00</span>d 
                                                    <span class="hours">00</span>h 
                                                    <span class="minutes">00</span>m 
                                                    <span class="seconds">00</span>s
                                                </div>
                                            </div>
                                            <small class="d-block mt-2 text-muted">
                                                <i class="fas fa-calendar-check me-1"></i>
                                                Starts: {{ $exam->scheduled_start_date->format('M d, Y g:i A') }}
                                            </small>
                                        </div>
                                    @elseif($examEnded)
                                        <div class="alert alert-danger mb-3" style="border-radius: 12px; border-left: 4px solid #e53e3e;">
                                            <i class="fas fa-calendar-times me-2"></i>
                                            <strong>Exam Ended</strong>
                                            <small class="d-block mt-1 text-muted">
                                                Ended: {{ $exam->scheduled_end_date->format('M d, Y g:i A') }}
                                            </small>
                                        </div>
                                    @endif

                                    <div class="d-grid gap-2 mt-3">
                                        @if($inProgress)
                                            <a href="{{ route('student.exams.take', $inProgress) }}"
                                               class="btn btn-lg fw-bold"
                                               style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; border-radius: 12px; padding: 0.875rem; box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);">
                                                <i class="fas fa-play me-2"></i>Continue Exam
                                            </a>
                                        @elseif($canStart && !$maxAttemptsReached && !$examEnded)
                                            @if($examNotStarted)
                                                <button class="btn btn-lg fw-bold exam-start-btn-{{ $exam->id }}" disabled
                                                        data-exam-id="{{ $exam->id }}"
                                                        data-exam-url="{{ route('student.exams.show', $exam) }}"
                                                        style="background: #9ca3af; color: white; border-radius: 12px; padding: 0.875rem; opacity: 0.6; cursor: not-allowed;">
                                                    <i class="fas fa-clock me-2"></i>Exam Not Started
                                                </button>
                                            @else
                                                <a href="{{ route('student.exams.show', $exam) }}"
                                                   class="btn btn-lg fw-bold"
                                                   style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px; padding: 0.875rem; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);">
                                                    <i class="fas fa-clipboard-check me-2"></i>{{ $hasCompletedAttempts ? 'Retake' : 'Start' }} Exam
                                                </a>
                                            @endif
                                        @else
                                            @if($maxAttemptsReached)
                                                <button class="btn btn-lg fw-bold" disabled
                                                        style="background: #9ca3af; color: white; border-radius: 12px; padding: 0.875rem; opacity: 0.6;">
                                                    <i class="fas fa-ban me-2"></i>Max Attempts Reached
                                                </button>
                                            @elseif($examEnded)
                                                <button class="btn btn-lg fw-bold" disabled
                                                        style="background: #9ca3af; color: white; border-radius: 12px; padding: 0.875rem; opacity: 0.6;">
                                                    <i class="fas fa-calendar-times me-2"></i>Exam Ended
                                                </button>
                                            @else
                                                <button class="btn btn-lg fw-bold" disabled
                                                        style="background: #9ca3af; color: white; border-radius: 12px; padding: 0.875rem; opacity: 0.6;">
                                                    <i class="fas fa-exclamation-triangle me-2"></i>Not Available
                                                </button>
                                            @endif
                                        @endif

                                        @if($lastAttempt)
                                            <a href="{{ route('student.exams.result', $lastAttempt) }}"
                                               class="btn btn-lg fw-bold"
                                               style="background: white; color: #0ea5e9; border: 2px solid #0ea5e9; border-radius: 12px; padding: 0.875rem;">
                                                <i class="fas fa-chart-bar me-2"></i>View Last Result
                                            </a>
                                        @endif
                                        
                                        @if($bestAttempt && $bestAttempt->id !== ($lastAttempt->id ?? null))
                                            <a href="{{ route('student.exams.result', $bestAttempt) }}"
                                               class="btn btn-lg fw-bold"
                                               style="background: white; color: #10b981; border: 2px solid #10b981; border-radius: 12px; padding: 0.875rem;">
                                                <i class="fas fa-trophy me-2"></i>View Best Result
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="alert alert-info">
                                <small><i class="fas fa-info-circle me-1"></i>No exams available yet</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-book-open fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No Enrolled Courses</h4>
                <p class="text-muted">You haven't enrolled in any courses yet. Browse courses on the main website to get started!</p>
                <a href="{{ route('home') }}" class="btn btn-primary">
                    <i class="fas fa-globe me-2"></i>Browse Courses
                </a>
            </div>
        </div>
    @endforelse
</div>
@endsection

@push('scripts')
<script>
// Countdown Timer for scheduled exams in course cards
function initCourseCountdowns() {
    const countdownDisplays = document.querySelectorAll('[class*="countdown-display-course-"]');
    
    countdownDisplays.forEach(display => {
        const examStart = new Date(display.dataset.examStart).getTime();
        const examId = display.dataset.examId;
        const startButton = document.querySelector(`.exam-start-btn-${examId}`);
        
        function updateCountdown() {
            const now = new Date().getTime();
            const distance = examStart - now;
            
            if (distance < 0) {
                // Exam has started - enable button and convert to link
                if (startButton) {
                    const examUrl = startButton.dataset.examUrl;
                    const newLink = document.createElement('a');
                    newLink.href = examUrl;
                    newLink.className = 'btn btn-lg fw-bold';
                    newLink.style = 'background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px; padding: 0.875rem; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);';
                    newLink.innerHTML = '<i class="fas fa-clipboard-check me-2"></i>Start Exam';
                    startButton.parentNode.replaceChild(newLink, startButton);
                }
                
                // Reload page to update status
                setTimeout(() => location.reload(), 1000);
                return;
            }
            
            // Calculate time units
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            // Update display
            display.querySelector('.days').textContent = String(days).padStart(2, '0');
            display.querySelector('.hours').textContent = String(hours).padStart(2, '0');
            display.querySelector('.minutes').textContent = String(minutes).padStart(2, '0');
            display.querySelector('.seconds').textContent = String(seconds).padStart(2, '0');
        }
        
        // Update immediately
        updateCountdown();
        
        // Update every second
        setInterval(updateCountdown, 1000);
    });
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', initCourseCountdowns);
</script>
@endpush
