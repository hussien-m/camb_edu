<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
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

    /**
     * Toggle content disabled status for an enrollment
     */
    public function toggleContentDisabled(Enrollment $enrollment)
    {
        try {
            $enrollment->content_disabled = !$enrollment->content_disabled;
            $enrollment->save();

            return response()->json([
                'success' => true,
                'content_disabled' => $enrollment->content_disabled,
                'message' => $enrollment->content_disabled 
                    ? 'Course content has been disabled for this student successfully' 
                    : 'Course content has been enabled for this student successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error toggling content disabled: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'enrollment_id' => $enrollment->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating content status'
            ], 500);
        }
    }
}
