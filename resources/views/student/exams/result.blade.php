@extends('student.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header {{ $attempt->passed ? 'bg-success' : 'bg-warning' }} text-white">
                    <h3 class="mb-0">Exam Results</h3>
                </div>
                <div class="card-body">
                    <div class="row text-center mb-4">
                        <div class="col-md-3">
                            <div class="p-4 border rounded">
                                <i class="fas fa-star fa-3x {{ $attempt->passed ? 'text-success' : 'text-warning' }} mb-3"></i>
                                <h2>{{ $attempt->percentage }}%</h2>
                                <p class="text-muted mb-0">Your Score</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-4 border rounded">
                                <i class="fas fa-chart-line fa-3x text-primary mb-3"></i>
                                <h2>{{ $attempt->score }}/{{ $attempt->exam->total_marks }}</h2>
                                <p class="text-muted mb-0">Points Earned</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-4 border rounded">
                                <i class="fas fa-clipboard-check fa-3x text-info mb-3"></i>
                                <h2>{{ $correctAnswers }}/{{ $totalQuestions }}</h2>
                                <p class="text-muted mb-0">Correct Answers</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-4 border rounded">
                                <i class="fas fa-{{ $attempt->passed ? 'check-circle' : 'times-circle' }} fa-3x {{ $attempt->passed ? 'text-success' : 'text-danger' }} mb-3"></i>
                                <h2>{{ $attempt->passed ? 'PASSED' : 'FAILED' }}</h2>
                                <p class="text-muted mb-0">Result</p>
                            </div>
                        </div>
                    </div>

                    @if($attempt->passed)
                        <div class="alert alert-success">
                            <i class="fas fa-trophy me-2"></i>
                            <strong>Congratulations!</strong> You have successfully passed the exam.
                            @if($attempt->certificate)
                                <a href="{{ route('student.certificates.show', $attempt->certificate) }}" class="alert-link">View your certificate</a>
                            @endif
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Keep trying!</strong> You need {{ $attempt->exam->passing_score }}% to pass.
                            @php
                                $remainingAttempts = $attempt->exam->max_attempts - $attempt->attempt_number;
                            @endphp
                            @if($remainingAttempts > 0)
                                You have {{ $remainingAttempts }} attempt(s) remaining.
                            @else
                                You have used all your attempts.
                            @endif
                        </div>
                    @endif

                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">Question Breakdown</h5>
                        </div>
                        <div class="card-body">
                            @foreach($attempt->exam->questions as $index => $question)
                                @php
                                    $studentAnswer = $attempt->answers->where('question_id', $question->id)->first();
                                    $correctOption = $question->options->where('is_correct', true)->first();
                                @endphp
                                <div class="card mb-3 {{ $studentAnswer && $studentAnswer->is_correct ? 'border-success' : 'border-danger' }}">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <span>
                                            <strong>Question {{ $index + 1 }}</strong>
                                            @if($studentAnswer && $studentAnswer->is_correct)
                                                <i class="fas fa-check-circle text-success ms-2"></i>
                                                <span class="badge bg-success ms-2">+{{ $question->points }} points</span>
                                            @else
                                                <i class="fas fa-times-circle text-danger ms-2"></i>
                                                <span class="badge bg-danger ms-2">0 points</span>
                                            @endif
                                        </span>
                                        <span class="badge bg-secondary">{{ $question->points }} points</span>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-3"><strong>{{ $question->question_text }}</strong></p>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>Your Answer:</h6>
                                                @if($studentAnswer && $studentAnswer->selectedOption)
                                                    <div class="p-3 rounded {{ $studentAnswer->is_correct ? 'bg-success-subtle' : 'bg-danger-subtle' }}">
                                                        {{ $studentAnswer->selectedOption->option_text }}
                                                    </div>
                                                @else
                                                    <div class="p-3 rounded bg-light text-muted">
                                                        <em>No answer provided</em>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <h6>Correct Answer:</h6>
                                                <div class="p-3 rounded bg-success-subtle">
                                                    <i class="fas fa-check text-success me-2"></i>
                                                    {{ $correctOption->option_text }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('student.courses.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Courses
                        </a>
                        @if(!$attempt->passed && $remainingAttempts > 0)
                            <a href="{{ route('student.exams.show', $attempt->exam) }}" class="btn btn-primary">
                                <i class="fas fa-redo me-2"></i>Retake Exam
                            </a>
                        @endif
                        @if($attempt->certificate)
                            <a href="{{ route('student.certificates.show', $attempt->certificate) }}" class="btn btn-success">
                                <i class="fas fa-certificate me-2"></i>View Certificate
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
