<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreExamRequest;
use App\Http\Requests\Admin\UpdateExamRequest;
use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamStudentAssignment;
use App\Models\Student;
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

            // Get statistics
            $stats = [
                'total' => Exam::count(),
                'active' => Exam::where('status', 'active')->count(),
                'inactive' => Exam::where('status', 'inactive')->count(),
                'with_questions' => Exam::whereHas('questions')->count(),
                'scheduled' => Exam::where('is_scheduled', true)->count(),
                'ready' => Exam::whereHas('questions')
                    ->whereRaw('(SELECT SUM(points) FROM questions WHERE exam_id = exams.id) = exams.total_marks')
                    ->count(),
                'total_attempts' => \App\Models\ExamAttempt::count(),
                'completed_attempts' => \App\Models\ExamAttempt::where('status', 'completed')->count(),
            ];

            return view('admin.exams.index', compact('exams', 'courses', 'stats'));
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

    public function show(Request $request, Exam $exam)
    {
        $exam->load(['course', 'questions.options', 'attempts']);

        $assignmentStats = [];
        $assignments = collect();
        $verifiedStudents = collect();
        $assignmentFilters = [
            'status' => $request->get('assignment_status'),
            'search' => $request->get('assignment_search'),
        ];

        if ($exam->group_assignment_enabled) {
            ExamStudentAssignment::where('exam_id', $exam->id)
                ->where('mode', 'scheduled')
                ->where('status', 'assigned')
                ->whereNotNull('ends_at')
                ->where('ends_at', '<', now())
                ->update(['status' => 'missed', 'last_activity_at' => now()]);

            $assignmentQuery = ExamStudentAssignment::with('student')
                ->where('exam_id', $exam->id);

            if (!empty($assignmentFilters['status'])) {
                $assignmentQuery->where('status', $assignmentFilters['status']);
            }

            if (!empty($assignmentFilters['search'])) {
                $search = $assignmentFilters['search'];
                $assignmentQuery->whereHas('student', function ($query) use ($search) {
                    $query->where('email', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
            }

            $assignments = $assignmentQuery
                ->orderBy('assigned_at', 'desc')
                ->paginate(20)
                ->withQueryString();

            $allAssignments = ExamStudentAssignment::where('exam_id', $exam->id)->get();
            $assignmentStats = [
                'total' => $allAssignments->count(),
                'assigned' => $allAssignments->where('status', 'assigned')->count(),
                'started' => $allAssignments->where('status', 'started')->count(),
                'submitted' => $allAssignments->whereIn('status', ['submitted', 'graded'])->count(),
                'missed' => $allAssignments->where('status', 'missed')->count(),
                'expired' => $allAssignments->where('status', 'expired')->count(),
                'avg_score' => $allAssignments->whereIn('status', ['submitted', 'graded'])
                    ->avg('percentage') ?? 0,
            ];

            $verifiedStudents = Student::whereNotNull('email_verified_at')
                ->orderBy('first_name')
                ->orderBy('last_name')
                ->get();
        }

        return view('admin.exams.show', compact(
            'exam',
            'assignmentStats',
            'assignments',
            'assignmentFilters',
            'verifiedStudents'
        ));
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
