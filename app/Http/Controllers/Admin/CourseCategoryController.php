<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCourseCategoryRequest;
use App\Http\Requests\Admin\UpdateCourseCategoryRequest;
use App\Models\CourseCategory;
use App\Services\Admin\CourseCategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class CourseCategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CourseCategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        try {
            $query = CourseCategory::withCount('courses');

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where('name', 'like', "%{$search}%");
            }

            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            $categories = $query->paginate(15)->withQueryString();
            return view('admin.categories.index', compact('categories'));
        } catch (\Exception $e) {
            Log::error('Error fetching categories: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')
                ->with('error', 'An error occurred while loading categories.');
        }
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(StoreCourseCategoryRequest $request): RedirectResponse
    {
        try {
            $this->categoryService->createCategory($request->validated());

            return redirect()
                ->route('admin.categories.index')
                ->with('success', 'Category created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating category: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to create category. Please try again.');
        }
    }

    public function edit(CourseCategory $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(UpdateCourseCategoryRequest $request, CourseCategory $category): RedirectResponse
    {
        try {
            $this->categoryService->updateCategory($category, $request->validated());

            return redirect()
                ->route('admin.categories.index')
                ->with('success', 'Category updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating category: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'category_id' => $category->id
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to update category. Please try again.');
        }
    }

    public function destroy(CourseCategory $category): RedirectResponse
    {
        try {
            $this->categoryService->deleteCategory($category);

            return redirect()
                ->route('admin.categories.index')
                ->with('success', 'Category deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting category: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'category_id' => $category->id
            ]);

            return back()
                ->with('error', 'Failed to delete category. Please try again.');
        }
    }
}
