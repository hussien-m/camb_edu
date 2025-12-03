<?php

namespace App\Services\Admin;

use App\Models\Course;
use App\Services\Admin\ActivityLogService;
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
        $course = Course::create($data);

        ActivityLogService::log('created', 'Course', $course->id, ['title' => $course->title]);

        return $course;
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
        $result = $course->update($data);

        if ($result) {
            ActivityLogService::log('updated', 'Course', $course->id, ['title' => $course->title]);
        }

        return $result;
    }

    public function deleteCourse(Course $course): bool
    {
        $courseTitle = $course->title;
        $courseId = $course->id;

        if ($course->image) {
            Storage::disk('public')->delete($course->image);
        }

        $result = $course->delete();

        if ($result) {
            ActivityLogService::log('deleted', 'Course', $courseId, ['title' => $courseTitle]);
        }

        return $result;
    }
}
