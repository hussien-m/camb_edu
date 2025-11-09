<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseLevel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $courses = Course::with(['category', 'level'])
            ->latest()
            ->paginate(15);

        return view('admin.courses.index', compact('courses'));
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
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:courses,slug',
            'category_id' => 'nullable|exists:course_categories,id',
            'level_id' => 'nullable|exists:course_levels,id',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'duration' => 'nullable|string|max:100',
            'mode' => 'nullable|string|max:50',
            'fee' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('courses', 'public');
        }

        // Handle is_featured checkbox
        $validated['is_featured'] = $request->has('is_featured') ? 1 : 0;

        Course::create($validated);

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Course created successfully.');
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
    public function update(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:courses,slug,' . $course->id,
            'category_id' => 'nullable|exists:course_categories,id',
            'level_id' => 'nullable|exists:course_levels,id',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'duration' => 'nullable|string|max:100',
            'mode' => 'nullable|string|max:50',
            'fee' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($course->image) {
                Storage::disk('public')->delete($course->image);
            }
            $validated['image'] = $request->file('image')->store('courses', 'public');
        }

        // Handle is_featured checkbox
        $validated['is_featured'] = $request->has('is_featured') ? 1 : 0;

        $course->update($validated);

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Course updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course): RedirectResponse
    {
        // Delete image if exists
        if ($course->image) {
            Storage::disk('public')->delete($course->image);
        }

        $course->delete();

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Course deleted successfully.');
    }
}
