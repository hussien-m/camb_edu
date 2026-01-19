<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Services\Student\StudentCourseService;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    protected $courseService;

    public function __construct(StudentCourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    public function index()
    {
        $student = Auth::guard('student')->user();
        $courses = $this->courseService->getEnrolledCourses($student);

        return view('student.courses.index', compact('courses'));
    }

    public function content()
    {
        $student = Auth::guard('student')->user();
        $courses = $this->courseService->getEnrolledCourses($student);

        return view('student.courses.content', compact('courses'));
    }

    public function enroll(Course $course)
    {
        $student = Auth::guard('student')->user();

        $result = $this->courseService->enrollStudent($student, $course);

        if (!$result['success']) {
            return back()->with('info', $result['message']);
        }

        return redirect()->route('student.courses.index')
            ->with('success', $result['message']);
    }
}
