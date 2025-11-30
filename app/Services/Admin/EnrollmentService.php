<?php

namespace App\Services\Admin;

use App\Models\Enrollment;
use App\Models\Course;
use App\Models\CourseLevel;

class EnrollmentService
{
    /**
     * Get enrollments with exam status and filtering
     */
    public function getEnrollmentsWithExamStatus($filters = [])
    {
        $query = Enrollment::with(['student', 'course.level'])
            ->orderBy('enrollments.created_at', 'desc');

        // Filter by student name or email
        if (!empty($filters['student'])) {
            $search = $filters['student'];
            $query->whereHas('student', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by course level
        if (!empty($filters['level_id'])) {
            $query->whereHas('course', function($q) use ($filters) {
                $q->where('courses.level_id', $filters['level_id']);
            });
        }

        // Get all enrollments first (without exam filter for pagination)
        $enrollments = $query->paginate(20);

        // Add exam status to each enrollment
        $enrollments->getCollection()->transform(function($enrollment) {
            $examsCount = $enrollment->course
                ->exams()
                ->where('status', 'active')
                ->count();

            return [
                'enrollment' => $enrollment,
                'student' => $enrollment->student,
                'course' => $enrollment->course,
                'examsCount' => $examsCount,
                'hasExam' => $examsCount > 0,
                'enrolledAt' => $enrollment->created_at,
            ];
        });

        // Filter by exam status in PHP (after pagination)
        if (!empty($filters['exam_status'])) {
            $filtered = $enrollments->getCollection()->filter(function($item) use ($filters) {
                if ($filters['exam_status'] === 'has_exam') {
                    return $item['hasExam'] === true;
                } elseif ($filters['exam_status'] === 'no_exam') {
                    return $item['hasExam'] === false;
                }
                return true;
            });

            $enrollments->setCollection($filtered);
        }

        return $enrollments;
    }

    /**
     * Get all course levels for filter dropdown
     */
    public function getLevels()
    {
        return CourseLevel::orderBy('name')->get();
    }
}
