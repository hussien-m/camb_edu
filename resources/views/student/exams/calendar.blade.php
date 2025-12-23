@extends('student.layouts.app')

@section('title', 'Exam Calendar')

@push('styles')
<style>
    .countdown-box {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border-radius: 10px;
        padding: 1rem;
        margin-top: 0.5rem;
        color: white;
        text-align: center;
        font-weight: 700;
        font-size: 1.1rem;
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
    }
    .countdown-number {
        font-size: 1.5rem;
        font-weight: 900;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-calendar-alt mr-2"></i>Scheduled Exams Calendar
                    </h4>
                </div>
                <div class="card-body">
                    @if($scheduledExams->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">No Scheduled Exams</h5>
                            <p class="text-muted">You don't have any scheduled exams at the moment.</p>
                            <a href="{{ route('student.courses.index') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-book mr-2"></i>View My Courses
                            </a>
                        </div>
                    @else
                        <div class="row">
                            @php
                                $now = now();
                                $upcomingExams = $scheduledExams->filter(function($exam) use ($now) {
                                    return $exam->scheduled_start_date->isFuture();
                                });
                                $availableExams = $scheduledExams->filter(function($exam) use ($now) {
                                    return $exam->isAvailable();
                                });
                                $pastExams = $scheduledExams->filter(function($exam) use ($now) {
                                    return $exam->scheduled_end_date && $exam->scheduled_end_date->isPast();
                                });
                            @endphp

                            <!-- Upcoming Exams -->
                            @if($upcomingExams->isNotEmpty())
                                <div class="col-12 mb-4">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-clock mr-2"></i>Upcoming Exams
                                    </h5>
                                    <div class="row">
                                        @foreach($upcomingExams as $exam)
                                            <div class="col-md-6 col-lg-4 mb-3">
                                                <div class="card border-primary h-100">
                                                    <div class="card-body">
                                                        <h6 class="card-title font-weight-bold">{{ $exam->title }}</h6>
                                                        <p class="card-text text-muted small mb-2">
                                                            <i class="fas fa-book mr-1"></i>{{ $exam->course->title }}
                                                        </p>
                                                        
                                                        <div class="mb-2">
                                                            <span class="badge badge-warning">
                                                                <i class="fas fa-calendar mr-1"></i>Scheduled
                                                            </span>
                                                        </div>

                                                        <div class="small mb-2">
                                                            <i class="fas fa-calendar-day text-primary mr-1"></i>
                                                            <strong>Start:</strong> {{ $exam->scheduled_start_date->format('M d, Y') }}
                                                        </div>
                                                        <div class="small mb-2">
                                                            <i class="fas fa-clock text-primary mr-1"></i>
                                                            <strong>Time:</strong> {{ $exam->scheduled_start_date->format('g:i A') }}
                                                        </div>
                                                        @if($exam->scheduled_end_date)
                                                            <div class="small mb-2">
                                                                <i class="fas fa-calendar-times text-danger mr-1"></i>
                                                                <strong>Closes:</strong> {{ $exam->scheduled_end_date->format('M d, Y g:i A') }}
                                                            </div>
                                                        @endif
                                                        <div class="small mb-3">
                                                            <i class="fas fa-hourglass-half text-info mr-1"></i>
                                                            <strong>Duration:</strong> {{ $exam->duration }} minutes
                                                        </div>

                                                        <div class="countdown-box" data-exam-start="{{ $exam->scheduled_start_date->toIso8601String() }}" data-exam-id="{{ $exam->id }}">
                                                            <div><i class="fas fa-clock mr-1"></i> Starts In:</div>
                                                            <div class="countdown-number countdown-display-{{ $exam->id }}">
                                                                <span class="days">00</span>d 
                                                                <span class="hours">00</span>h 
                                                                <span class="minutes">00</span>m 
                                                                <span class="seconds">00</span>s
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Available Now -->
                            @if($availableExams->isNotEmpty())
                                <div class="col-12 mb-4">
                                    <h5 class="text-success mb-3">
                                        <i class="fas fa-check-circle mr-2"></i>Available Now
                                    </h5>
                                    <div class="row">
                                        @foreach($availableExams as $exam)
                                            <div class="col-md-6 col-lg-4 mb-3">
                                                <div class="card border-success h-100">
                                                    <div class="card-body">
                                                        <h6 class="card-title font-weight-bold">{{ $exam->title }}</h6>
                                                        <p class="card-text text-muted small mb-2">
                                                            <i class="fas fa-book mr-1"></i>{{ $exam->course->title }}
                                                        </p>
                                                        
                                                        <div class="mb-2">
                                                            <span class="badge badge-success">
                                                                <i class="fas fa-check mr-1"></i>Available
                                                            </span>
                                                        </div>

                                                        <div class="small mb-2">
                                                            <i class="fas fa-calendar-check text-success mr-1"></i>
                                                            <strong>Started:</strong> {{ $exam->scheduled_start_date->format('M d, Y g:i A') }}
                                                        </div>
                                                        @if($exam->scheduled_end_date)
                                                            <div class="small mb-2">
                                                                <i class="fas fa-calendar-times text-danger mr-1"></i>
                                                                <strong>Closes:</strong> {{ $exam->scheduled_end_date->format('M d, Y g:i A') }}
                                                            </div>
                                                        @endif
                                                        <div class="small mb-2">
                                                            <i class="fas fa-hourglass-half text-info mr-1"></i>
                                                            <strong>Duration:</strong> {{ $exam->duration }} minutes
                                                        </div>
                                                        <div class="small mb-3">
                                                            <i class="fas fa-redo text-warning mr-1"></i>
                                                            <strong>Attempts:</strong> {{ $exam->attempts->count() }} / {{ $exam->max_attempts }}
                                                        </div>

                                                        <a href="{{ route('student.exams.show', $exam) }}" class="btn btn-success btn-block">
                                                            <i class="fas fa-play mr-1"></i>Take Exam
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Past Exams -->
                            @if($pastExams->isNotEmpty())
                                <div class="col-12">
                                    <h5 class="text-secondary mb-3">
                                        <i class="fas fa-history mr-2"></i>Past Exams
                                    </h5>
                                    <div class="row">
                                        @foreach($pastExams as $exam)
                                            <div class="col-md-6 col-lg-4 mb-3">
                                                <div class="card border-secondary h-100">
                                                    <div class="card-body">
                                                        <h6 class="card-title font-weight-bold text-muted">{{ $exam->title }}</h6>
                                                        <p class="card-text text-muted small mb-2">
                                                            <i class="fas fa-book mr-1"></i>{{ $exam->course->title }}
                                                        </p>
                                                        
                                                        <div class="mb-2">
                                                            <span class="badge badge-secondary">
                                                                <i class="fas fa-times mr-1"></i>Ended
                                                            </span>
                                                        </div>

                                                        <div class="small mb-2 text-muted">
                                                            <i class="fas fa-calendar-times mr-1"></i>
                                                            <strong>Ended:</strong> {{ $exam->scheduled_end_date->format('M d, Y g:i A') }}
                                                        </div>
                                                        <div class="small mb-3 text-muted">
                                                            <i class="fas fa-redo mr-1"></i>
                                                            <strong>Your Attempts:</strong> {{ $exam->attempts->count() }}
                                                        </div>

                                                        @if($exam->attempts->isNotEmpty())
                                                            @php
                                                                $lastAttempt = $exam->attempts->sortByDesc('created_at')->first();
                                                            @endphp
                                                            <a href="{{ route('student.exams.result', $lastAttempt) }}" class="btn btn-outline-secondary btn-block btn-sm">
                                                                <i class="fas fa-eye mr-1"></i>View Results
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Countdown Timer for all upcoming exams
function initCountdowns() {
    const countdownBoxes = document.querySelectorAll('.countdown-box');
    
    countdownBoxes.forEach(box => {
        const examStart = new Date(box.dataset.examStart).getTime();
        const examId = box.dataset.examId;
        const displayElement = box.querySelector(`.countdown-display-${examId}`);
        
        function updateCountdown() {
            const now = new Date().getTime();
            const distance = examStart - now;
            
            if (distance < 0) {
                // Exam has started - reload page to update status
                location.reload();
                return;
            }
            
            // Calculate time units
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            // Update display
            displayElement.querySelector('.days').textContent = String(days).padStart(2, '0');
            displayElement.querySelector('.hours').textContent = String(hours).padStart(2, '0');
            displayElement.querySelector('.minutes').textContent = String(minutes).padStart(2, '0');
            displayElement.querySelector('.seconds').textContent = String(seconds).padStart(2, '0');
        }
        
        // Update immediately
        updateCountdown();
        
        // Update every second
        setInterval(updateCountdown, 1000);
    });
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', initCountdowns);
</script>
@endpush

