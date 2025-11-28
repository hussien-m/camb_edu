<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateCourseInquiryStatusRequest;
use App\Models\CourseInquiry;
use App\Services\Admin\CourseInquiryService;

class CourseInquiryController extends Controller
{
    protected $inquiryService;

    public function __construct(CourseInquiryService $inquiryService)
    {
        $this->inquiryService = $inquiryService;
    }

    public function index()
    {
        $inquiries = CourseInquiry::with('course')
            ->latest()
            ->paginate(20);

        return view('admin.inquiries.index', compact('inquiries'));
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
