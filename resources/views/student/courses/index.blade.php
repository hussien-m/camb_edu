@extends('student.layouts.app')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">My Courses</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
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
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h4>{{ $course->title }}</h4>
                        @if($course->short_description)
                            <p class="text-muted">{{ Str::limit($course->short_description, 200) }}</p>
                        @elseif($course->description)
                            <p class="text-muted">{{ Str::limit(strip_tags($course->description), 200) }}</p>
                        @endif

                        <div class="mt-3">
                            @if($course->category)
                            <span class="badge bg-primary me-2">
                                <i class="fas fa-book me-1"></i>{{ $course->category->name }}
                            </span>
                            @endif
                            @if($course->duration)
                            <span class="badge bg-info me-2">
                                <i class="fas fa-clock me-1"></i>{{ $course->duration }}
                            </span>
                            @endif
                            @if($course->level)
                            <span class="badge bg-success">
                                <i class="fas fa-signal me-1"></i>{{ $course->level->name }}
                            </span>
                            @endif
                        </div>
                    </div>                    <div class="col-md-4 text-end">
                        <div class="mb-3">
                            <small class="text-muted">Progress</small>
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                                    {{ $progress }}%
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

                                <div class="exam-card mb-2 p-3 border rounded">
                                    <h6>{{ $exam->title }}</h6>
                                    <small class="text-muted d-block mb-2">
                                        <i class="fas fa-clock me-1"></i>{{ $exam->duration }} min
                                        <i class="fas fa-redo ms-2 me-1"></i>Attempts: {{ $attemptCount }}/{{ $exam->max_attempts }}
                                    </small>

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

                                    @if($inProgress)
                                        <a href="{{ route('student.exams.take', $inProgress) }}" class="btn btn-warning btn-sm w-100">
                                            <i class="fas fa-play me-1"></i>Continue Exam
                                        </a>
                                    @elseif($attemptCount < $exam->max_attempts)
                                        <a href="{{ route('student.exams.show', $exam) }}" class="btn btn-primary btn-sm w-100">
                                            <i class="fas fa-clipboard-check me-1"></i>{{ $attemptCount > 0 ? 'Retake' : 'Start' }} Exam
                                        </a>
                                    @else
                                        <button class="btn btn-secondary btn-sm w-100" disabled>
                                            <i class="fas fa-ban me-1"></i>Max Attempts Reached
                                        </button>
                                    @endif

                                    @if($lastAttempt)
                                        <a href="{{ route('student.exams.result', $lastAttempt) }}" class="btn btn-outline-info btn-sm w-100 mt-2">
                                            <i class="fas fa-chart-bar me-1"></i>View Last Result
                                        </a>
                                    @endif
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
