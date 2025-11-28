<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreExamRequest;
use App\Http\Requests\Admin\UpdateExamRequest;
use App\Models\Course;
use App\Models\Exam;
use App\Services\Admin\ExamService;

class ExamController extends Controller
{
    protected $examService;

    public function __construct(ExamService $examService)
    {
        $this->examService = $examService;
    }

    public function index()
    {
        $exams = Exam::with('course')->latest()->paginate(20);
        return view('admin.exams.index', compact('exams'));
    }

    public function create()
    {
        $courses = Course::where('status', 'active')->orderBy('title')->get();
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
        $courses = Course::where('status', 'active')->orderBy('title')->get();
        return view('admin.exams.edit', compact('exam', 'courses'));
    }

    public function update(UpdateExamRequest $request, Exam $exam)
    {
        $this->examService->updateExam($exam, $request->validated());

        return redirect()->route('admin.exams.show', $exam)
            ->with('success', 'Exam updated successfully.');
    }

    public function destroy(Exam $exam)
    {
        $this->examService->deleteExam($exam);
        return redirect()->route('admin.exams.index')
            ->with('success', 'Exam deleted successfully.');
    }
}
