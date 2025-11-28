<?php

namespace App\Services\Student;

use App\Models\Student;
use Illuminate\Support\Facades\DB;

class StudentDashboardService
{
    /**
     * Get dashboard data for student
     */
    public function getDashboardData(Student $student)
    {
        return [
            'student' => $student,
            'stats' => $this->getStudentStats($student),
            'enrolledCourses' => $this->getEnrolledCoursesCount($student),
            'completedExams' => $this->getCompletedExamsCount($student),
            'certificates' => $this->getCertificatesCount($student),
            'recentActivity' => $this->getRecentActivity($student),
        ];
    }

    /**
     * Get student statistics
     */
    public function getStudentStats(Student $student)
    {
        $totalEnrollments = $student->enrollments()->count();
        $completedEnrollments = $student->enrollments()
            ->where('status', 'completed')
            ->count();
        $activeCourses = $student->enrollments()
            ->where('status', 'active')
            ->count();
        $totalAttempts = $student->examAttempts()->count();
        $passedAttempts = $student->examAttempts()
            ->where('status', 'passed')
            ->count();

        return [
            'total_enrollments' => $totalEnrollments,
            'completed_enrollments' => $completedEnrollments,
            'active_courses' => $activeCourses,
            'total_attempts' => $totalAttempts,
            'passed_attempts' => $passedAttempts,
            'pass_rate' => $totalAttempts > 0 ? round(($passedAttempts / $totalAttempts) * 100) : 0,
        ];
    }

    /**
     * Get enrolled courses count
     */
    public function getEnrolledCoursesCount(Student $student)
    {
        return $student->enrollments()
            ->where('status', 'active')
            ->count();
    }

    /**
     * Get completed exams count
     */
    public function getCompletedExamsCount(Student $student)
    {
        return $student->examAttempts()
            ->where('status', '!=', 'in_progress')
            ->count();
    }

    /**
     * Get certificates count
     */
    public function getCertificatesCount(Student $student)
    {
        return $student->certificates()->count();
    }

    /**
     * Get recent activity for student
     */
    public function getRecentActivity(Student $student, $limit = 5)
    {
        $activity = collect();

        // Recent exam attempts
        $recentAttempts = $student->examAttempts()
            ->with('exam')
            ->latest('created_at')
            ->limit($limit)
            ->get()
            ->map(function ($attempt) {
                return [
                    'type' => 'exam_attempt',
                    'title' => 'Attempted: ' . $attempt->exam->title,
                    'status' => $attempt->status,
                    'date' => $attempt->created_at,
                ];
            });

        $activity = $activity->merge($recentAttempts);

        // Recent enrollments
        $recentEnrollments = $student->enrollments()
            ->with('course')
            ->latest('created_at')
            ->limit($limit)
            ->get()
            ->map(function ($enrollment) {
                return [
                    'type' => 'enrollment',
                    'title' => 'Enrolled in: ' . $enrollment->course->title,
                    'status' => $enrollment->status,
                    'date' => $enrollment->created_at,
                ];
            });

        $activity = $activity->merge($recentEnrollments);

        // Recent certificates
        $recentCertificates = $student->certificates()
            ->latest('created_at')
            ->limit($limit)
            ->get()
            ->map(function ($cert) {
                return [
                    'type' => 'certificate',
                    'title' => 'Certificate received',
                    'status' => 'completed',
                    'date' => $cert->created_at,
                ];
            });

        $activity = $activity->merge($recentCertificates);

        return $activity->sortByDesc('date')->take($limit);
    }
}
