<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateStudentRequest;
use App\Services\Admin\StudentManagementService;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    protected $studentService;

    public function __construct(StudentManagementService $studentService)
    {
        $this->studentService = $studentService;
    }

    public function index(Request $request)
    {
        $students = $this->studentService->getAllStudents($request);
        return view('admin.students.index', compact('students'));
    }

    public function edit($id)
    {
        $student = $this->studentService->getStudent($id);
        return view('admin.students.edit', compact('student'));
    }

    public function update(UpdateStudentRequest $request, $id)
    {
        $this->studentService->updateStudent($id, $request->validated());
        return redirect()->route('admin.students.index')->with('success', 'Student updated successfully');
    }

    public function destroy($id)
    {
        $this->studentService->deleteStudent($id);
        return redirect()->route('admin.students.index')->with('success', 'Student deleted successfully');
    }

    public function updateStatus(Request $request, $id)
    {
        $this->studentService->updateStudentStatus($id, $request->status);
        return redirect()->back()->with('success', 'Student status updated successfully');
    }
}


