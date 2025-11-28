@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Exam Details</h2>
        <div>
            <a href="{{ route('admin.exams.edit', $exam) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Edit Exam
            </a>
            <a href="{{ route('admin.exams.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Exams
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">{{ $exam->title }}</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Course:</strong> {{ $exam->course->title }}
                        </div>
                        <div class="col-md-6">
                            <strong>Status:</strong>
                            @if($exam->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </div>
                    </div>

                    @if($exam->description)
                        <div class="mb-3">
                            <strong>Description:</strong>
                            <p class="mt-2">{{ $exam->description }}</p>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-3">
                            <div class="border rounded p-3 text-center">
                                <i class="fas fa-clock fa-2x text-primary mb-2"></i>
                                <h6>Duration</h6>
                                <p class="mb-0">{{ $exam->duration }} min</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3 text-center">
                                <i class="fas fa-percentage fa-2x text-success mb-2"></i>
                                <h6>Passing Score</h6>
                                <p class="mb-0">{{ $exam->passing_score }}%</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3 text-center">
                                <i class="fas fa-redo fa-2x text-warning mb-2"></i>
                                <h6>Max Attempts</h6>
                                <p class="mb-0">{{ $exam->max_attempts }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3 text-center">
                                <i class="fas fa-star fa-2x text-info mb-2"></i>
                                <h6>Total Marks</h6>
                                <p class="mb-0">{{ $exam->total_marks }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Questions ({{ $exam->questions->count() }})</h5>
                    <a href="{{ route('admin.questions.create', $exam) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-2"></i>Add Question
                    </a>
                </div>
                <div class="card-body">
                    @forelse($exam->questions as $index => $question)
                        <div class="card mb-3">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span>
                                    <strong>Question {{ $index + 1 }}</strong>
                                    <span class="badge bg-secondary ms-2">{{ ucfirst(str_replace('_', ' ', $question->question_type)) }}</span>
                                    <span class="badge bg-info ms-2">{{ $question->points }} points</span>
                                </span>
                                <div>
                                    <a href="{{ route('admin.questions.edit', [$exam, $question]) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.questions.destroy', [$exam, $question]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this question?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="mb-3">{{ $question->question_text }}</p>
                                <div class="options">
                                    @foreach($question->options as $optIndex => $option)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" disabled {{ $option->is_correct ? 'checked' : '' }}>
                                            <label class="form-check-label {{ $option->is_correct ? 'text-success fw-bold' : '' }}">
                                                {{ chr(65 + $optIndex) }}. {{ $option->option_text }}
                                                @if($option->is_correct)
                                                    <i class="fas fa-check-circle text-success ms-2"></i>
                                                @endif
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No questions added yet. Click "Add Question" to create the first question.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Total Questions</span>
                            <strong>{{ $exam->questions->count() }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Total Attempts</span>
                            <strong>{{ $exam->attempts->count() }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Completed Attempts</span>
                            <strong>{{ $exam->attempts->where('status', 'completed')->count() }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Average Score</span>
                            <strong>
                                @php
                                    $avgScore = $exam->attempts->where('status', 'completed')->avg('percentage');
                                @endphp
                                {{ $avgScore ? number_format($avgScore, 1) : '0' }}%
                            </strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Pass Rate</span>
                            <strong>
                                @php
                                    $completedCount = $exam->attempts->where('status', 'completed')->count();
                                    $passedCount = $exam->attempts->where('passed', true)->count();
                                    $passRate = $completedCount > 0 ? ($passedCount / $completedCount * 100) : 0;
                                @endphp
                                {{ number_format($passRate, 1) }}%
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
