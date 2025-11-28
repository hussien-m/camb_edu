<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\Student\StudentDashboardService;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(StudentDashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        $student = auth()->guard('student')->user();
        $dashboardData = $this->dashboardService->getDashboardData($student);

        return view('student.dashboard', compact('student') + $dashboardData);
    }
}
