<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreQuestionRequest;
use App\Http\Requests\Admin\UpdateQuestionRequest;
use App\Models\Exam;
use App\Models\Question;
use App\Services\Admin\QuestionService;
use Illuminate\Support\Facades\Log;

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
        
        // Check if request is AJAX
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Question deleted successfully.',
                'total_questions' => $exam->questions()->count(),
                'total_points' => $exam->questions()->sum('points')
            ]);
        }
        
        return redirect()->route('admin.exams.show', $exam)
            ->with('success', 'Question deleted successfully.');
    }
    
    /**
     * Quick update question via AJAX
     */
    public function quickUpdate(UpdateQuestionRequest $request, Exam $exam, Question $question)
    {
        try {
            Log::info('Quick Update Question Request', [
                'data' => $request->all(),
                'exam_id' => $exam->id,
                'question_id' => $question->id
            ]);
            
            $this->questionService->updateQuestion($question, $request->validated());
            
            // Reload question with options
            $question->load('options');
            
            // Calculate total points
            $totalPoints = $exam->questions()->sum('points');
            $targetPoints = $exam->total_marks;
            
            return response()->json([
                'success' => true,
                'message' => 'Question updated successfully.',
                'question' => $question,
                'total_questions' => $exam->questions()->count(),
                'total_points' => $totalPoints,
                'target_points' => $targetPoints
            ]);
        } catch (\Exception $e) {
            Log::error('QuickUpdate Question Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update question: ' . $e->getMessage(),
                'error' => config('app.debug') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }
    
    /**
     * Quick add question via AJAX
     */
    public function quickAdd(StoreQuestionRequest $request, Exam $exam)
    {
        try {
            Log::info('Quick Add Question Request', [
                'data' => $request->all(),
                'exam_id' => $exam->id
            ]);
            
            $question = $this->questionService->createQuestion($exam, $request->validated());
            
            // Load options relationship
            $question->load('options');
            
            // Calculate total points
            $totalPoints = $exam->questions()->sum('points');
            $targetPoints = $exam->total_marks;
            
            // Check if points exceed total marks
            $warning = null;
            if ($totalPoints > $targetPoints) {
                $warning = "Warning: Total points ({$totalPoints}) exceed exam total marks ({$targetPoints})";
            } elseif ($totalPoints < $targetPoints) {
                $remaining = $targetPoints - $totalPoints;
                $warning = "{$remaining} points remaining to reach total marks";
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Question added successfully.',
                'question' => $question,
                'question_index' => $exam->questions()->count(),
                'total_questions' => $exam->questions()->count(),
                'total_points' => $totalPoints,
                'target_points' => $targetPoints,
                'warning' => $warning
            ]);
        } catch (\Exception $e) {
            Log::error('QuickAdd Question Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to add question: ' . $e->getMessage(),
                'error' => config('app.debug') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }
}
