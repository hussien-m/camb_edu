@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Edit Question</h2>
        <a href="{{ route('admin.exams.show', $exam) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Exam
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.questions.update', [$exam, $question]) }}" method="POST" id="questionForm">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="question_text" class="form-label">Question Text <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('question_text') is-invalid @enderror" id="question_text" name="question_text" rows="3" required>{{ old('question_text', $question->question_text) }}</textarea>
                    @error('question_text')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="question_type" class="form-label">Question Type <span class="text-danger">*</span></label>
                        <select class="form-select @error('question_type') is-invalid @enderror" id="question_type" name="question_type" required>
                            <option value="multiple_choice" {{ old('question_type', $question->question_type) == 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                            <option value="true_false" {{ old('question_type', $question->question_type) == 'true_false' ? 'selected' : '' }}>True/False</option>
                        </select>
                        @error('question_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="points" class="form-label">Points <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('points') is-invalid @enderror" id="points" name="points" value="{{ old('points', $question->points) }}" min="1" required>
                        @error('points')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Answer Options</h5>
                        <button type="button" class="btn btn-sm btn-primary" id="addOptionBtn">
                            <i class="fas fa-plus me-1"></i>Add Option
                        </button>
                    </div>
                    <div class="card-body" id="optionsContainer">
                        @php
                            $existingOptions = old('options', $question->options->toArray());
                            $correctOptionIndex = null;
                            foreach($existingOptions as $index => $option) {
                                if (isset($option['is_correct']) && $option['is_correct']) {
                                    $correctOptionIndex = $index;
                                    break;
                                }
                            }
                        @endphp
                        @foreach($existingOptions as $index => $option)
                            <div class="option-item mb-3" data-index="{{ $index }}">
                                <input type="hidden" name="options[{{ $index }}][id]" value="{{ $option['id'] ?? '' }}">
                                <div class="row align-items-center">
                                    <div class="col-md-1 text-center">
                                        <strong>{{ chr(65 + $index) }}</strong>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control @error('options.'.$index.'.text') is-invalid @enderror" name="options[{{ $index }}][text]" placeholder="Enter option text" value="{{ $option['option_text'] ?? $option['text'] ?? '' }}" required>
                                        @error('options.'.$index.'.text')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="correct_option" value="{{ $index }}" id="correct_{{ $index }}" {{ $correctOptionIndex == $index ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="correct_{{ $index }}">
                                                Correct
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-danger btn-sm remove-option" {{ $index < 2 ? 'disabled' : '' }}>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                @error('options')
                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                @enderror

                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle me-2"></i>
                    You must select at least one correct answer option. For True/False questions, add only two options (True and False).
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.exams.show', $exam) }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Question
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let optionIndex = {{ count($existingOptions) }};
    const container = document.getElementById('optionsContainer');
    const addBtn = document.getElementById('addOptionBtn');
    const questionType = document.getElementById('question_type');

    addBtn.addEventListener('click', function() {
        if (container.children.length >= 6) {
            alert('Maximum 6 options allowed');
            return;
        }

        const optionHtml = `
            <div class="option-item mb-3" data-index="${optionIndex}">
                <input type="hidden" name="options[${optionIndex}][id]" value="">
                <div class="row align-items-center">
                    <div class="col-md-1 text-center">
                        <strong>${String.fromCharCode(65 + optionIndex)}</strong>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="options[${optionIndex}][text]" placeholder="Enter option text" required>
                    </div>
                    <div class="col-md-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="correct_option" value="${optionIndex}" id="correct_${optionIndex}" required>
                            <label class="form-check-label" for="correct_${optionIndex}">
                                Correct
                            </label>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger btn-sm remove-option">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', optionHtml);
        optionIndex++;
        updateOptionLabels();
    });

    container.addEventListener('click', function(e) {
        if (e.target.closest('.remove-option')) {
            if (container.children.length <= 2) {
                alert('Minimum 2 options required');
                return;
            }
            e.target.closest('.option-item').remove();
            updateOptionLabels();
        }
    });

    questionType.addEventListener('change', function() {
        if (this.value === 'true_false') {
            // Limit to 2 options for true/false
            while (container.children.length > 2) {
                container.lastElementChild.remove();
            }
            addBtn.disabled = true;
        } else {
            addBtn.disabled = false;
        }
    });

    function updateOptionLabels() {
        const options = container.querySelectorAll('.option-item');
        options.forEach((option, index) => {
            option.querySelector('.col-md-1 strong').textContent = String.fromCharCode(65 + index);
        });
    }

    // Initial check for true/false
    if (questionType.value === 'true_false') {
        addBtn.disabled = true;
    }
});
</script>
@endpush
@endsection
