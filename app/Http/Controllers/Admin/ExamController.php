<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreExamRequest;
use App\Http\Requests\Admin\UpdateExamRequest;
use App\Models\Course;
use App\Models\Exam;
use App\Services\Admin\ExamService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ExamController extends Controller
{
    protected $examService;

    public function __construct(ExamService $examService)
    {
        $this->examService = $examService;
    }

    public function index(Request $request)
    {
        try {
            $query = Exam::with('course');

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhereHas('course', function($courseQuery) use ($search) {
                          $courseQuery->where('title', 'like', "%{$search}%");
                      });
                });
            }

            // Filter by course
            if ($request->filled('course_id')) {
                $query->where('course_id', $request->course_id);
            }

            // Filter by status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            $exams = $query->paginate(20)->withQueryString();
            $courses = Course::where('status', 'active')->orderBy('title')->get();

            return view('admin.exams.index', compact('exams', 'courses'));
        } catch (\Exception $e) {
            Log::error('Error fetching exams: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')
                ->with('error', 'An error occurred while loading exams. Please try again.');
        }
    }

    public function create()
    {
        $courses = Course::with('level')
            ->where('status', 'active')
            ->orderBy('title')
            ->get();
        return view('admin.exams.create', compact('courses'));
    }

    public function store(StoreExamRequest $request)
    {
        $exam = $this->examService->createExam($request->validated());

        return redirect()->route('admin.exams.show', $exam)
            ->with('success', 'Exam created successfully.');
    }

    public function show(Exam $exam)
    {
        $exam->load(['course', 'questions.options', 'attempts']);
        return view('admin.exams.show', compact('exam'));
    }

    public function edit(Exam $exam)
    {
        $courses = Course::with('level')
            ->where('status', 'active')
            ->orderBy('title')
            ->get();
        return view('admin.exams.edit', compact('exam', 'courses'));
    }

    public function update(UpdateExamRequest $request, Exam $exam)
    {
        try {
            $this->examService->updateExam($exam, $request->validated());

            return redirect()->route('admin.exams.show', $exam)
                ->with('success', 'Exam updated successfully.');
        } catch (\Exception $e) {
            Log::error('Exam update failed: ' . $e->getMessage());
            return redirect()->route('admin.exams.edit', $exam)
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Exam $exam)
    {
        $this->examService->deleteExam($exam);
        return redirect()->route('admin.exams.index')
            ->with('success', 'Exam deleted successfully.');
    }
}
