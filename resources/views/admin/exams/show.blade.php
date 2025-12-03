@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Exam Details</h2>
        <div>
            <a href="{{ route('admin.exams.edit', $exam) }}" class="btn btn-warning">
                <i class="fas fa-edit mr-2"></i>Edit Exam
            </a>
            <a href="{{ route('admin.exams.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Back to Exams
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @php
        $currentPoints = $exam->questions->sum('points');
        $totalQuestions = $exam->questions->count();
        $isExamReady = ($totalQuestions > 0 && $currentPoints == $exam->total_marks);
    @endphp

    @if(!$isExamReady)
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <h5 class="alert-heading"><i class="fas fa-exclamation-triangle mr-2"></i>Exam Not Ready for Students!</h5>
            <hr>
            <p class="mb-2">This exam will <strong>NOT be visible</strong> to students until:</p>
            <ul class="mb-2">
                @if($totalQuestions === 0)
                    <li><strong>At least one question is added</strong> (Current: 0 questions)</li>
                @endif
                @if($currentPoints != $exam->total_marks)
                    <li><strong>Total points equal {{ $exam->total_marks }}</strong> (Current: {{ $currentPoints }} points)</li>
                @endif
            </ul>
            <p class="mb-0"><small><i class="fas fa-info-circle mr-1"></i>Students will see a message that the exam is not ready yet.</small></p>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @else
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i><strong>Exam is ready!</strong> Students can now take this exam.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
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
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-secondary">Inactive</span>
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
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="mb-0">Questions (<span id="questionsCount">{{ $exam->questions->count() }}</span>)</h5>
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addQuestionModal">
                            <i class="fas fa-plus mr-2"></i>Add Question Quick
                        </button>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted">Total Points: </span>
                            <span class="font-weight-bold" id="totalPoints">{{ $exam->questions->sum('points') }}</span>
                            <span class="text-muted"> / {{ $exam->total_marks }}</span>
                            @php
                                $currentPoints = $exam->questions->sum('points');
                                $targetPoints = $exam->total_marks;
                            @endphp
                            @if($currentPoints < $targetPoints)
                                <span class="badge badge-warning ml-2" id="pointsWarning">
                                    <i class="fas fa-exclamation-triangle"></i> {{ $targetPoints - $currentPoints }} points remaining
                                </span>
                            @elseif($currentPoints > $targetPoints)
                                <span class="badge badge-danger ml-2" id="pointsWarning">
                                    <i class="fas fa-times-circle"></i> {{ $currentPoints - $targetPoints }} points over limit
                                </span>
                            @else
                                <span class="badge badge-success ml-2" id="pointsWarning">
                                    <i class="fas fa-check-circle"></i> Perfect!
                                </span>
                            @endif
                        </div>
                        <div class="progress" style="width: 200px; height: 20px;">
                            @php
                                $percentage = $targetPoints > 0 ? min(($currentPoints / $targetPoints) * 100, 100) : 0;
                                $progressColor = $currentPoints == $targetPoints ? 'bg-success' : ($currentPoints > $targetPoints ? 'bg-danger' : 'bg-warning');
                            @endphp
                            <div class="progress-bar {{ $progressColor }}" role="progressbar" style="width: {{ $percentage }}%" id="pointsProgress">
                                {{ number_format($percentage, 0) }}%
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="questionsList">
                    @forelse($exam->questions as $index => $question)
                        <div class="card mb-3 question-card" id="question-{{ $question->id }}" data-question-id="{{ $question->id }}">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span>
                                    <strong class="question-number">Question {{ $index + 1 }}</strong>
                                    <span class="badge badge-secondary ml-2">{{ ucfirst(str_replace('_', ' ', $question->question_type)) }}</span>
                                    <span class="badge badge-info ml-2">{{ $question->points }} points</span>
                                </span>
                                <div>
                                    <button type="button" class="btn btn-sm btn-warning btn-edit-question"
                                            data-question-id="{{ $question->id }}"
                                            data-question-text="{{ $question->question_text }}"
                                            data-question-type="{{ $question->question_type }}"
                                            data-points="{{ $question->points }}"
                                            data-options='@json($question->options)'>
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger btn-delete-question" data-question-id="{{ $question->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="mb-3">{{ $question->question_text }}</p>
                                <div class="options">
                                    @foreach($question->options as $optIndex => $option)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" disabled {{ $option->is_correct ? 'checked' : '' }}>
                                            <label class="form-check-label {{ $option->is_correct ? 'text-success font-weight-bold' : '' }}">
                                                {{ chr(65 + $optIndex) }}. {{ $option->option_text }}
                                                @if($option->is_correct)
                                                    <i class="fas fa-check-circle text-success ml-2"></i>
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
                            <p class="text-muted">No questions added yet. Click "Add Question Quick" to create the first question.</p>
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

<!-- Edit Question Modal -->
<div class="modal fade" id="editQuestionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title"><i class="fas fa-edit mr-2"></i>Edit Question</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="editQuestionForm">
                @csrf
                <input type="hidden" id="edit_question_id" name="question_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_question_text">Question Text <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="edit_question_text" name="question_text" rows="3" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="edit_question_type">Question Type <span class="text-danger">*</span></label>
                        <select class="form-control" id="edit_question_type" name="question_type" required disabled>
                            <option value="multiple_choice">Multiple Choice</option>
                            <option value="true_false">True/False</option>
                        </select>
                        <small class="text-muted">Question type cannot be changed after creation</small>
                    </div>

                    <div class="form-group">
                        <label for="edit_points">Points <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="edit_points" name="points" value="1" min="1" required>
                    </div>

                    <div id="editOptionsContainer">
                        <label>Answer Options <span class="text-danger">*</span></label>
                        <div id="editOptionsList">
                            <!-- Options will be loaded here -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning" id="updateQuestionBtn">
                        <i class="fas fa-save mr-1"></i>Update Question
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Quick Add Question Modal -->
<div class="modal fade" id="addQuestionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-plus-circle mr-2"></i>Add Question Quick</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="quickAddQuestionForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="question_text">Question Text <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="question_text" name="question_text" rows="3" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="question_type">Question Type <span class="text-danger">*</span></label>
                        <select class="form-control" id="question_type" name="question_type" required>
                            <option value="multiple_choice">Multiple Choice</option>
                            <option value="true_false">True/False</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="points">Points <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="points" name="points" value="1" min="1" max="{{ $exam->total_marks }}" required>
                        <small class="text-muted">
                            <span id="modalPointsInfo">
                                Current: <span id="modalCurrentPoints">{{ $exam->questions->sum('points') }}</span> / {{ $exam->total_marks }}
                                | Remaining: <span id="modalRemainingPoints">{{ max($exam->total_marks - $exam->questions->sum('points'), 0) }}</span>
                            </span>
                        </small>
                        <div id="pointsWarningModal" class="text-warning mt-1" style="display: none;">
                            <i class="fas fa-exclamation-triangle"></i> <small>This will exceed total marks!</small>
                        </div>
                    </div>

                    <div id="optionsContainer">
                        <label>Answer Options <span class="text-danger">*</span></label>
                        <div id="optionsList">
                            <!-- Options will be added here dynamically -->
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="addOptionBtn">
                            <i class="fas fa-plus mr-1"></i>Add Option
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="saveQuestionBtn">
                        <i class="fas fa-save mr-1"></i>Save Question
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.option-item {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
    align-items: center;
}
.option-item input[type="text"] {
    flex: 1;
}
.option-item .btn-remove-option {
    flex-shrink: 0;
}
.correct-answer-indicator {
    color: #28a745;
    font-weight: bold;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    let optionCounter = 0;

    // Initialize options when modal opens
    $('#addQuestionModal').on('show.bs.modal', function() {
        // Reset everything
        optionCounter = 0;
        $('#optionsList').empty();
        $('#quickAddQuestionForm')[0].reset();

        // Reset button state
        const $btn = $('#saveQuestionBtn');
        $btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i>Save Question');

        // Show add option button
        $('#addOptionBtn').show();

        // Add initial options based on question type
        const questionType = $('#question_type').val();
        if (questionType === 'true_false') {
            addTrueFalseOptions();
            $('#addOptionBtn').hide();
        } else {
            addOption();
            addOption();
            addOption();
            addOption();
        }
    });

    // Reset modal when hidden
    $('#addQuestionModal').on('hidden.bs.modal', function() {
        $('#quickAddQuestionForm')[0].reset();
        $('#optionsList').empty();
        optionCounter = 0;

        // Reset button state
        const $btn = $('#saveQuestionBtn');
        $btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i>Save Question');
    });

    // Change options when question type changes
    $('#question_type').on('change', function() {
        const type = $(this).val();
        $('#optionsList').empty();
        optionCounter = 0;

        if (type === 'true_false') {
            addTrueFalseOptions();
            $('#addOptionBtn').hide();
        } else {
            addOption();
            addOption();
            addOption();
            addOption();
            $('#addOptionBtn').show();
        }
    });

    // Add option button click
    $('#addOptionBtn').on('click', function() {
        if (optionCounter < 6) {
            addOption();
        } else {
            toastr.warning('Maximum 6 options allowed');
        }
    });

    // Check points on input
    $('#points').on('input', function() {
        const newPoints = parseInt($(this).val()) || 0;
        const currentPoints = parseInt($('#modalCurrentPoints').text());
        const targetPoints = {{ $exam->total_marks }};
        const totalAfterAdd = currentPoints + newPoints;

        if (totalAfterAdd > targetPoints) {
            $('#pointsWarningModal').show();
            $(this).addClass('is-invalid');
        } else {
            $('#pointsWarningModal').hide();
            $(this).removeClass('is-invalid');
        }
    });

    // Add new option
    function addOption(text = '', isCorrect = false) {
        const optionHtml = `
            <div class="option-item" data-option="${optionCounter}">
                <div class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" id="correct_${optionCounter}" name="correct_option" value="${optionCounter}" ${isCorrect ? 'checked' : ''} required>
                    <label class="custom-control-label" for="correct_${optionCounter}">
                        <small>Correct</small>
                    </label>
                </div>
                <input type="text" class="form-control" name="options[${optionCounter}][option_text]" placeholder="Option ${String.fromCharCode(65 + optionCounter)}" value="${text}" required>
                <button type="button" class="btn btn-sm btn-danger btn-remove-option">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        $('#optionsList').append(optionHtml);
        optionCounter++;
    }

    // Add True/False options
    function addTrueFalseOptions() {
        addOption('True', true);
        addOption('False', false);
    }

    // Remove option
    $(document).on('click', '.btn-remove-option', function() {
        const questionType = $('#question_type').val();
        if (questionType === 'true_false') {
            toastr.warning('Cannot remove True/False options');
            return;
        }

        if ($('.option-item').length > 2) {
            $(this).closest('.option-item').remove();
        } else {
            toastr.warning('At least 2 options required');
        }
    });

    // Submit form via AJAX
    $('#quickAddQuestionForm').on('submit', function(e) {
        e.preventDefault();

        const examId = {{ $exam->id }};
        const newPoints = parseInt($('#points').val());
        const currentPoints = parseInt($('#modalCurrentPoints').text());
        const targetPoints = {{ $exam->total_marks }};
        const totalAfterAdd = currentPoints + newPoints;

        // Check if adding this question will exceed total marks
        if (totalAfterAdd > targetPoints) {
            const over = totalAfterAdd - targetPoints;
            if (!confirm(`Warning: Adding this question will exceed the total marks by ${over} points.\n\nCurrent: ${currentPoints}\nAdding: ${newPoints}\nTotal will be: ${totalAfterAdd} / ${targetPoints}\n\nDo you want to continue?`)) {
                return false;
            }
        }

        // Build data object
        const data = {
            _token: '{{ csrf_token() }}',
            question_text: $('#question_text').val(),
            question_type: $('#question_type').val(),
            points: $('#points').val()
        };

        // Build options array with correct format
        $('.option-item').each(function(index) {
            const optionText = $(this).find('input[type="text"]').val();
            const isCorrect = $(this).find('input[type="radio"]').is(':checked');

            // Send as Laravel expects: options[0][option_text], options[0][is_correct]
            data['options[' + index + '][option_text]'] = optionText;
            data['options[' + index + '][is_correct]'] = isCorrect ? 1 : 0;
        });

        // Log data for debugging
        console.log('Submitting question data:', data);

        // Disable submit button
        const $btn = $('#saveQuestionBtn');
        const originalText = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Saving...');

        $.ajax({
            url: '/admin/exams/' + examId + '/questions/quick-add',
            method: 'POST',
            data: data,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    toastr.success('Question added successfully!');

                    // Show warning if exists
                    if (response.warning) {
                        toastr.info(response.warning, 'Points Update', {
                            timeOut: 5000
                        });
                    }

                    // Update questions count
                    $('#questionsCount').text(response.total_questions);

                    // Update points display
                    updatePointsDisplay(response.total_points);

                    // Add question to list
                    addQuestionToList(response.question, response.question_index);

                    // Reset form
                    $('#quickAddQuestionForm')[0].reset();
                    $('#optionsList').empty();
                    optionCounter = 0;

                    // Close modal
                    $('#addQuestionModal').modal('hide');
                }
            },
            error: function(xhr) {
                console.error('AJAX Error:', xhr);
                console.error('Response:', xhr.responseJSON);

                if (xhr.responseJSON) {
                    if (xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;
                        Object.keys(errors).forEach(key => {
                            toastr.error(errors[key][0], 'Validation Error');
                        });
                    } else if (xhr.responseJSON.message) {
                        toastr.error(xhr.responseJSON.message, 'Error');
                    } else if (xhr.responseJSON.error) {
                        toastr.error(xhr.responseJSON.error, 'Error');
                    } else {
                        toastr.error('Failed to add question. Please check console for details.', 'Error');
                    }
                } else {
                    toastr.error('Server error. Please check console for details.', 'Error');
                }
            },
            complete: function() {
                $btn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Update points display
    function updatePointsDisplay(totalPoints) {
        const targetPoints = {{ $exam->total_marks }};
        const percentage = targetPoints > 0 ? Math.min((totalPoints / targetPoints) * 100, 100) : 0;
        const remaining = Math.max(targetPoints - totalPoints, 0);
        const over = Math.max(totalPoints - targetPoints, 0);

        $('#totalPoints').text(totalPoints);
        $('#pointsProgress').css('width', percentage + '%').text(Math.round(percentage) + '%');
        $('#modalCurrentPoints').text(totalPoints);

        if (remaining > 0) {
            $('#modalRemainingPoints').text(remaining);
        } else {
            $('#modalRemainingPoints').text(0);
        }

        // Update badge
        if (totalPoints < targetPoints) {
            $('#pointsWarning')
                .removeClass('badge-success badge-danger')
                .addClass('badge-warning')
                .html('<i class="fas fa-exclamation-triangle"></i> ' + (targetPoints - totalPoints) + ' points remaining');
            $('#pointsProgress').removeClass('bg-success bg-danger').addClass('bg-warning');
        } else if (totalPoints > targetPoints) {
            $('#pointsWarning')
                .removeClass('badge-success badge-warning')
                .addClass('badge-danger')
                .html('<i class="fas fa-times-circle"></i> ' + (totalPoints - targetPoints) + ' points over limit');
            $('#pointsProgress').removeClass('bg-success bg-warning').addClass('bg-danger');
        } else {
            $('#pointsWarning')
                .removeClass('badge-warning badge-danger')
                .addClass('badge-success')
                .html('<i class="fas fa-check-circle"></i> Perfect!');
            $('#pointsProgress').removeClass('bg-warning bg-danger').addClass('bg-success');
        }
        
        // Check exam readiness and update alert
        checkExamReadiness(totalPoints);
    }

    // Check exam readiness and update page alert
    function checkExamReadiness(totalPoints) {
        const targetPoints = {{ $exam->total_marks }};
        const questionsCount = parseInt($('#questionsCount').text()) || 0;
        const isReady = questionsCount > 0 && totalPoints === targetPoints;
        
        // Reload page to update alert (simple solution)
        if (isReady || (totalPoints === targetPoints && questionsCount > 0)) {
            // Page will be reloaded to show updated status
        }
    }

    // Add question to DOM
    function addQuestionToList(question, index) {
        // Remove "no questions" message if exists
        $('#questionsList .text-center.py-4').remove();

        let optionsHtml = '';
        question.options.forEach((option, idx) => {
            optionsHtml += `
                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" disabled ${option.is_correct ? 'checked' : ''}>
                    <label class="form-check-label ${option.is_correct ? 'text-success font-weight-bold' : ''}">
                        ${String.fromCharCode(65 + idx)}. ${option.option_text}
                        ${option.is_correct ? '<i class="fas fa-check-circle text-success ml-2"></i>' : ''}
                    </label>
                </div>
            `;
        });

        // Prepare options data for edit button
        const optionsData = JSON.stringify(question.options).replace(/"/g, '&quot;');

        const questionHtml = `
            <div class="card mb-3 question-card" id="question-${question.id}" data-question-id="${question.id}">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>
                        <strong class="question-number">Question ${index}</strong>
                        <span class="badge badge-secondary ml-2">${question.question_type.replace('_', ' ')}</span>
                        <span class="badge badge-info ml-2">${question.points} points</span>
                    </span>
                    <div>
                        <button type="button" class="btn btn-sm btn-warning btn-edit-question"
                                data-question-id="${question.id}"
                                data-question-text="${question.question_text}"
                                data-question-type="${question.question_type}"
                                data-points="${question.points}"
                                data-options='${optionsData}'>
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger btn-delete-question" data-question-id="${question.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <p class="mb-3">${question.question_text}</p>
                    <div class="options">
                        ${optionsHtml}
                    </div>
                </div>
            </div>
        `;

        $('#questionsList').append(questionHtml);
    }

    // Edit question button click
    $(document).on('click', '.btn-edit-question', function() {
        const questionId = $(this).data('question-id');
        const questionText = $(this).data('question-text');
        const questionType = $(this).data('question-type');
        const points = $(this).data('points');
        const options = $(this).data('options');

        // Fill form
        $('#edit_question_id').val(questionId);
        $('#edit_question_text').val(questionText);
        $('#edit_question_type').val(questionType);
        $('#edit_points').val(points);

        // Load options
        $('#editOptionsList').empty();
        if (options && options.length > 0) {
            options.forEach((option, index) => {
                const optionHtml = `
                    <div class="option-item mb-2" data-option="${index}">
                        <div class="d-flex align-items-center">
                            <div class="custom-control custom-radio mr-2">
                                <input type="radio" class="custom-control-input" id="edit_correct_${index}" name="edit_correct_option" value="${index}" ${option.is_correct ? 'checked' : ''} required>
                                <label class="custom-control-label" for="edit_correct_${index}">
                                    <small>Correct</small>
                                </label>
                            </div>
                            <input type="text" class="form-control" name="edit_options[${index}][option_text]" placeholder="Option ${String.fromCharCode(65 + index)}" value="${option.option_text}" required>
                        </div>
                    </div>
                `;
                $('#editOptionsList').append(optionHtml);
            });
        }

        // Show modal
        $('#editQuestionModal').modal('show');
    });

    // Submit edit form via AJAX
    $('#editQuestionForm').on('submit', function(e) {
        e.preventDefault();

        const questionId = $('#edit_question_id').val();
        const examId = {{ $exam->id }};

        // Build data object (no _method needed for POST route)
        const data = {
            _token: '{{ csrf_token() }}',
            question_text: $('#edit_question_text').val(),
            question_type: $('#edit_question_type').val(),
            points: $('#edit_points').val()
        };

        // Build options array
        $('#editOptionsList .option-item').each(function(index) {
            const optionText = $(this).find('input[type="text"]').val();
            const isCorrect = $(this).find('input[type="radio"]').is(':checked');

            data['options[' + index + '][option_text]'] = optionText;
            data['options[' + index + '][is_correct]'] = isCorrect ? 1 : 0;
        });

        console.log('Updating question:', data);

        // Disable button
        const $btn = $('#updateQuestionBtn');
        const originalText = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Updating...');

        $.ajax({
            url: '/admin/exams/' + examId + '/questions/' + questionId + '/quick-update',
            method: 'POST',
            data: data,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    toastr.success('Question updated successfully!');

                    // Update points display
                    updatePointsDisplay(response.total_points);

                    // Close modal
                    $('#editQuestionModal').modal('hide');

                    // Reload page to show updated question
                    setTimeout(function() {
                        location.reload();
                    }, 500);
                }
            },
            error: function(xhr) {
                console.error('Update Error:', xhr);

                if (xhr.responseJSON) {
                    if (xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;
                        Object.keys(errors).forEach(key => {
                            toastr.error(errors[key][0], 'Validation Error');
                        });
                    } else if (xhr.responseJSON.message) {
                        toastr.error(xhr.responseJSON.message, 'Error');
                    } else {
                        toastr.error('Failed to update question.', 'Error');
                    }
                } else {
                    toastr.error('Server error occurred.', 'Error');
                }
            },
            complete: function() {
                $btn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Delete question via AJAX
    $(document).on('click', '.btn-delete-question', function() {
        if (!confirm('Are you sure you want to delete this question?')) {
            return;
        }

        const questionId = $(this).data('question-id');
        const examId = {{ $exam->id }};
        const $questionCard = $('#question-' + questionId);

        // Disable button and show loading
        $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: '/admin/exams/' + examId + '/questions/' + questionId,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    toastr.success('Question deleted successfully!');

                    // Fade out and remove the card
                    $questionCard.fadeOut(400, function() {
                        $(this).remove();

                        // Update question numbers
                        updateQuestionNumbers();

                        // Update count
                        $('#questionsCount').text(response.total_questions);

                        // Update points display
                        updatePointsDisplay(response.total_points);

                        // Show "no questions" message if no questions left
                        if (response.total_questions === 0) {
                            $('#questionsList').html(`
                                <div class="text-center py-4">
                                    <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No questions added yet. Click "Add Question Quick" to create the first question.</p>
                                </div>
                            `);
                        }
                    });
                }
            },
            error: function(xhr) {
                console.error('Delete Error:', xhr);
                toastr.error('Failed to delete question. Please try again.');

                // Re-enable button
                $(this).prop('disabled', false).html('<i class="fas fa-trash"></i>');
            }
        });
    });

    // Update question numbers after deletion
    function updateQuestionNumbers() {
        $('.question-card').each(function(index) {
            $(this).find('.question-number').text('Question ' + (index + 1));
        });
    }
});
</script>
@endpush
