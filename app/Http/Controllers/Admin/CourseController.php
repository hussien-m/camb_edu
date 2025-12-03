<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCourseRequest;
use App\Http\Requests\Admin\UpdateCourseRequest;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseLevel;
use App\Services\Admin\CourseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class CourseController extends Controller
{
    protected $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        try {
            $query = Course::with(['category', 'level']);

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Filter by category
            if ($request->filled('category_id')) {
                $query->where('category_id', $request->category_id);
            }

            // Filter by level
            if ($request->filled('level_id')) {
                $query->where('level_id', $request->level_id);
            }

            // Filter by status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            $courses = $query->paginate(15)->withQueryString();
            $categories = CourseCategory::all();
            $levels = CourseLevel::orderBy('sort_order')->get();

            return view('admin.courses.index', compact('courses', 'categories', 'levels'));
        } catch (\Exception $e) {
            Log::error('Error fetching courses: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->route('admin.dashboard')
                ->with('error', 'An error occurred while loading courses. Please try again.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = CourseCategory::all();
        $levels = CourseLevel::orderBy('sort_order')->get();

        return view('admin.courses.create', compact('categories', 'levels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseRequest $request): RedirectResponse
    {
        try {
            $this->courseService->createCourse($request->validated());

            return redirect()
                ->route('admin.courses.index')
                ->with('success', 'Course created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating course: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'data' => $request->except(['password', '_token'])
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to create course. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course): View
    {
        $course->load(['category', 'level']);
        return view('admin.courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course): View
    {
        $categories = CourseCategory::all();
        $levels = CourseLevel::orderBy('sort_order')->get();

        return view('admin.courses.edit', compact('course', 'categories', 'levels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseRequest $request, Course $course): RedirectResponse
    {
        try {
            $this->courseService->updateCourse($course, $request->validated());

            return redirect()
                ->route('admin.courses.index')
                ->with('success', 'Course updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating course: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'course_id' => $course->id
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to update course. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course): RedirectResponse
    {
        try {
            $this->courseService->deleteCourse($course);

            return redirect()
                ->route('admin.courses.index')
                ->with('success', 'Course deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting course: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'course_id' => $course->id
            ]);

            return back()
                ->with('error', 'Failed to delete course. Please try again.');
        }
    }
}
