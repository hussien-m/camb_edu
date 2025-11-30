<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateExamResultRequest;
use App\Models\ExamAttempt;
use App\Models\Exam;
use App\Services\Admin\ExamResultService;
use Illuminate\Http\Request;

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

        return view('admin.exam-results.index', compact('attempts', 'exams'));
    }

    public function show($id)
    {
        $data = $this->resultService->getAttemptDetail($id);

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
}
