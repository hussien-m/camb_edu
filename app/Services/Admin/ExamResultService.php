<?php

namespace App\Services\Admin;

use App\Models\ExamAttempt;
use App\Models\Certificate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ExamResultService
{
    /**
     * Get filtered exam attempts.
     */
    public function getFilteredAttempts(array $filters)
    {
        $query = ExamAttempt::with(['student', 'exam', 'certificate'])
            ->orderBy('created_at', 'desc');

        if (!empty($filters['student'])) {
            $query->whereHas('student', function($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['student'] . '%');
            });
        }

        if (!empty($filters['exam_id'])) {
            $query->where('exam_id', $filters['exam_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('passed', $filters['status'] === 'passed' ? 1 : 0);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->paginate(20);
    }

    /**
     * Get detailed attempt with calculations.
     */
    public function getAttemptDetails($id)
    {
        $attempt = ExamAttempt::with([
            'student',
            'exam.questions.options',
            'answers.question.options',
            'certificate'
        ])->findOrFail($id);

        $totalQuestions = $attempt->exam->questions->count();
        $correctAnswers = $attempt->answers->where('is_correct', true)->count();
        $wrongAnswers = $totalQuestions - $correctAnswers;

        return [
            'attempt' => $attempt,
            'totalQuestions' => $totalQuestions,
            'correctAnswers' => $correctAnswers,
            'wrongAnswers' => $wrongAnswers,
        ];
    }

    /**
     * Update exam result manually.
     */
    public function updateExamResult($id, array $data)
    {
        DB::beginTransaction();
        try {
            $attempt = ExamAttempt::findOrFail($id);

            $attempt->update([
                'score' => $data['score'],
                'percentage' => $data['percentage'],
                'passed' => $data['passed'],
                'admin_notes' => $data['admin_notes'] ?? null,
            ]);

            // Handle certificate generation/deletion
            if ($data['passed']) {
                if (!$attempt->certificate) {
                    $this->generateCertificate($attempt);
                }
            } else {
                if ($attempt->certificate) {
                    $attempt->certificate->delete();
                }
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Recalculate score based on saved answers.
     */
    public function recalculateScore($id)
    {
        DB::beginTransaction();
        try {
            $attempt = ExamAttempt::with(['answers', 'exam'])->findOrFail($id);

            $correctCount = 0;
            $totalQuestions = $attempt->exam->questions()->count();

            // Recalculate each answer
            foreach ($attempt->answers as $answer) {
                $question = $answer->question;
                $correctOption = $question->options()->where('is_correct', true)->first();

                if ($correctOption && $answer->selected_option_id == $correctOption->id) {
                    $answer->is_correct = true;
                    $answer->points_earned = $question->points ?? 1;
                    $correctCount++;
                } else {
                    $answer->is_correct = false;
                    $answer->points_earned = 0;
                }
                $answer->save();
            }

            // Calculate total score and percentage
            $totalMarks = $attempt->exam->total_marks;
            $earnedMarks = $attempt->answers->sum('points_earned');
            $percentage = $totalMarks > 0 ? ($earnedMarks / $totalMarks) * 100 : 0;
            $passed = $percentage >= $attempt->exam->passing_score;

            // Update attempt
            $attempt->update([
                'score' => $earnedMarks,
                'percentage' => round($percentage, 2),
                'passed' => $passed,
            ]);

            // Handle certificate
            if ($passed && !$attempt->certificate) {
                $this->generateCertificate($attempt);
            } elseif (!$passed && $attempt->certificate) {
                $attempt->certificate->delete();
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Delete exam attempt with all related data.
     */
    public function deleteAttempt($id)
    {
        DB::beginTransaction();
        try {
            $attempt = ExamAttempt::with(['answers', 'certificate'])->findOrFail($id);

            // Delete certificate if exists
            if ($attempt->certificate) {
                if ($attempt->certificate->certificate_file) {
                    Storage::disk('public')->delete($attempt->certificate->certificate_file);
                }
                $attempt->certificate->delete();
            }

            // Delete all answers
            $attempt->answers()->delete();

            // Delete attempt
            $attempt->delete();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Generate certificate for passed attempt.
     */
    private function generateCertificate($attempt)
    {
        Certificate::create([
            'student_id' => $attempt->student_id,
            'course_id' => $attempt->exam->course_id,
            'exam_attempt_id' => $attempt->id,
            'certificate_number' => $this->generateCertificateNumber(),
            'issue_date' => now(),
        ]);
    }

    /**
     * Generate unique certificate number.
     */
    private function generateCertificateNumber()
    {
        return 'CERT-' . strtoupper(uniqid()) . '-' . date('Y');
    }
}
