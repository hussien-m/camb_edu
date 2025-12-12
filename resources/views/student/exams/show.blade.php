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
                        <form action="{{ route('student.exams.start', $exam) }}" method="POST" class="d-inline-block">
                            @csrf
                            <button type="submit" class="start-exam-btn">
                                <i class="fas fa-play-circle me-2"></i>Start Exam Now
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
