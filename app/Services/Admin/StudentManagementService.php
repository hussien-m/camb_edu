<?php

namespace App\Services\Admin;

use App\Models\Student;
use Illuminate\Support\Facades\Cache;

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
        $oldStatus = $student->status;
        $student->update($data);

        // Clear cache if status changed
        if (isset($data['status']) && $data['status'] !== $oldStatus) {
            Cache::forget('admin.pending_students');
        }

        return $student;
    }

    /**
     * Delete student
     */
    public function deleteStudent($id)
    {
        $student = Student::findOrFail($id);
        $wasPending = $student->status === 'pending';
        $student->delete();

        // Clear cache if deleted student was pending
        if ($wasPending) {
            Cache::forget('admin.pending_students');
        }

        return true;
    }

    /**
     * Update student status
     */
    public function updateStudentStatus($id, $status)
    {
        $student = Student::findOrFail($id);
        $oldStatus = $student->status;
        $student->update(['status' => $status]);

        // Clear cache if status changed to/from pending
        if ($oldStatus === 'pending' || $status === 'pending') {
            Cache::forget('admin.pending_students');
        }

        return $student;
    }
}
