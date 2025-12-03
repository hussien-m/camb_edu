<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\EnrollmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        try {
            $enrollments = $this->enrollmentService->getEnrollmentsWithExamStatus($request->all());
            $levels = $this->enrollmentService->getLevels();

            return view('admin.enrollments.index', compact('enrollments', 'levels'));
        } catch (\Exception $e) {
            Log::error('Error fetching enrollments: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('admin.dashboard')
                ->with('error', 'An error occurred while loading enrollments. Please try again.');
        }
    }
}
