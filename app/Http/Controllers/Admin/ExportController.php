<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Student;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ExportController extends Controller
{
    /**
     * Export courses to CSV
     */
    public function courses()
    {
        try {
            $courses = Course::with(['category', 'level'])->get();

            $filename = 'courses_' . date('Y-m-d') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ];

            $callback = function() use ($courses) {
                $file = fopen('php://output', 'w');
                
                // Headers
                fputcsv($file, ['ID', 'Title', 'Category', 'Level', 'Duration', 'Fee', 'Status', 'Created At']);
                
                // Data
                foreach ($courses as $course) {
                    fputcsv($file, [
                        $course->id,
                        $course->title,
                        $course->category->name ?? 'N/A',
                        $course->level->name ?? 'N/A',
                        $course->duration ?? 'N/A',
                        $course->fee ?? '0',
                        $course->status,
                        $course->created_at->format('Y-m-d H:i:s'),
                    ]);
                }
                
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            Log::error('Export failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to export courses.');
        }
    }

    /**
     * Export students to CSV
     */
    public function students()
    {
        try {
            $students = Student::all();

            $filename = 'students_' . date('Y-m-d') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ];

            $callback = function() use ($students) {
                $file = fopen('php://output', 'w');
                
                // Headers
                fputcsv($file, ['ID', 'First Name', 'Last Name', 'Email', 'Phone', 'Status', 'Country', 'Created At']);
                
                // Data
                foreach ($students as $student) {
                    fputcsv($file, [
                        $student->id,
                        $student->first_name,
                        $student->last_name,
                        $student->email,
                        $student->phone ?? 'N/A',
                        $student->status,
                        $student->country ?? 'N/A',
                        $student->created_at->format('Y-m-d H:i:s'),
                    ]);
                }
                
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            Log::error('Export failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to export students.');
        }
    }

    /**
     * Export contacts to CSV
     */
    public function contacts()
    {
        try {
            $contacts = Contact::all();

            $filename = 'contacts_' . date('Y-m-d') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ];

            $callback = function() use ($contacts) {
                $file = fopen('php://output', 'w');
                
                // Headers
                fputcsv($file, ['ID', 'Name', 'Email', 'Phone', 'Subject', 'Message', 'Read', 'Created At']);
                
                // Data
                foreach ($contacts as $contact) {
                    fputcsv($file, [
                        $contact->id,
                        $contact->name,
                        $contact->email,
                        $contact->phone ?? 'N/A',
                        $contact->subject,
                        substr($contact->message, 0, 100) . '...',
                        $contact->is_read ? 'Yes' : 'No',
                        $contact->created_at->format('Y-m-d H:i:s'),
                    ]);
                }
                
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            Log::error('Export failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to export contacts.');
        }
    }
}

