<?php

namespace App\Services\Admin;

use App\Models\Exam;

class ExamService
{
    /**
     * Create a new exam.
     */
    public function createExam(array $data): Exam
    {
        return Exam::create($data);
    }

    /**
     * Update an existing exam.
     */
    public function updateExam(Exam $exam, array $data): bool
    {
        return $exam->update($data);
    }

    /**
     * Delete an exam.
     */
    public function deleteExam(Exam $exam): bool
    {
        return $exam->delete();
    }
}
