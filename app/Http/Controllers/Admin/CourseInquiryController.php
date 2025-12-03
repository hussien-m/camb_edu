<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateCourseInquiryStatusRequest;
use App\Models\CourseInquiry;
use App\Services\Admin\CourseInquiryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CourseInquiryController extends Controller
{
    protected $inquiryService;

    public function __construct(CourseInquiryService $inquiryService)
    {
        $this->inquiryService = $inquiryService;
    }

    public function index(Request $request)
    {
        try {
            $query = CourseInquiry::with('course');

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%")
                      ->orWhereHas('course', function($courseQuery) use ($search) {
                          $courseQuery->where('title', 'like', "%{$search}%");
                      });
                });
            }

            // Filter by status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            $inquiries = $query->paginate(20)->withQueryString();

        return view('admin.inquiries.index', compact('inquiries'));
        } catch (\Exception $e) {
            Log::error('Error fetching inquiries: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')
                ->with('error', 'An error occurred while loading inquiries. Please try again.');
        }
    }

    public function show(CourseInquiry $inquiry)
    {
        $inquiry->load('course');
        return view('admin.inquiries.show', compact('inquiry'));
    }

    public function updateStatus(UpdateCourseInquiryStatusRequest $request, CourseInquiry $inquiry)
    {
        $this->inquiryService->updateInquiryStatus($inquiry, $request->validated()['status']);

        return redirect()->back()->with('success', 'Inquiry status updated successfully.');
    }

    public function destroy(CourseInquiry $inquiry)
    {
        $this->inquiryService->deleteInquiry($inquiry);
        return redirect()->route('admin.inquiries.index')
            ->with('success', 'Inquiry deleted successfully.');
    }
}
