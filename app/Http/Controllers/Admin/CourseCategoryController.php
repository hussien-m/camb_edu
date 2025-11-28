<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCourseCategoryRequest;
use App\Http\Requests\Admin\UpdateCourseCategoryRequest;
use App\Models\CourseCategory;
use App\Services\Admin\CourseCategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CourseCategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CourseCategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(): View
    {
        $categories = CourseCategory::withCount('courses')->latest()->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(StoreCourseCategoryRequest $request): RedirectResponse
    {
        $this->categoryService->createCategory($request->validated());

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(CourseCategory $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(UpdateCourseCategoryRequest $request, CourseCategory $category): RedirectResponse
    {
        $this->categoryService->updateCategory($category, $request->validated());

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(CourseCategory $category): RedirectResponse
    {
        $this->categoryService->deleteCategory($category);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
