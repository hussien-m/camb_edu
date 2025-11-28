<?php

namespace App\Services\Admin;

use App\Models\Student;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class StudentService
{
    /**
     * Get filtered and paginated students list.
     */
    public function getStudents(array $filters): LengthAwarePaginator
    {
        $query = Student::query();

        // Search by name or email
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->latest()->paginate(20);
    }

    /**
     * Update a student record.
     */
    public function updateStudent(Student $student, array $data): bool
    {
        return $student->update($data);
    }

    /**
     * Delete a student record.
     */
    public function deleteStudent(Student $student): bool
    {
        return $student->delete();
    }

    /**
     * Update student status.
     */
    public function updateStudentStatus(Student $student, string $status): bool
    {
        return $student->update(['status' => $status]);
    }
}
