<?php

namespace App\Services\Admin;

use App\Models\CourseLevel;
use Illuminate\Support\Str;

class CourseLevelService
{
    /**
     * Create a new course level.
     */
    public function createLevel(array $data): CourseLevel
    {
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        return CourseLevel::create($data);
    }

    /**
     * Update an existing course level.
     */
    public function updateLevel(CourseLevel $level, array $data): bool
    {
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        return $level->update($data);
    }

    /**
     * Delete a course level.
     */
    public function deleteLevel(CourseLevel $level): bool
    {
        return $level->delete();
    }
}
