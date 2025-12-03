<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Contact;
use App\Models\Student;
use App\Services\Admin\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BulkActionController extends Controller
{
    /**
     * Handle bulk actions for courses
     */
    public function courses(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,activate,deactivate',
            'ids' => 'required|string', // JSON string
        ]);

        $ids = json_decode($request->ids, true);
        if (!is_array($ids) || empty($ids)) {
            return redirect()->back()->with('error', 'No items selected.');
        }

        try {
            $courses = Course::whereIn('id', $ids);
            $count = $courses->count();

            $courseService = new CourseService();
            
            switch ($request->action) {
                case 'delete':
                    $courses->each(function($course) use ($courseService) {
                        $courseService->deleteCourse($course);
                    });
                    $message = "{$count} course(s) deleted successfully.";
                    break;

                case 'activate':
                    $courses->update(['status' => 'active']);
                    $message = "{$count} course(s) activated successfully.";
                    break;

                case 'deactivate':
                    $courses->update(['status' => 'inactive']);
                    $message = "{$count} course(s) deactivated successfully.";
                    break;
            }

            ActivityLogService::log('bulk_action', 'Course', null, [
                'action' => $request->action,
                'count' => $count
            ]);

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Bulk action failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to perform bulk action. Please try again.');
        }
    }

    /**
     * Handle bulk actions for students
     */
    public function students(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,activate,pending',
            'ids' => 'required|string', // JSON string
        ]);

        $ids = json_decode($request->ids, true);
        if (!is_array($ids) || empty($ids)) {
            return redirect()->back()->with('error', 'No items selected.');
        }

        try {
            $students = Student::whereIn('id', $ids);
            $count = $students->count();

            switch ($request->action) {
                case 'delete':
                    $students->delete();
                    $message = "{$count} student(s) deleted successfully.";
                    break;

                case 'activate':
                    $students->update(['status' => 'active']);
                    \Illuminate\Support\Facades\Cache::forget('admin.pending_students');
                    $message = "{$count} student(s) activated successfully.";
                    break;

                case 'pending':
                    $students->update(['status' => 'pending']);
                    \Illuminate\Support\Facades\Cache::forget('admin.pending_students');
                    $message = "{$count} student(s) set to pending successfully.";
                    break;
            }

            ActivityLogService::log('bulk_action', 'Student', null, [
                'action' => $request->action,
                'count' => $count
            ]);

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Bulk action failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to perform bulk action. Please try again.');
        }
    }

    /**
     * Handle bulk actions for contacts
     */
    public function contacts(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,mark_read,mark_unread',
            'ids' => 'required|string', // JSON string
        ]);

        $ids = json_decode($request->ids, true);
        if (!is_array($ids) || empty($ids)) {
            return redirect()->back()->with('error', 'No items selected.');
        }

        try {
            $contacts = Contact::whereIn('id', $ids);
            $count = $contacts->count();

            switch ($request->action) {
                case 'delete':
                    $contacts->delete();
                    \Illuminate\Support\Facades\Cache::forget('admin.unread_messages');
                    $message = "{$count} contact(s) deleted successfully.";
                    break;

                case 'mark_read':
                    $contacts->update(['is_read' => true]);
                    \Illuminate\Support\Facades\Cache::forget('admin.unread_messages');
                    $message = "{$count} contact(s) marked as read.";
                    break;

                case 'mark_unread':
                    $contacts->update(['is_read' => false]);
                    \Illuminate\Support\Facades\Cache::forget('admin.unread_messages');
                    $message = "{$count} contact(s) marked as unread.";
                    break;
            }

            ActivityLogService::log('bulk_action', 'Contact', null, [
                'action' => $request->action,
                'count' => $count
            ]);

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Bulk action failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to perform bulk action. Please try again.');
        }
    }
}

