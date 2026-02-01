<?php

namespace App\Services\Student;

use App\Models\Student;
use App\Models\Course;
use App\Models\Enrollment;
use App\Services\Student\StudentExamService;

class StudentCourseService
{
    protected $examService;

    public function __construct(StudentExamService $examService)
    {
        $this->examService = $examService;
    }

    /**
     * Get enrolled courses for student
     */
    public function getEnrolledCourses(Student $student)
    {
        $enrolledCourseIds = $student->enrollments()->pluck('course_id');

        $courses = Course::with([
            'exams' => function($query) use ($student) {
                // Only show exams that are ready (have questions and points = total_marks)
                $query->whereHas('questions')
                      ->whereRaw('(SELECT SUM(points) FROM questions WHERE exam_id = exams.id) = exams.total_marks')
                      ->with(['attempts' => function($q) use ($student) {
                          $q->where('student_id', $student->id)
                            ->orderBy('created_at', 'desc');
                      }]);
            },
            'enrollments' => function($query) use ($student) {
                $query->where('student_id', $student->id);
            },
            'category',
            'level'
        ])
        ->whereIn('id', $enrolledCourseIds)
        ->get();

        return $this->prepareCoursesForView($student, $courses);
    }

    /**
     * Prepare course data for student courses view (English only)
     *
     * @param Student $student
     * @param \Illuminate\Support\Collection|array $courses
     * @return array
     */
    public function prepareCoursesForView(Student $student, $courses)
    {
        $result = [];
        foreach ($courses as $course) {
            $enrollment = $course->enrollments->first();
            $progress = $enrollment ? $enrollment->progress : 0;
            $examsData = [];
            foreach ($course->exams as $exam) {
                $attempts = $exam->attempts;
                // Include both completed and expired attempts in statistics
                $completedAttempts = $attempts->whereIn('status', ['completed', 'expired']);
                $lastAttempt = $completedAttempts->sortByDesc('created_at')->first();
                $bestAttempt = $completedAttempts->sortByDesc('percentage')->first();
                $attemptCount = $attempts->count();
                $inProgress = $attempts->where('status', 'in_progress')->first();

                // Check if student can start this exam
                $accessCheck = $this->examService->checkExamAccess($student, $exam);
                $canStart = $accessCheck['allowed'];

                // Calculate exam statistics
                $hasCompletedAttempts = $completedAttempts->count() > 0;
                $highestScore = $bestAttempt ? $bestAttempt->percentage : null;
                $lastScore = $lastAttempt ? $lastAttempt->percentage : null;
                $hasPassed = $bestAttempt ? $bestAttempt->passed : false;

                // Check if exam has ended (for scheduled exams)
                $examEnded = false;
                $examNotStarted = false;
                $isScheduled = ($exam->is_scheduled === true || $exam->is_scheduled === 1);
                if ($isScheduled) {
                    if ($exam->scheduled_start_date && now()->lt($exam->scheduled_start_date)) {
                        $examNotStarted = true;
                    }
                    if ($exam->scheduled_end_date && now()->gt($exam->scheduled_end_date)) {
                        $examEnded = true;
                    }
                }

                // Check if max attempts reached
                $maxAttemptsReached = $exam->max_attempts > 0 && $attemptCount >= $exam->max_attempts;

                $examsData[] = [
                    'exam' => $exam,
                    'attempts' => $attempts,
                    'lastAttempt' => $lastAttempt,
                    'bestAttempt' => $bestAttempt,
                    'attemptCount' => $attemptCount,
                    'inProgress' => $inProgress,
                    'canStart' => $canStart,
                    'accessMessage' => $accessCheck['message'] ?? null,
                    'hasCompletedAttempts' => $hasCompletedAttempts,
                    'highestScore' => $highestScore,
                    'lastScore' => $lastScore,
                    'hasPassed' => $hasPassed,
                    'examEnded' => $examEnded,
                    'examNotStarted' => $examNotStarted,
                    'maxAttemptsReached' => $maxAttemptsReached,
                ];
            }
            $result[] = [
                'course' => $course,
                'enrollment' => $enrollment,
                'progress' => $progress,
                'examsData' => $examsData,
            ];
        }
        return $result;
    }

    /**
     * Check if student is already enrolled in course
     */
    public function isAlreadyEnrolled(Student $student, Course $course): bool
    {
        return Enrollment::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->exists();
    }

    /**
     * Enroll student in course
     */
    public function enrollStudent(Student $student, Course $course)
    {
        // Create enrollment with content disabled by default
        // Admin must enable content for each student manually
        return Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'status' => 'active',
            'enrolled_at' => now(),
            'content_disabled' => false, // Content enabled by default after enrollment
            'exam_disabled' => false, // Exams enabled by default after enrollment
        ]);
    }
}
