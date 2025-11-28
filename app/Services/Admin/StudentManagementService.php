<?php

namespace App\Services\Admin;

use App\Models\Student;

class StudentManagementService
{
    /**
     * Get all students with search and filters
     */
    public function getAllStudents($request)
    {
        $query = Student::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return $query->latest()->paginate(20);
    }

    /**
     * Get student by ID
     */
    public function getStudent($id)
    {
        return Student::findOrFail($id);
    }

    /**
     * Update student
     */
    public function updateStudent($id, $data)
    {
        $student = Student::findOrFail($id);
        $student->update($data);
        return $student;
    }

    /**
     * Delete student
     */
    public function deleteStudent($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();
        return true;
    }

    /**
     * Update student status
     */
    public function updateStudentStatus($id, $status)
    {
        $student = Student::findOrFail($id);
        $student->update(['status' => $status]);
        return $student;
    }
}
