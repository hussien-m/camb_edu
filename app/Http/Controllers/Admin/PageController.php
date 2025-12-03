<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePageRequest;
use App\Http\Requests\Admin\UpdatePageRequest;
use App\Models\Page;
use App\Services\Admin\PageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PageController extends Controller
{
    protected $pageService;

    public function __construct(PageService $pageService)
    {
        $this->pageService = $pageService;
    }

    public function index(Request $request): View
    {
        try {
            $query = Page::query();

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('slug', 'like', "%{$search}%")
                      ->orWhere('content', 'like', "%{$search}%");
                });
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            $pages = $query->paginate(15)->withQueryString();
            return view('admin.pages.index', compact('pages'));
        } catch (\Exception $e) {
            Log::error('Error fetching pages: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->route('admin.dashboard')
                ->with('error', 'An error occurred while loading pages. Please try again.');
        }
    }

    public function create(): View
    {
        return view('admin.pages.create');
    }

    public function store(StorePageRequest $request): RedirectResponse
    {
        try {
            $this->pageService->createPage($request->validated());

            return redirect()
                ->route('admin.pages.index')
                ->with('success', 'Page created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating page: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to create page. Please try again.');
        }
    }

    public function edit(Page $page): View
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(UpdatePageRequest $request, Page $page): RedirectResponse
    {
        try {
            $this->pageService->updatePage($page, $request->validated());

            return redirect()
                ->route('admin.pages.index')
                ->with('success', 'Page updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating page: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'page_id' => $page->id
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to update page. Please try again.');
        }
    }

    public function destroy(Page $page): RedirectResponse
    {
        try {
            $this->pageService->deletePage($page);

            return redirect()
                ->route('admin.pages.index')
                ->with('success', 'Page deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting page: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'page_id' => $page->id
            ]);

            return back()
                ->with('error', 'Failed to delete page. Please try again.');
        }
    }
}
