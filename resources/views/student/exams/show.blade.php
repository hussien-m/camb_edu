@extends('student.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">{{ $exam->title }}</h4>
                </div>
                <div class="card-body">
                    @if($exam->description)
                        <p class="lead">{{ $exam->description }}</p>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="text-center p-3 border rounded">
                                <i class="fas fa-clock fa-2x text-primary mb-2"></i>
                                <h6>Duration</h6>
                                <p class="mb-0">{{ $exam->duration }} minutes</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 border rounded">
                                <i class="fas fa-question-circle fa-2x text-info mb-2"></i>
                                <h6>Questions</h6>
                                <p class="mb-0">{{ $exam->questions->count() }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 border rounded">
                                <i class="fas fa-percentage fa-2x text-success mb-2"></i>
                                <h6>Passing Score</h6>
                                <p class="mb-0">{{ $exam->passing_score }}%</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 border rounded">
                                <i class="fas fa-redo fa-2x text-warning mb-2"></i>
                                <h6>Attempts Left</h6>
                                <p class="mb-0">{{ $exam->max_attempts - $attemptCount }}</p>
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

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Important Instructions:</strong>
                        <ul class="mb-0 mt-2">
                            <li>You will have {{ $exam->duration }} minutes to complete this exam</li>
                            <li>Your answers will be automatically saved as you progress</li>
                            <li>You can navigate between questions using Previous/Next buttons</li>
                            <li>Make sure to submit your exam before time runs out</li>
                            <li>Once submitted, you cannot change your answers</li>
                        </ul>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('student.courses.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Courses
                        </a>
                        <form action="{{ route('student.exams.start', $exam) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-play me-2"></i>Start Exam Now
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
