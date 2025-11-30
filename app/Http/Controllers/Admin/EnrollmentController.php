<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\EnrollmentService;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    protected $enrollmentService;

    public function __construct(EnrollmentService $enrollmentService)
    {
        $this->enrollmentService = $enrollmentService;
    }

    /**
     * Show enrollments with exam status
     */
    public function index(Request $request)
    {
        $enrollments = $this->enrollmentService->getEnrollmentsWithExamStatus($request->all());
        $levels = $this->enrollmentService->getLevels();

        return view('admin.enrollments.index', compact('enrollments', 'levels'));
    }
}
