<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseInquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CourseInquiryController extends Controller
{
    public function store(Request $request, $courseId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string|max:1000',
        ]);

        $course = Course::findOrFail($courseId);

        CourseInquiry::create([
            'course_id' => $course->id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message,
            'status' => 'new',
        ]);

        // Clear cache after creating new inquiry
        Cache::forget('admin.new_inquiries');

        // Check if it's an AJAX request
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you for your inquiry! We will contact you soon.'
            ]);
        }

        return redirect()->back()->with('success', 'Thank you for your inquiry! We will contact you soon.');
    }
}
