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
                <script>
                    setTimeout(function() {
                        document.getElementById('submitExamForm').submit();
                    }, 3000);
                </script>
            @endif

            <div class="row">
                <div class="col-md-9">
                    <div id="questionsContainer">
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Timer - use ISO format for JavaScript Date
    const startTime = new Date('{{ $attempt->start_time->toIso8601String() }}');
    const durationMinutes = {{ $attempt->exam->duration }};
    const endTime = startTime.getTime() + (durationMinutes * 60 * 1000);
    const timerElement = document.getElementById('timeRemaining');

    // Debug info
    console.log('Start Time:', startTime);
    console.log('Duration:', durationMinutes, 'minutes');
    console.log('End Time:', new Date(endTime));
    console.log('Current Time:', new Date());
    console.log('Time remaining (ms):', endTime - new Date().getTime());

    function updateTimer() {
        const now = new Date().getTime();
        const distance = endTime - now;

        if (distance < 0) {
            console.log('Time expired!');
            timerElement.innerHTML = "TIME'S UP!";
            timerElement.parentElement.classList.add('text-danger');
            clearInterval(timerInterval);
            document.getElementById('submitExamForm').submit();
            return;
        }

        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        timerElement.innerHTML = `${minutes}:${seconds.toString().padStart(2, '0')}`;

        if (minutes < 5) {
            timerElement.parentElement.classList.add('text-warning');
        }
    }

    updateTimer();
    const timerInterval = setInterval(updateTimer, 1000);

    // Question navigation
    let currentQuestion = 0;
    const questions = document.querySelectorAll('.question-card');
    const totalQuestions = questions.length;

    function showQuestion(index) {
        questions.forEach((q, i) => {
            q.style.display = i === index ? 'block' : 'none';
        });
        currentQuestion = index;

        // Update navigation buttons
        document.querySelectorAll('.prev-btn').forEach(btn => {
            btn.disabled = index === 0;
        });
    }

    // Navigation buttons
    document.querySelectorAll('.next-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (currentQuestion < totalQuestions - 1) {
                showQuestion(currentQuestion + 1);
            }
        });
    });

    document.querySelectorAll('.prev-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (currentQuestion > 0) {
                showQuestion(currentQuestion - 1);
            }
        });
    });

    // Question navigator buttons
    document.querySelectorAll('.question-nav-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const questionIndex = parseInt(this.getAttribute('data-question'));
            showQuestion(questionIndex);
        });
    });

    // Save answer via AJAX
    document.querySelectorAll('input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const questionId = this.getAttribute('data-question-id');
            const optionId = this.value;

            fetch('{{ route("student.exams.answer", $attempt) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    question_id: questionId,
                    option_id: optionId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update question navigator button
                    const navBtn = document.querySelector(`.question-nav-btn[data-question="${currentQuestion}"]`);
                    navBtn.classList.remove('btn-outline-secondary');
                    navBtn.classList.add('btn-success', 'text-white');

                    // Update counters
                    const answered = document.querySelectorAll('.question-nav-btn.btn-success').length;
                    document.getElementById('answeredCount').textContent = answered;
                    document.getElementById('unansweredCount').textContent = totalQuestions - answered;
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });

    // Submit exam confirmation
    document.getElementById('submitExamBtn').addEventListener('click', function() {
        const answered = document.querySelectorAll('.question-nav-btn.btn-success').length;
        const unanswered = totalQuestions - answered;

        let message = 'Are you sure you want to submit your exam?';
        if (unanswered > 0) {
            message += `\n\nYou have ${unanswered} unanswered question(s).`;
        }

        if (confirm(message)) {
            document.getElementById('submitExamForm').submit();
        }
    });
});
</script>
@endpush
@endsection
