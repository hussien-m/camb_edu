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
     * Update an existing question with options.
     */
    public function updateQuestion(Question $question, array $data): bool
    {
        try {
            \DB::beginTransaction();
            
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
        return $question->delete();
    }
}
