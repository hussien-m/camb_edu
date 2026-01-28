<?php

namespace App\Services\Student;

use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\ExamStudentAssignment;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\StudentAnswer;
use App\Models\Certificate;
use App\Notifications\ExamAssignmentStartedNotification;
use App\Notifications\ExamAssignmentSubmittedNotification;
use Illuminate\Support\Facades\DB;

class StudentExamService
{
    /**
     * Check if student can access this exam
     */
    public function checkExamAccess(Student $student, Exam $exam): array
    {
        // Check if exam is active
        if ($exam->status !== 'active') {
            return [
                'allowed' => false,
                'message' => 'This exam is not available at the moment.'
            ];
        }

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

        // Check if course content is disabled (enrollment-level first, then course-level)
        $course = $exam->course;
        $contentDisabled = false;
        if ($enrollment->content_disabled !== null) {
            // Check enrollment-level setting first
            $contentDisabled = (bool) $enrollment->content_disabled;
        } elseif ($course && $course->content_disabled !== null) {
            // Fall back to course-level setting
            $contentDisabled = (bool) $course->content_disabled;
        }
        
        if ($contentDisabled) {
            $contactEmail = \App\Models\Setting::get('contact_email', 'info@example.com');
            return [
                'allowed' => false,
                'message' => 'The course content is currently disabled. Please contact the administration at ' . $contactEmail . ' to request access.'
            ];
        }

        // Check if exam is scheduled and available
        // Use strict boolean check - non-scheduled exams (false/0/null) should be accessible
        $isScheduled = ($exam->is_scheduled === true || $exam->is_scheduled === 1);
        
        // Check if exam is disabled for this enrollment
        // BUT: For non-scheduled exams with allow_enrolled_access=true, ignore exam_disabled
        // This allows enrolled students to access non-scheduled exams even if exam_disabled=true
        $examDisabled = ($enrollment->exam_disabled !== null && (bool) $enrollment->exam_disabled);
        
        if ($examDisabled) {
            // Only block if exam is scheduled OR if allow_enrolled_access is false
            // For non-scheduled exams with allow_enrolled_access=true, allow access
            if ($isScheduled || !$exam->allow_enrolled_access) {
                $contactEmail = \App\Models\Setting::get('contact_email', 'info@example.com');
                return [
                    'allowed' => false,
                    'message' => 'Exams are currently disabled for your enrollment. Please contact the administration at ' . $contactEmail . ' to request access.'
                ];
            }
            // If non-scheduled and allow_enrolled_access=true, continue (allow access)
        }

        // Check if exam has questions
        $totalQuestions = $exam->questions()->count();
        if ($totalQuestions === 0) {
            return [
                'allowed' => false,
                'message' => 'This exam is not ready yet. No questions have been added.'
            ];
        }

        // Check if total points equal total_marks (not hardcoded 100)
        $totalPoints = $exam->questions()->sum('points');
        if ($totalPoints != $exam->total_marks) {
            return [
                'allowed' => false,
                'message' => 'This exam is not ready yet. The questions points (' . $totalPoints . ') do not match the exam total marks (' . $exam->total_marks . ').'
            ];
        }

        if ($isScheduled) {
            if (!$exam->scheduled_start_date) {
                return [
                    'allowed' => false,
                    'message' => 'This exam has not been scheduled yet.'
                ];
            }

            $now = now();

            // Check if exam hasn't started yet
            if ($now->lt($exam->scheduled_start_date)) {
                $timeUntil = $exam->scheduled_start_date->diffForHumans();
                return [
                    'allowed' => false,
                    'message' => "This exam will be available {$timeUntil}. Start time: " .
                                 $exam->scheduled_start_date->format('l, F j, Y \a\t g:i A')
                ];
            }

            // Check if exam has ended
            if ($exam->scheduled_end_date && $now->gt($exam->scheduled_end_date)) {
                return [
                    'allowed' => false,
                    'message' => 'This exam has ended. The exam was available until ' .
                                 $exam->scheduled_end_date->format('l, F j, Y \a\t g:i A')
                ];
            }
        }

        // Group assignment checks (only for scheduled exams)
        // For non-scheduled exams, allow enrolled students to access regardless of group_assignment_enabled
        // $isScheduled is already defined above
        if ($exam->group_assignment_enabled && !$exam->allow_enrolled_access && $isScheduled) {
            // Only check assignment for scheduled exams
            if (!$student->email_verified_at) {
                return [
                    'allowed' => false,
                    'message' => 'Please verify your email address before accessing this exam.'
                ];
            }

            $assignment = $this->getLatestAssignment($student, $exam);
            if (!$assignment) {
                return [
                    'allowed' => false,
                    'message' => 'You are not assigned to this exam.'
                ];
            }

            if ($assignment->mode === 'scheduled') {
                $now = now();
                if ($assignment->starts_at && $now->lt($assignment->starts_at)) {
                    return [
                        'allowed' => false,
                        'message' => 'This exam is scheduled and not available yet.'
                    ];
                }

                if ($assignment->ends_at && $now->gt($assignment->ends_at)) {
                    if ($assignment->status === 'assigned') {
                        $assignment->update([
                            'status' => 'missed',
                            'last_activity_at' => now(),
                        ]);
                    }

                    return [
                        'allowed' => false,
                        'message' => 'This exam assignment has expired.'
                    ];
                }
            }

            if (in_array($assignment->status, ['started', 'submitted', 'expired', 'missed', 'graded'], true)) {
                return [
                    'allowed' => false,
                    'message' => 'You have already started this exam and cannot re-enter.'
                ];
            }
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
     * Get attempt count (all attempts including in_progress)
     */
    public function getAttemptCount(Student $student, Exam $exam): int
    {
        return ExamAttempt::where('student_id', $student->id)
            ->where('exam_id', $exam->id)
            ->count();
    }

    /**
     * Get completed attempt count (excluding in_progress and expired)
     */
    public function getCompletedAttemptCount(Student $student, Exam $exam): int
    {
        return ExamAttempt::where('student_id', $student->id)
            ->where('exam_id', $exam->id)
            ->where('status', 'completed')
            ->count();
    }

    /**
     * Get all attempts count by status
     */
    public function getAttemptCountByStatus(Student $student, Exam $exam): array
    {
        $attempts = ExamAttempt::where('student_id', $student->id)
            ->where('exam_id', $exam->id)
            ->get();

        return [
            'total' => $attempts->count(),
            'in_progress' => $attempts->where('status', 'in_progress')->count(),
            'completed' => $attempts->where('status', 'completed')->count(),
            'expired' => $attempts->where('status', 'expired')->count(),
        ];
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

        if ($attempt->exam->group_assignment_enabled) {
            $assignment = ExamStudentAssignment::where('exam_attempt_id', $attempt->id)->first();
            if ($assignment) {
                $assignment->update(['last_activity_at' => now()]);
            }
        }

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

        // Previous completed attempts (including expired)
        $previousAttempts = ExamAttempt::where('student_id', $student->id)
            ->where('exam_id', $exam->id)
            ->whereIn('status', ['completed', 'expired'])
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
            if ($exam->group_assignment_enabled) {
                return [
                    'success' => false,
                    'message' => 'You already started this exam and cannot re-enter.'
                ];
            }

            return [
                'success' => true,
                'attempt' => $inProgressAttempt
            ];
        }

        $assignment = null;
        // Only check assignment for scheduled exams
        $isScheduled = ($exam->is_scheduled === true || $exam->is_scheduled === 1);
        if ($exam->group_assignment_enabled && !$exam->allow_enrolled_access && $isScheduled) {
            $assignment = $this->getLatestAssignment($student, $exam);
            if (!$assignment || $assignment->status !== 'assigned') {
                return [
                    'success' => false,
                    'message' => 'You are not allowed to start this exam.'
                ];
            }
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

        if ($assignment) {
            $assignment->update([
                'status' => 'started',
                'started_at' => now(),
                'last_activity_at' => now(),
                'exam_attempt_id' => $attempt->id,
            ]);

            if ($assignment->assignedBy) {
                $assignment->assignedBy->notify(new ExamAssignmentStartedNotification($exam, $student, $assignment));
            }
        }

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

            // Certificate creation is controlled by admin approval

            if ($attempt->exam->group_assignment_enabled) {
                $assignment = ExamStudentAssignment::where('exam_attempt_id', $attempt->id)->first();
                if ($assignment) {
                    $assignment->update([
                        'status' => $expired ? 'expired' : 'submitted',
                        'submitted_at' => now(),
                        'score' => $totalScore,
                        'percentage' => $percentage,
                        'passed' => $passed,
                        'last_activity_at' => now(),
                    ]);

                    if (!$expired && $assignment->assignedBy) {
                        $assignment->assignedBy->notify(
                            new ExamAssignmentSubmittedNotification($attempt->exam, $attempt->student, $assignment)
                        );
                    }
                }
            }

            return $attempt;
        });
    }

