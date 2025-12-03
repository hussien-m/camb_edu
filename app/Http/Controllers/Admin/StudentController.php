<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateStudentRequest;
use App\Services\Admin\StudentManagementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    protected $studentService;

    public function __construct(StudentManagementService $studentService)
    {
        $this->studentService = $studentService;
    }

    public function index(Request $request)
    {
        try {
            $students = $this->studentService->getAllStudents($request);
            return view('admin.students.index', compact('students'));
        } catch (\Exception $e) {
            Log::error('Error fetching students: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->route('admin.dashboard')
                ->with('error', 'An error occurred while loading students. Please try again.');
        }
    }

    public function edit($id)
    {
        try {
            $student = $this->studentService->getStudent($id);
            return view('admin.students.edit', compact('student'));
        } catch (\Exception $e) {
            Log::error('Error fetching student: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'student_id' => $id
            ]);
            return redirect()->route('admin.students.index')
                ->with('error', 'Student not found.');
        }
    }

    public function update(UpdateStudentRequest $request, $id)
    {
        try {
            $this->studentService->updateStudent($id, $request->validated());
            return redirect()->route('admin.students.index')->with('success', 'Student updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating student: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'student_id' => $id
            ]);
            return back()->withInput()->with('error', 'Failed to update student. Please try again.');
        }
    }

    public function destroy($id)
    {
        try {
            $this->studentService->deleteStudent($id);
            return redirect()->route('admin.students.index')->with('success', 'Student deleted successfully');
        } catch (\Exception $e) {
            Log::error('Error deleting student: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'student_id' => $id
            ]);
            return back()->with('error', 'Failed to delete student. Please try again.');
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $this->studentService->updateStudentStatus($id, $request->status);
            return redirect()->back()->with('success', 'Student status updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating student status: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'student_id' => $id
            ]);
            return back()->with('error', 'Failed to update student status. Please try again.');
        }
    }
}


