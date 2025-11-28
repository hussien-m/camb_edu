<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCourseLevelRequest;
use App\Http\Requests\Admin\UpdateCourseLevelRequest;
use App\Models\CourseLevel;
use App\Services\Admin\CourseLevelService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CourseLevelController extends Controller
{
    protected $levelService;

    public function __construct(CourseLevelService $levelService)
    {
        $this->levelService = $levelService;
    }

    public function index(): View
    {
        $levels = CourseLevel::withCount('courses')->orderBy('sort_order')->paginate(15);
        return view('admin.levels.index', compact('levels'));
    }

    public function create(): View
    {
        return view('admin.levels.create');
    }

    public function store(StoreCourseLevelRequest $request): RedirectResponse
    {
        $this->levelService->createLevel($request->validated());

        return redirect()
            ->route('admin.levels.index')
            ->with('success', 'Level created successfully.');
    }

    public function edit(CourseLevel $level): View
    {
        return view('admin.levels.edit', compact('level'));
    }

    public function update(UpdateCourseLevelRequest $request, CourseLevel $level): RedirectResponse
    {
        $this->levelService->updateLevel($level, $request->validated());

        return redirect()
            ->route('admin.levels.index')
            ->with('success', 'Level updated successfully.');
    }

    public function destroy(CourseLevel $level): RedirectResponse
    {
        $this->levelService->deleteLevel($level);

        return redirect()
            ->route('admin.levels.index')
            ->with('success', 'Level deleted successfully.');
    }
}
