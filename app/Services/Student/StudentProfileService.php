<?php

namespace App\Services\Student;

use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StudentProfileService
{
    /**
     * Update student profile
     */
    public function updateProfile($student, array $data)
    {
        $student->first_name = $data['first_name'];
        $student->last_name = $data['last_name'];
        $student->email = $data['email'];
        $student->phone = $data['phone'] ?? null;
        $student->date_of_birth = $data['date_of_birth'] ?? null;
        $student->country = $data['country'] ?? null;

        // Handle profile photo upload
        if (isset($data['profile_photo'])) {
            // Delete old photo if exists
            if ($student->profile_photo) {
                Storage::disk('public')->delete($student->profile_photo);
            }

            $path = $data['profile_photo']->store('students', 'public');
            $student->profile_photo = $path;
        }

        // Update password if provided
        if (isset($data['new_password'])) {
            $student->password = Hash::make($data['new_password']);
        }

        $student->save();

        return $student;
    }

    /**
     * Verify current password
     */
    public function verifyPassword($student, string $password): bool
    {
        return Hash::check($password, $student->password);
    }

    /**
     * Get student profile data
     */
    public function getProfile($student)
    {
        return [
            'id' => $student->id,
            'first_name' => $student->first_name,
            'last_name' => $student->last_name,
            'email' => $student->email,
            'phone' => $student->phone,
            'date_of_birth' => $student->date_of_birth,
            'country' => $student->country,
            'profile_photo' => $student->profile_photo,
            'created_at' => $student->created_at,
            'updated_at' => $student->updated_at,
        ];
    }

    /**
     * Change password
     */
    public function changePassword($student, string $currentPassword, string $newPassword): bool
    {
        if (!$this->verifyPassword($student, $currentPassword)) {
            return false;
        }

        $student->password = Hash::make($newPassword);
        $student->save();

        return true;
    }
}
