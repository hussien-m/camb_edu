<?php

namespace App\Services\Student;

use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\StudentAnswer;
use App\Models\Certificate;
use Illuminate\Support\Facades\DB;

class StudentExamService
{
    /**
     * Check if student can access this exam
     */
    public function checkExamAccess(Student $student, Exam $exam): array
    {
        // Check enrollment
        $enrollment = Enrollment::where('student_id', $student->id)
            ->where('course_id', $exam->course_id)
            ->where('status', 'active')
            ->first();

        if (!$enrollment) {
            return [
                'allowed' => false,
                'message' => 'You are not enrolled in this course.'
            ];
        }

        // Check max attempts
        $attemptCount = $this->getAttemptCount($student, $exam);
        if ($exam->max_attempts > 0 && $attemptCount >= $exam->max_attempts) {
            return [
                'allowed' => false,
                'message' => 'You have reached the maximum number of attempts for this exam.'
            ];
        }

        return ['allowed' => true];
    }

    /**
     * Get previous attempts for student
     */
    public function getPreviousAttempts(Student $student, Exam $exam)
    {
        return ExamAttempt::where('student_id', $student->id)
            ->where('exam_id', $exam->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get attempt count
     */
    public function getAttemptCount(Student $student, Exam $exam): int
    {
        return ExamAttempt::where('student_id', $student->id)
            ->where('exam_id', $exam->id)
            ->count();
    }

    /**
     * Validate an ongoing attempt
     */
    public function validateAttempt(ExamAttempt $attempt): array
    {
        if ($attempt->status !== 'in_progress') {
            return [
                'valid' => false,
                'expired' => false,
                'message' => 'This exam attempt has already been completed.'
            ];
        }

        $expired = $this->isExamExpired($attempt);
        if ($expired) {
            $this->finishAttempt($attempt, true);
            return [
                'valid' => false,
                'expired' => true,
                'message' => 'Time has expired for this exam.'
            ];
        }

        return ['valid' => true, 'expired' => false];
    }

    /**
     * Get answered questions for an attempt
     */
    public function getAnsweredQuestions(ExamAttempt $attempt): array
    {
        $answeredQuestions = [];
        foreach ($attempt->answers as $answer) {
            $answeredQuestions[$answer->question_id] = $answer->selected_option_id;
        }
        return $answeredQuestions;
    }

    /**
     * Save answer for a question
     */
    public function saveAnswer(ExamAttempt $attempt, array $data): bool
    {
        $question = $attempt->exam->questions()->findOrFail($data['question_id']);
        $option = $question->options()->findOrFail($data['option_id']);

        StudentAnswer::updateOrCreate(
            [
                'attempt_id' => $attempt->id,
                'question_id' => $question->id,
            ],
            [
                'selected_option_id' => $option->id,
                'is_correct' => $option->is_correct,
                'points_earned' => $option->is_correct ? $question->points : 0,
            ]
        );

        return true;
    }

    /**
     * Prepare exam data for student exam view
     * @param Student $student
     * @param Exam $exam
     * @return array
     */
    public function prepareExamForView(Student $student, Exam $exam)
    {
        // Check enrollment
        $enrollment = Enrollment::where('student_id', $student->id)
            ->where('course_id', $exam->course_id)
            ->where('status', 'active')
            ->first();

        // Check attempts
        $attemptsCount = ExamAttempt::where('student_id', $student->id)
            ->where('exam_id', $exam->id)
            ->count();

        // In-progress attempt
        $currentAttempt = ExamAttempt::where('student_id', $student->id)
            ->where('exam_id', $exam->id)
            ->where('status', 'in_progress')
            ->first();

        // Previous completed attempts
        $previousAttempts = ExamAttempt::where('student_id', $student->id)
            ->where('exam_id', $exam->id)
            ->where('status', '!=', 'in_progress')
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'enrollment' => $enrollment,
            'attemptsCount' => $attemptsCount,
            'currentAttempt' => $currentAttempt,
            'previousAttempts' => $previousAttempts,
        ];
    }

    /**
     * Start exam for student
     */
    public function startExam(Student $student, Exam $exam): array
    {
        // Check access first
        $accessCheck = $this->checkExamAccess($student, $exam);
        if (!$accessCheck['allowed']) {
            return [
                'success' => false,
                'message' => $accessCheck['message']
            ];
        }

        // Check for in-progress attempt
        $inProgressAttempt = ExamAttempt::where('student_id', $student->id)
            ->where('exam_id', $exam->id)
            ->where('status', 'in_progress')
            ->first();

        if ($inProgressAttempt) {
            return [
                'success' => true,
                'attempt' => $inProgressAttempt
            ];
        }

        $enrollment = Enrollment::where('student_id', $student->id)
            ->where('course_id', $exam->course_id)
            ->where('status', 'active')
            ->first();

        $attemptNumber = ExamAttempt::where('student_id', $student->id)
            ->where('exam_id', $exam->id)
            ->count() + 1;

        $attempt = ExamAttempt::create([
            'student_id' => $student->id,
            'exam_id' => $exam->id,
            'enrollment_id' => $enrollment->id,
            'start_time' => now(),
            'attempt_number' => $attemptNumber,
            'status' => 'in_progress',
        ]);

        return [
            'success' => true,
            'attempt' => $attempt
        ];
    }

    /**
     * Get exam with questions for taking
     */
    public function getExamForTaking(ExamAttempt $attempt)
    {
        $exam = $attempt->exam->load('questions.options');
        $questions = $exam->questions;

        // Get answered questions with their selected options
        $answeredQuestions = [];
        foreach ($attempt->answers as $answer) {
            $answeredQuestions[$answer->question_id] = $answer->selected_option_id;
        }

        return [
            'exam' => $exam,
            'questions' => $questions,
            'answeredQuestions' => $answeredQuestions,
        ];
    }

    /**
     * Finish exam attempt
     */
    public function finishAttempt(ExamAttempt $attempt, $expired = false)
    {
        return DB::transaction(function () use ($attempt, $expired) {
            $totalScore = $attempt->answers()->sum('points_earned');
            $totalMarks = $attempt->exam->total_marks;
            $percentage = ($totalMarks > 0) ? ($totalScore / $totalMarks) * 100 : 0;
            $passed = $percentage >= $attempt->exam->passing_score;

            $attempt->update([
                'end_time' => now(),
                'score' => $totalScore,
                'percentage' => $percentage,
                'passed' => $passed,
                'status' => $expired ? 'expired' : 'completed',
            ]);

            // Generate certificate if passed
            if ($passed) {
                $this->generateCertificate($attempt);
            }

            return $attempt;
        });
    }

    /**
     * Generate certificate for passed exam
     */
    private function generateCertificate(ExamAttempt $attempt)
    {
        Certificate::create([
            'student_id' => $attempt->student_id,
            'course_id' => $attempt->exam->course_id,
            'exam_attempt_id' => $attempt->id,
            'certificate_number' => Certificate::generateCertificateNumber(),
            'issue_date' => now(),
        ]);

        // Mark enrollment as completed
        $attempt->enrollment->update([
            'status' => 'completed',
            'completed_at' => now(),
            'progress' => 100,
        ]);
    }

    /**
     * Get exam result with details
     */
    public function getExamResult(ExamAttempt $attempt)
    {
        $attempt->load(['exam.questions.options', 'answers.question', 'certificate']);

        $correctAnswers = $attempt->answers->where('is_correct', true)->count();
        $totalQuestions = $attempt->exam->questions->count();
        $remainingAttempts = $attempt->exam->max_attempts - $attempt->attempt_number;

        return [
            'attempt' => $attempt,
            'correctAnswers' => $correctAnswers,
            'totalQuestions' => $totalQuestions,
            'remainingAttempts' => $remainingAttempts,
        ];
    }

    /**
     * Check if exam is expired
     */
    public function isExamExpired(ExamAttempt $attempt): bool
    {
        $endTime = $attempt->start_time->copy()->addMinutes($attempt->exam->duration);
        return now()->gt($endTime);
    }

    /**
     * Get remaining time for exam (in seconds)
     */
    public function getRemainingTime(ExamAttempt $attempt): int
    {
        $endTime = $attempt->start_time->copy()->addMinutes($attempt->exam->duration);
        $remaining = $endTime->diffInSeconds(now(), false);

        return max(0, $remaining);
    }
}
