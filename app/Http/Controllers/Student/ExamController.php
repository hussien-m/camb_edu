<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\SubmitAnswerRequest;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Services\Student\StudentExamService;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    protected $examService;

    public function __construct(StudentExamService $examService)
    {
        $this->examService = $examService;
    }

    public function show(Exam $exam)
    {
        $student = Auth::guard('student')->user();

        $result = $this->examService->checkExamAccess($student, $exam);
        if (!$result['allowed']) {
            return redirect()->route('student.courses.index')
                ->with('error', $result['message']);
        }

        $previousAttempts = $this->examService->getPreviousAttempts($student, $exam);
        $attemptCount = $this->examService->getAttemptCount($student, $exam);

        return view('student.exams.show', compact('exam', 'attemptCount', 'previousAttempts'));
    }

    public function start(Exam $exam)
    {
        $student = Auth::guard('student')->user();

        $result = $this->examService->startExam($student, $exam);
        if (!$result['success']) {
            return redirect()->route('student.courses.index')
                ->with('error', $result['message']);
        }

        return redirect()->route('student.exams.take', $result['attempt']);
    }

    public function take(ExamAttempt $attempt)
    {
        $student = Auth::guard('student')->user();

        if ($attempt->student_id !== $student->id) {
            abort(403);
        }

        $result = $this->examService->validateAttempt($attempt);
        if (!$result['valid']) {
            return redirect()->route('student.exams.result', $attempt)
                ->with('warning', $result['message']);
        }

        $exam = $attempt->exam->load('questions.options');
        $questions = $exam->questions;
        $answeredQuestions = $this->examService->getAnsweredQuestions($attempt);
        $expired = $result['expired'];

        return view('student.exams.take', compact('attempt', 'exam', 'questions', 'answeredQuestions', 'expired'));
    }

    public function submitAnswer(SubmitAnswerRequest $request, ExamAttempt $attempt)
    {
        $student = Auth::guard('student')->user();

        if ($attempt->student_id !== $student->id) {
            abort(403);
        }

        $this->examService->saveAnswer($attempt, $request->validated());

        $answeredCount = $attempt->answers()->count();
        $totalQuestions = $attempt->exam->questions()->count();

        return response()->json([
            'success' => true,
            'answered_count' => $answeredCount,
            'unanswered_count' => $totalQuestions - $answeredCount
        ]);
    }

    public function submit(ExamAttempt $attempt)
    {
        $student = Auth::guard('student')->user();

        if ($attempt->student_id !== $student->id) {
            abort(403);
        }

        $this->examService->finishAttempt($attempt);

        return redirect()->route('student.exams.result', $attempt)
            ->with('success', 'Exam submitted successfully.');
    }

    public function result(ExamAttempt $attempt)
    {
        $student = Auth::guard('student')->user();

        if ($attempt->student_id !== $student->id) {
            abort(403);
        }

        if ($attempt->status === 'in_progress') {
            return redirect()->route('student.exams.take', $attempt);
        }

        $attempt->load(['exam.questions.options', 'answers.question', 'answers.selectedOption', 'certificate']);

        $correctAnswers = $attempt->answers->where('is_correct', true)->count();
        $totalQuestions = $attempt->exam->questions->count();
        $remainingAttempts = $attempt->exam->max_attempts - $attempt->attempt_number;

        return view('student.exams.result', compact('attempt', 'correctAnswers', 'totalQuestions', 'remainingAttempts'));
    }

    public function calendar()
    {
        $student = Auth::guard('student')->user();

        // Get all scheduled exams for enrolled courses
        $enrolledCourseIds = $student->enrollments()->pluck('course_id');

        $scheduledExams = Exam::with(['course', 'attempts' => function($query) use ($student) {
                $query->where('student_id', $student->id);
            }])
            ->whereIn('course_id', $enrolledCourseIds)
            ->where('is_scheduled', true)
            ->where('status', 'active')
            ->whereNotNull('scheduled_start_date')
            ->orderBy('scheduled_start_date', 'asc')
            ->get();

        // Filter only ready exams
        $scheduledExams = $scheduledExams->filter(function($exam) {
            return $exam->isReady();
        });

        return view('student.exams.calendar', compact('scheduledExams'));
    }

    public function index()
    {
        $student = Auth::guard('student')->user();
        $exams = $this->examService->getExamsForStudent($student);

        return view('student.exams.index', compact('exams'));
    }
}
