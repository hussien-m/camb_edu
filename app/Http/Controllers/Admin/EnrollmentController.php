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

            // Get statistics
            $stats = [
                'total' => \App\Models\Enrollment::count(),
                'active' => \App\Models\Enrollment::where('status', 'active')->count(),
                'completed' => \App\Models\Enrollment::where('status', 'completed')->count(),
                'content_disabled' => \App\Models\Enrollment::where('content_disabled', true)->count(),
                'content_enabled' => \App\Models\Enrollment::where('content_disabled', false)->count(),
                'exam_disabled' => \App\Models\Enrollment::where('exam_disabled', true)->count(),
                'exam_enabled' => \App\Models\Enrollment::where('exam_disabled', false)->count(),
                'with_exams' => \App\Models\Enrollment::whereHas('course.exams')->count(),
                'recent_week' => \App\Models\Enrollment::where('created_at', '>=', now()->subWeek())->count(),
                'recent_month' => \App\Models\Enrollment::where('created_at', '>=', now()->subMonth())->count(),
            ];

            return view('admin.enrollments.index', compact('enrollments', 'levels', 'stats'));
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

    /**
     * Toggle exam disabled status for an enrollment
     */
    public function toggleExamDisabled(Enrollment $enrollment)
    {
        try {
            $enrollment->exam_disabled = !$enrollment->exam_disabled;
            $enrollment->save();

            return response()->json([
                'success' => true,
                'exam_disabled' => $enrollment->exam_disabled,
                'message' => $enrollment->exam_disabled 
                    ? 'Exams have been disabled for this student successfully' 
                    : 'Exams have been enabled for this student successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error toggling exam disabled: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'enrollment_id' => $enrollment->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating exam status'
            ], 500);
        }
    }

    /**
     * Filter enrollments via AJAX
     */
    public function filter(Request $request)
    {
        try {
            $filters = $request->all();
            
            // Handle pagination
            if ($request->has('page')) {
                $page = $request->get('page', 1);
            } else {
                $page = 1;
            }
            
            $enrollments = $this->enrollmentService->getEnrollmentsWithExamStatus($filters);
            $levels = $this->enrollmentService->getLevels();

            $html = view('admin.enrollments.partials.table', compact('enrollments'))->render();
            $pagination = view('admin.enrollments.partials.pagination', compact('enrollments'))->render();

            return response()->json([
                'success' => true,
                'html' => $html,
                'pagination' => $pagination,
                'count' => $enrollments->total()
            ]);
        } catch (\Exception $e) {
            Log::error('Filter error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error filtering enrollments: ' . $e->getMessage()
            ], 500);
        }
    }
}
