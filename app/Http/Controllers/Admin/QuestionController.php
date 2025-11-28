<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreQuestionRequest;
use App\Http\Requests\Admin\UpdateQuestionRequest;
use App\Models\Exam;
use App\Models\Question;
use App\Services\Admin\QuestionService;

class QuestionController extends Controller
{
    protected $questionService;

    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
    }

    public function create(Exam $exam)
    {
        return view('admin.questions.create', compact('exam'));
    }

    public function store(StoreQuestionRequest $request, Exam $exam)
    {
        $this->questionService->createQuestion($exam, $request->validated());

        return redirect()->route('admin.exams.show', $exam)
            ->with('success', 'Question added successfully.');
    }

    public function edit(Exam $exam, Question $question)
    {
        $question->load('options');
        return view('admin.questions.edit', compact('exam', 'question'));
    }

    public function update(UpdateQuestionRequest $request, Exam $exam, Question $question)
    {
        $this->questionService->updateQuestion($question, $request->validated());

        return redirect()->route('admin.exams.show', $exam)
            ->with('success', 'Question updated successfully.');
    }

    public function destroy(Exam $exam, Question $question)
    {
        $question->delete();
        return redirect()->route('admin.exams.show', $exam)
            ->with('success', 'Question deleted successfully.');
    }
}
