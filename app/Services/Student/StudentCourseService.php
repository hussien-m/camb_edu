<?php

namespace App\Services\Student;

use App\Models\Student;
use App\Models\Course;
use App\Models\Enrollment;

class StudentCourseService
{
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
                $lastAttempt = $attempts->where('status', 'completed')->sortByDesc('created_at')->first();
                $attemptCount = $attempts->count();
                $inProgress = $attempts->where('status', 'in_progress')->first();
                $examsData[] = [
                    'exam' => $exam,
                    'attempts' => $attempts,
                    'lastAttempt' => $lastAttempt,
                    'attemptCount' => $attemptCount,
                    'inProgress' => $inProgress,
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
        return Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'status' => 'active',
            'enrolled_at' => now(),
        ]);
    }
}
