<?php

namespace App\Services\Admin;

use App\Models\Course;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CourseService
{
    public function createCourse(array $data): Course
    {
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            $data['image'] = $data['image']->store('courses', 'public');
        }
        $data['is_featured'] = isset($data['is_featured']) ? 1 : 0;
        return Course::create($data);
    }

    public function updateCourse(Course $course, array $data): bool
    {
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            if ($course->image) {
                Storage::disk('public')->delete($course->image);
            }
            $data['image'] = $data['image']->store('courses', 'public');
        }
        $data['is_featured'] = isset($data['is_featured']) ? 1 : 0;
        return $course->update($data);
    }

    public function deleteCourse(Course $course): bool
    {
        if ($course->image) {
            Storage::disk('public')->delete($course->image);
        }
        return $course->delete();
    }
}
