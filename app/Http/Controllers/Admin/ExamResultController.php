<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateExamResultRequest;
use App\Models\ExamAttempt;
use App\Models\Exam;
use App\Services\Admin\ExamResultService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ExamResultController extends Controller
{
    protected $resultService;

    public function __construct(ExamResultService $resultService)
    {
        $this->resultService = $resultService;
    }

    public function index(Request $request)
    {
        $attempts = $this->resultService->getFilteredAttempts($request->all());
        $exams = Exam::all();

        // Get statistics
        $stats = [
            'total' => ExamAttempt::count(),
            'completed' => ExamAttempt::where('status', 'completed')->count(),
            'in_progress' => ExamAttempt::where('status', 'in_progress')->count(),
            'expired' => ExamAttempt::where('status', 'expired')->count(),
            'passed' => ExamAttempt::where('passed', true)->count(),
            'failed' => ExamAttempt::where('passed', false)->where('status', 'completed')->count(),
            'with_certificates' => ExamAttempt::whereHas('certificate')->count(),
            'avg_score' => ExamAttempt::where('status', 'completed')->avg('percentage') ?? 0,
            'today' => ExamAttempt::whereDate('created_at', today())->count(),
            'this_week' => ExamAttempt::where('created_at', '>=', now()->startOfWeek())->count(),
            'this_month' => ExamAttempt::where('created_at', '>=', now()->startOfMonth())->count(),
        ];

        return view('admin.exam-results.index', compact('attempts', 'exams', 'stats'));
    }

    public function show($id)
    {
        $data = $this->resultService->getAttemptDetails($id);

        return view('admin.exam-results.show', $data);
    }

    public function edit($id)
    {
        $attempt = ExamAttempt::with(['student', 'exam', 'certificate'])->findOrFail($id);
        return view('admin.exam-results.edit', compact('attempt'));
    }

    public function update(UpdateExamResultRequest $request, $id)
    {
        $result = $this->resultService->updateExamResult($id, $request->validated());

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        return redirect()->route('admin.exam-results.index')
            ->with('success', 'Result updated successfully');
    }

    public function recalculate($id)
    {
        try {
            $this->resultService->recalculateScore($id);
            return redirect()->back()->with('success', 'Score recalculated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to recalculate: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->resultService->deleteAttempt($id);
            return redirect()->route('admin.exam-results.index')
                ->with('success', 'Attempt deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete: ' . $e->getMessage());
        }
    }

    public function toggleCertificate($id)
    {
        $attempt = ExamAttempt::with('exam')->findOrFail($id);
        $enabled = !$attempt->certificate_enabled;

        $this->resultService->setCertificateAccess($attempt, $enabled);

        $message = $enabled ? 'Certificate access enabled.' : 'Certificate access disabled.';

        return redirect()->back()->with('success', $message);
    }

    public function enableCertificatesForExam(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
        ]);

        $count = $this->resultService->enableCertificatesForExam((int) $validated['exam_id']);

        return redirect()->back()->with('success', "Enabled certificate access for {$count} passed attempt(s).");
    }

    public function disableCertificatesForExam(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
        ]);

        $count = $this->resultService->disableCertificatesForExam((int) $validated['exam_id']);

        return redirect()->back()->with('success', "Disabled certificate access for {$count} attempt(s).");
    }

    /**
     * Filter exam results via AJAX
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
            
            $attempts = $this->resultService->getFilteredAttempts($filters);
            
            // Manually set current page if provided
            if ($request->has('page')) {
                $attempts->setCurrentPage($page);
            }

            // Use index URL for pagination links so AJAX can read ?page= from href
            $attempts->withPath(route('admin.exam-results.index'));

            // Return only table rows for AJAX (no tbody wrapper) to avoid nested tbody
            $html = view('admin.exam-results.partials.table_rows', compact('attempts'))->render();
            $pagination = view('admin.exam-results.partials.pagination', compact('attempts'))->render();

            return response()->json([
                'success' => true,
                'html' => $html,
                'pagination' => $pagination,
                'count' => $attempts->total()
            ]);
        } catch (\Exception $e) {
            Log::error('Filter error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error filtering results: ' . $e->getMessage()
            ], 500);
        }
    }
}
