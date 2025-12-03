<?php

namespace App\Services\Admin;

use App\Models\CourseInquiry;
use Illuminate\Support\Facades\Cache;

class CourseInquiryService
{
    /**
     * Update inquiry status.
     */
    public function updateInquiryStatus(CourseInquiry $inquiry, string $status): bool
    {
        $result = $inquiry->update(['status' => $status]);
        // Clear cache after status update
        Cache::forget('admin.new_inquiries');
        return $result;
    }

    /**
     * Delete an inquiry.
     */
    public function deleteInquiry(CourseInquiry $inquiry): bool
    {
        $result = $inquiry->delete();
        // Clear cache after deletion
        Cache::forget('admin.new_inquiries');
        return $result;
    }
}
