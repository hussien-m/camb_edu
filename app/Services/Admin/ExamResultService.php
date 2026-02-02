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
        $query = ExamAttempt::with(['student', 'exam', 'certificate']);

        // Student search
        if (!empty($filters['student'])) {
            $query->whereHas('student', function($q) use ($filters) {
                $search = $filters['student'];
                $q->where(function($sq) use ($search) {
                    $sq->where('first_name', 'like', '%' . $search . '%')
                       ->orWhere('last_name', 'like', '%' . $search . '%')
                       ->orWhere('email', 'like', '%' . $search . '%')
                       ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $search . '%']);
                });
            });
        }

        // Exam filter
        if (!empty($filters['exam_id'])) {
            $query->where('exam_id', $filters['exam_id']);
        }

        // Status filter (passed/failed)
        if (!empty($filters['status'])) {
            if ($filters['status'] === 'passed') {
                $query->where('passed', true)->where('status', 'completed');
            } elseif ($filters['status'] === 'failed') {
                $query->where('passed', false)->where('status', 'completed');
            }
        }

        // Attempt status filter (completed/not_completed)
        if (!empty($filters['attempt_status'])) {
            if ($filters['attempt_status'] === 'completed') {
                $query->where('status', 'completed');
            } elseif ($filters['attempt_status'] === 'not_completed') {
                $query->where('status', '!=', 'completed');
            }
        }

        // Date from
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        // Date to
        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        // Sort by date
        $sortOrder = $filters['sort_date'] ?? 'desc'; // 'asc' for oldest first, 'desc' for newest first
        $query->orderBy('created_at', $sortOrder);

        $perPage = 20;
        $page = isset($filters['page']) ? (int)$filters['page'] : 1;
        
        return $query->paginate($perPage, ['*'], 'page', $page)->withQueryString();
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

            // تفعيل الشهادة تلقائياً عند النجاح
            if ($data['passed']) {
                $this->setCertificateAccess($attempt->fresh(), true);
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

            // تفعيل الشهادة تلقائياً عند النجاح بعد إعادة الحساب
            if ($passed) {
                $this->setCertificateAccess($attempt->fresh(), true);
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

    public function setCertificateAccess(ExamAttempt $attempt, bool $enabled): void
    {
        $attempt->update(['certificate_enabled' => $enabled]);

        if ($enabled && !$attempt->certificate) {
            $this->generateCertificate($attempt);
        }
    }

    /**
     * Enable certificate access for all passed attempts of an exam (الناجحين فقط).
     */
    public function enableCertificatesForExam(int $examId): int
    {
        $attempts = ExamAttempt::with('certificate')
            ->where('exam_id', $examId)
            ->where('status', 'completed')
            ->where('passed', true)
            ->get();

        $enabledCount = 0;
        foreach ($attempts as $attempt) {
            if (!$attempt->certificate_enabled) {
                $this->setCertificateAccess($attempt, true);
                $enabledCount++;
            }
        }

        return $enabledCount;
    }

    /**
     * Disable certificate access for all attempts of an exam (تعطيل جماعي).
     */
    public function disableCertificatesForExam(int $examId): int
    {
        $count = ExamAttempt::where('exam_id', $examId)
            ->where('certificate_enabled', true)
            ->update(['certificate_enabled' => false]);

        return $count;
    }
}
