@extends('student.layouts.app')

@section('title', 'All Exams')
@section('page-title', 'All Exams')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h4 class="mb-2"><i class="fas fa-clipboard-list me-2"></i>All Exams</h4>
            <p class="text-muted mb-0">View all available and scheduled exams for your enrolled courses.</p>
        </div>
    </div>

    @forelse($exams as $item)
        @php
            $exam = $item['exam'];
            $access = $item['access'];
            $attempts = $item['attempts'];
            $inProgress = $item['in_progress'];
        @endphp
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div>
                        <h5 class="mb-1">{{ $exam->title }}</h5>
                        <div class="text-muted small">
                            <span><i class="fas fa-book me-1"></i>{{ $exam->course->title }}</span>
                            @if($exam->is_scheduled)
                                <span class="ms-3"><i class="fas fa-calendar-alt me-1"></i>{{ $item['schedule_status'] }}</span>
                            @else
                                <span class="ms-3"><i class="fas fa-clock me-1"></i>Available anytime</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-end">
                        @if($inProgress)
                            <a href="{{ route('student.exams.take', $inProgress) }}" class="btn btn-warning btn-sm">
                                Continue Exam
                            </a>
                        @elseif($access['allowed'] ?? false)
                            <form action="{{ route('student.exams.start', $exam) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm">Start Exam</button>
                            </form>
                        @else
                            <span class="badge bg-secondary">Not Available</span>
                        @endif
                    </div>
                </div>
                @if(!empty($access['message']))
                    <div class="text-muted small mt-2">{{ $access['message'] }}</div>
                @endif
                @if($attempts->count() > 0)
                    <div class="mt-2 small text-muted">
                        Attempts: {{ $attempts->count() }}
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="alert alert-info">No exams available for your enrolled courses.</div>
    @endforelse
</div>
@endsection
