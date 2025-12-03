<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCourseLevelRequest;
use App\Http\Requests\Admin\UpdateCourseLevelRequest;
use App\Models\CourseLevel;
use App\Services\Admin\CourseLevelService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class CourseLevelController extends Controller
{
    protected $levelService;

    public function __construct(CourseLevelService $levelService)
    {
        $this->levelService = $levelService;
    }

    public function index(Request $request)
    {
        try {
            $query = CourseLevel::withCount('courses');

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where('name', 'like', "%{$search}%");
            }

            $sortBy = $request->get('sort_by', 'sort_order');
            $sortOrder = $request->get('sort_order', 'asc');
            
            // Validate sort_by to prevent SQL injection
            $allowedSortFields = ['sort_order', 'created_at', 'name', 'id'];
            if (!in_array($sortBy, $allowedSortFields)) {
                $sortBy = 'sort_order';
            }
            
            $query->orderBy($sortBy, $sortOrder);

            $levels = $query->paginate(15)->withQueryString();
            return view('admin.levels.index', compact('levels'));
        } catch (\Exception $e) {
            Log::error('Error fetching levels: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')
                ->with('error', 'An error occurred while loading levels.');
        }
    }

    public function create(): View
    {
        return view('admin.levels.create');
    }

    public function store(StoreCourseLevelRequest $request): RedirectResponse
    {
        try {
            $this->levelService->createLevel($request->validated());

            return redirect()
                ->route('admin.levels.index')
                ->with('success', 'Level created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating level: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to create level. Please try again.');
        }
    }

    public function edit(CourseLevel $level): View
    {
        return view('admin.levels.edit', compact('level'));
    }

    public function update(UpdateCourseLevelRequest $request, CourseLevel $level): RedirectResponse
    {
        try {
            $this->levelService->updateLevel($level, $request->validated());

            return redirect()
                ->route('admin.levels.index')
                ->with('success', 'Level updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating level: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'level_id' => $level->id
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to update level. Please try again.');
        }
    }

    public function destroy(CourseLevel $level): RedirectResponse
    {
        try {
            $this->levelService->deleteLevel($level);

            return redirect()
                ->route('admin.levels.index')
                ->with('success', 'Level deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting level: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'level_id' => $level->id
            ]);

            return back()
                ->with('error', 'Failed to delete level. Please try again.');
        }
    }
}
