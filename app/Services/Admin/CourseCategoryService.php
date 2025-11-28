<?php

namespace App\Services\Admin;

use App\Models\CourseCategory;
use Illuminate\Support\Str;

class CourseCategoryService
{
    /**
     * Create a new course category.
     */
    public function createCategory(array $data): CourseCategory
    {
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        return CourseCategory::create($data);
    }

    /**
     * Update an existing course category.
     */
    public function updateCategory(CourseCategory $category, array $data): bool
    {
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        return $category->update($data);
    }

    /**
     * Delete a course category.
     */
    public function deleteCategory(CourseCategory $category): bool
    {
        return $category->delete();
    }
}
