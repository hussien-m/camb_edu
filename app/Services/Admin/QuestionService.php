<?php

namespace App\Services\Admin;

use App\Models\Exam;
use App\Models\Question;
use App\Models\QuestionOption;

class QuestionService
{
    /**
     * Create a new question with options.
     */
    public function createQuestion(Exam $exam, array $data): Question
    {
        $question = $exam->questions()->create([
            'question_text' => $data['question_text'],
            'question_type' => $data['question_type'],
            'points' => $data['points'],
            'order' => $exam->questions()->max('order') + 1,
        ]);

        foreach ($data['options'] as $index => $option) {
            $question->options()->create([
                'option_text' => $option['text'],
                'is_correct' => ($index == $data['correct_option']),
                'order' => $index + 1,
            ]);
        }

        return $question;
    }

    /**
     * Update an existing question with options.
     */
    public function updateQuestion(Question $question, array $data): bool
    {
        $question->update([
            'question_text' => $data['question_text'],
            'question_type' => $data['question_type'],
            'points' => $data['points'],
        ]);

        // Delete removed options
        $keepIds = collect($data['options'])->pluck('id')->filter();
        $question->options()->whereNotIn('id', $keepIds)->delete();

        // Update or create options
        foreach ($data['options'] as $index => $optionData) {
            $isCorrect = ($index == $data['correct_option']);

            if (isset($optionData['id'])) {
                QuestionOption::find($optionData['id'])->update([
                    'option_text' => $optionData['text'],
                    'is_correct' => $isCorrect,
                    'order' => $index + 1,
                ]);
            } else {
                $question->options()->create([
                    'option_text' => $optionData['text'],
                    'is_correct' => $isCorrect,
                    'order' => $index + 1,
                ]);
            }
        }

        return true;
    }

    /**
     * Delete a question.
     */
    public function deleteQuestion(Question $question): bool
    {
        return $question->delete();
    }
}
