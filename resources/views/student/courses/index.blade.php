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
        box-shadow: 0 8px 30px rgba(30, 58, 138, 0.15);
    }
    .course-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        background: linear-gradient(135deg, #f0f9ff 0%, #e0e7ff 100%);
        border-radius: 15px;
        padding: 1.5rem;
        border: 2px solid rgba(102, 126, 234, 0.1);
    }
    .exam-card {
        background: white;
        border-radius: 16px;
        padding: 1.75rem;
        border: 2px solid #e5e7eb;
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .exam-card:hover {
        border-color: #667eea;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.15);
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
                <h2 class="mb-2 fw-bold" style="color: #1e3a8a;">
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
                                    $attemptCount = $examItem['attemptCount'];
                                    $inProgress = $examItem['inProgress'];
                                @endphp

                                <div class="exam-card">
                                    <div class="d-flex align-items-start justify-content-between mb-3">
                                        <div>
                                            <h6 class="mb-2 fw-bold" style="color: #1e3a8a;">
                                                <i class="fas fa-clipboard-check me-2" style="color: #667eea;"></i>{{ $exam->title }}
                                            </h6>
                                            <div class="d-flex gap-3">
                                                <small class="text-muted">
                                                    <i class="fas fa-clock me-1"></i>{{ $exam->duration }} min
                                                </small>
                                                <small class="text-muted">
                                                    <i class="fas fa-redo me-1"></i>{{ $attemptCount }}/{{ $exam->max_attempts }} Attempts
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    @if($lastAttempt)
                                        <div class="alert alert-{{ $lastAttempt->passed ? 'success' : 'warning' }} alert-sm mb-2 py-2">
                                            <small>
                                                Last Score: <strong>{{ number_format($lastAttempt->percentage, 1) }}%</strong>
                                                @if($lastAttempt->passed)
                                                    <i class="fas fa-check-circle ms-1"></i>Passed
                                                @else
                                                    <i class="fas fa-times-circle ms-1"></i>Failed
                                                @endif
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
                                        @elseif($attemptCount < $exam->max_attempts)
                                            <a href="{{ route('student.exams.show', $exam) }}"
                                               class="btn btn-lg fw-bold"
                                               style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px; padding: 0.875rem; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);">
                                                <i class="fas fa-clipboard-check me-2"></i>{{ $attemptCount > 0 ? 'Retake' : 'Start' }} Exam
                                            </a>
                                        @else
                                            <button class="btn btn-lg fw-bold" disabled
                                                    style="background: #9ca3af; color: white; border-radius: 12px; padding: 0.875rem; opacity: 0.6;">
                                                <i class="fas fa-ban me-2"></i>Max Attempts Reached
                                            </button>
                                        @endif

                                        @if($lastAttempt)
                                            <a href="{{ route('student.exams.result', $lastAttempt) }}"
                                               class="btn btn-lg fw-bold"
                                               style="background: white; color: #0ea5e9; border: 2px solid #0ea5e9; border-radius: 12px; padding: 0.875rem;">
                                                <i class="fas fa-chart-bar me-2"></i>View Last Result
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
