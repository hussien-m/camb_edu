<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Exam;
use App\Models\Enrollment;
use App\Services\Student\StudentExamService;
use Illuminate\Http\Request;

class DebugController extends Controller
{
    public function checkExamAccess(Request $request)
    {
        $email = $request->get('email', 'alfeqawy.h@gmail.com');
        $examId = $request->get('exam_id');
        
        $student = Student::where('email', $email)->first();
        
        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }
        
        $result = [
            'student' => [
                'id' => $student->id,
                'name' => $student->full_name,
                'email' => $student->email,
                'email_verified' => $student->email_verified_at ? 'Yes' : 'No',
            ],
            'enrollments' => [],
            'exams' => [],
        ];
        
        $enrollments = $student->enrollments()->with('course')->get();
        
        foreach ($enrollments as $enrollment) {
            $course = $enrollment->course;
            $result['enrollments'][] = [
                'course_id' => $course->id,
                'course_title' => $course->title,
                'enrollment_status' => $enrollment->status,
                'content_disabled' => $enrollment->content_disabled,
                'exam_disabled' => $enrollment->exam_disabled,
                'course_content_disabled' => $course->content_disabled,
            ];
            
            $exams = $course->exams()->with('questions')->get();
            
            foreach ($exams as $exam) {
                $totalQuestions = $exam->questions()->count();
                $totalPoints = $exam->questions()->sum('points');
                
                $examService = new StudentExamService();
                $accessCheck = $examService->checkExamAccess($student, $exam);
                
                $result['exams'][] = [
                    'exam_id' => $exam->id,
                    'exam_title' => $exam->title,
                    'status' => $exam->status,
                    'is_scheduled' => $exam->is_scheduled,
                    'scheduled_start_date' => $exam->scheduled_start_date,
                    'scheduled_end_date' => $exam->scheduled_end_date,
                    'total_marks' => $exam->total_marks,
                    'total_questions' => $totalQuestions,
                    'total_points' => $totalPoints,
                    'points_match' => $totalPoints == $exam->total_marks,
                    'group_assignment_enabled' => $exam->group_assignment_enabled,
                    'allow_enrolled_access' => $exam->allow_enrolled_access,
                    'access_allowed' => $accessCheck['allowed'],
                    'access_message' => $accessCheck['message'] ?? null,
                ];
            }
        }
        
        return response()->json($result, 200, [], JSON_PRETTY_PRINT);
    }
}
