<?php

namespace App\Services\Admin;

use App\Models\Exam;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QuestionService
{
    /**
     * Create a new question with options.
     */
    public function createQuestion(Exam $exam, array $data): Question
    {
        try {
            \DB::beginTransaction();
            
            $question = $exam->questions()->create([
                'question_text' => $data['question_text'],
                'question_type' => $data['question_type'],
                'points' => $data['points'],
                'order' => $exam->questions()->max('order') + 1,
            ]);

            foreach ($data['options'] as $index => $option) {
                $question->options()->create([
                    'option_text' => $option['option_text'],
                    'is_correct' => (bool) $option['is_correct'],
                    'order' => $index + 1,
                ]);
            }
            
            \DB::commit();
            return $question;
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Question creation failed: ' . $e->getMessage(), [
                'data' => $data,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Check if exam has attempts
     */
    private function examHasAttempts(Exam $exam): bool
    {
        return $exam->attempts()->exists();
    }

    /**
     * Update an existing question with options.
     */
    public function updateQuestion(Question $question, array $data): bool
    {
        try {
            \DB::beginTransaction();
            
            $exam = $question->exam;
            
            // Check if exam has attempts - protect critical fields
            if ($this->examHasAttempts($exam)) {
                // Check if points are being changed
                if (isset($data['points']) && $question->points != $data['points']) {
                    throw new \Exception("Cannot modify question points after students have attempted this exam. Current points: {$question->points}, Attempted points: {$data['points']}");
                }
                
                // Check if correct answer is being changed
                $oldCorrectOption = $question->options()->where('is_correct', true)->first();
                $newCorrectOption = collect($data['options'])->firstWhere('is_correct', true);
                
                if ($oldCorrectOption && $newCorrectOption) {
                    $oldCorrectId = $oldCorrectOption->id;
                    $newCorrectText = $newCorrectOption['option_text'] ?? $newCorrectOption['text'] ?? '';
                    
                    // If correct answer changed, warn (but allow for now with warning)
                    if ($oldCorrectOption->option_text != $newCorrectText) {
                        \Log::warning("Question correct answer changed after attempts exist", [
                            'question_id' => $question->id,
                            'exam_id' => $exam->id,
                            'old_answer' => $oldCorrectOption->option_text,
                            'new_answer' => $newCorrectText
                        ]);
                    }
                }
            }
            
            $question->update([
                'question_text' => $data['question_text'],
                'question_type' => $data['question_type'],
                'points' => $data['points'],
            ]);

            // Delete all old options and create new ones (simpler approach)
            $question->options()->delete();

            // Create new options
            foreach ($data['options'] as $index => $option) {
                $question->options()->create([
                    'option_text' => $option['option_text'] ?? $option['text'] ?? '',
                    'is_correct' => (bool) ($option['is_correct'] ?? false),
                    'order' => $index + 1,
                ]);
            }
            
            \DB::commit();
            return true;
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Question update failed: ' . $e->getMessage(), [
                'question_id' => $question->id,
                'data' => $data,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Delete a question.
     */
    public function deleteQuestion(Question $question): bool
    {
        $exam = $question->exam;
        
        // Prevent deletion if exam has attempts
        if ($this->examHasAttempts($exam)) {
            throw new \Exception("Cannot delete question after students have attempted this exam. Exam has " . $exam->attempts()->count() . " attempt(s).");
        }
        
        return $question->delete();
    }
}
