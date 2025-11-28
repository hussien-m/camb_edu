@extends('student.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">{{ $attempt->exam->title }}</h4>
                <div id="timer" class="fs-4">
                    <i class="fas fa-clock me-2"></i>
                    <span id="timeRemaining"></span>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($expired)
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    Time has expired! Your exam will be automatically submitted.
                </div>
            @endif

            <div class="row">
                <div class="col-md-9">
                    <div id="questionsContainer" data-attempt-id="{{ $attempt->id }}" data-duration="{{ $attempt->exam->duration }}" data-start-time="{{ $attempt->start_time->toIso8601String() }}">
                        @foreach($questions as $index => $question)
                            <div class="question-card {{ $index === 0 ? 'active' : '' }}" data-question="{{ $index }}" style="{{ $index === 0 ? '' : 'display:none;' }}">
                                <div class="d-flex justify-content-between mb-3">
                                    <h5>Question {{ $index + 1 }} of {{ $questions->count() }}</h5>
                                    <span class="badge bg-info">{{ $question->points }} points</span>
                                </div>

                                <div class="card mb-3">
                                    <div class="card-body">
                                        <p class="lead">{{ $question->question_text }}</p>

                                        <div class="options mt-4">
                                            @foreach($question->options as $option)
                                                <div class="form-check mb-3 p-3 border rounded option-item" data-option-id="{{ $option->id }}">
                                                    <input class="form-check-input"
                                                           type="radio"
                                                           name="question_{{ $question->id }}"
                                                           id="option_{{ $option->id }}"
                                                           value="{{ $option->id }}"
                                                           data-question-id="{{ $question->id }}"
                                                           {{ isset($answeredQuestions[$question->id]) && $answeredQuestions[$question->id] == $option->id ? 'checked' : '' }}>
                                                    <label class="form-check-label w-100 cursor-pointer" for="option_{{ $option->id }}">
                                                        {{ $option->option_text }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-secondary prev-btn" {{ $index === 0 ? 'disabled' : '' }}>
                                        <i class="fas fa-arrow-left me-2"></i>Previous
                                    </button>
                                    <button type="button" class="btn btn-primary next-btn" {{ $index === $questions->count() - 1 ? 'style=display:none;' : '' }}>
                                        Next<i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card sticky-top" style="top: 20px;">
                        <div class="card-header">
                            <h5 class="mb-0">Question Navigator</h5>
                        </div>
                        <div class="card-body">
                            <div class="question-nav-grid">
                                @foreach($questions as $index => $question)
                                    <button type="button"
                                            class="btn btn-outline-secondary btn-sm question-nav-btn m-1 {{ isset($answeredQuestions[$question->id]) ? 'btn-success text-white' : '' }}"
                                            data-question="{{ $index }}">
                                        {{ $index + 1 }}
                                    </button>
                                @endforeach
                            </div>

                            <div class="mt-3">
                                <small class="text-muted">
                                    <i class="fas fa-circle text-success me-1"></i>Answered: <strong id="answeredCount">{{ count($answeredQuestions) }}</strong>
                                </small><br>
                                <small class="text-muted">
                                    <i class="fas fa-circle text-secondary me-1"></i>Unanswered: <strong id="unansweredCount">{{ $questions->count() - count($answeredQuestions) }}</strong>
                                </small>
                            </div>

                            <form action="{{ route('student.exams.submit', $attempt) }}" method="POST" id="submitExamForm" class="mt-4">
                                @csrf
                                <button type="button" class="btn btn-success w-100" id="submitExamBtn">
                                    <i class="fas fa-check me-2"></i>Submit Exam
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    @vite('resources/js/exam-timer.js')
@endpush
@endsection
