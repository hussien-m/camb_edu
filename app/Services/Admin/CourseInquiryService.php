<?php

namespace App\Services\Admin;

use App\Models\CourseInquiry;

class CourseInquiryService
{
    /**
     * Update inquiry status.
     */
    public function updateInquiryStatus(CourseInquiry $inquiry, string $status): bool
    {
        return $inquiry->update(['status' => $status]);
    }

    /**
     * Delete an inquiry.
     */
    public function deleteInquiry(CourseInquiry $inquiry): bool
    {
        return $inquiry->delete();
    }
}
