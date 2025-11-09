<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseLevel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CourseLevelController extends Controller
{
    public function index(): View
    {
        $levels = CourseLevel::withCount('courses')->orderBy('sort_order')->paginate(15);
        return view('admin.levels.index', compact('levels'));
    }

    public function create(): View
    {
        return view('admin.levels.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:course_levels,slug',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        CourseLevel::create($validated);

        return redirect()
            ->route('admin.levels.index')
            ->with('success', 'Level created successfully.');
    }

    public function edit(CourseLevel $level): View
    {
        return view('admin.levels.edit', compact('level'));
    }

    public function update(Request $request, CourseLevel $level): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:course_levels,slug,' . $level->id,
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $level->update($validated);

        return redirect()
            ->route('admin.levels.index')
            ->with('success', 'Level updated successfully.');
    }

    public function destroy(CourseLevel $level): RedirectResponse
    {
        $level->delete();

        return redirect()
            ->route('admin.levels.index')
            ->with('success', 'Level deleted successfully.');
    }
}