    private function getLatestAssignment(Student $student, Exam $exam): ?ExamStudentAssignment
    {
        return ExamStudentAssignment::where('student_id', $student->id)
            ->where('exam_id', $exam->id)
            ->orderByDesc('assigned_at')
            ->orderByDesc('id')
            ->first();
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

    /**
     * Get all exams for enrolled courses with access info.
     */
    public function getExamsForStudent(Student $student)
    {
        $courseIds = $student->enrollments()
            ->where('status', 'active')
            ->pluck('course_id');

        if ($courseIds->isEmpty()) {
            return collect();
        }

        $exams = Exam::with(['course', 'attempts' => function ($query) use ($student) {
                $query->where('student_id', $student->id)
                    ->orderBy('created_at', 'desc');
            }])
            ->whereIn('course_id', $courseIds)
            ->where('status', 'active')
            ->whereHas('questions')
            ->whereRaw('(SELECT SUM(points) FROM questions WHERE exam_id = exams.id) = exams.total_marks')
            ->orderBy('created_at', 'desc')
            ->get();

        return $exams->map(function ($exam) use ($student) {
            $access = $this->checkExamAccess($student, $exam);
            $attempts = $exam->attempts;
            $inProgress = $attempts->firstWhere('status', 'in_progress');

            return [
                'exam' => $exam,
                'access' => $access,
                'attempts' => $attempts,
                'in_progress' => $inProgress,
                'schedule_status' => $exam->getScheduleStatus(),
                'is_scheduled' => (bool) $exam->is_scheduled,
            ];
        });
    }

    /**
     * Get non-scheduled exams that the student can start now.
     */
    public function getPendingNonScheduledExams(Student $student)
    {
        return $this->getExamsForStudent($student)
            ->filter(function ($item) {
                return !$item['is_scheduled'] && ($item['access']['allowed'] ?? false);
            })
            ->values();
    }
}
