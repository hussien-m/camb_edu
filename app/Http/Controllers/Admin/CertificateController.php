<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    /**
     * Display manual certificates management page
     */
    public function index(Request $request)
    {
        $certificates = Certificate::with(['student', 'course'])
            ->whereNull('exam_attempt_id')
            ->latest('created_at')
            ->paginate(20);

        $stats = [
            'total' => Certificate::whereNull('exam_attempt_id')->count(),
            'active' => Certificate::whereNull('exam_attempt_id')->where('is_active', true)->count(),
            'inactive' => Certificate::whereNull('exam_attempt_id')->where('is_active', false)->count(),
        ];

        return view('admin.certificates.index', compact('certificates', 'stats'));
    }

    /**
     * Store a new manual certificate (AJAX)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_title' => 'required|string|max:500',
            'certificate_file' => 'required|file|mimes:pdf,png,jpg,jpeg|max:10240',
            'transcript_file' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:10240',
            'issue_date' => 'required|date',
            'is_active' => 'nullable|boolean',
        ]);

        try {
            $certificatePath = $request->file('certificate_file')->store('certificates/manual', 'public');
            $transcriptPath = null;
            if ($request->hasFile('transcript_file')) {
                $transcriptPath = $request->file('transcript_file')->store('certificates/transcripts', 'public');
            }

            $certificate = Certificate::create([
                'student_id' => $validated['student_id'],
                'course_id' => null,
                'course_title' => trim($validated['course_title']),
                'exam_attempt_id' => null,
                'certificate_number' => Certificate::generateCertificateNumber(),
                'issue_date' => $validated['issue_date'],
                'certificate_file' => $certificatePath,
                'transcript_file' => $transcriptPath,
                'is_active' => $request->boolean('is_active', true),
            ]);

            $certificate->load(['student', 'course']);

            return response()->json([
                'success' => true,
                'message' => 'Certificate added successfully',
                'certificate' => [
                    'id' => $certificate->id,
                    'certificate_number' => $certificate->certificate_number,
                    'student_name' => $certificate->student->full_name,
                    'course_title' => $certificate->display_course_title,
                    'issue_date' => $certificate->issue_date->format('M d, Y'),
                    'is_active' => $certificate->is_active,
                    'certificate_url' => asset('storage/' . $certificate->certificate_file),
                    'transcript_url' => $certificate->transcript_file ? asset('storage/' . $certificate->transcript_file) : null,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating manual certificate: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while adding the certificate',
            ], 500);
        }
    }

    /**
     * Toggle certificate active status (AJAX)
     */
    public function toggleActive(Certificate $certificate)
    {
        if ($certificate->exam_attempt_id !== null) {
            return response()->json([
                'success' => false,
                'message' => 'Only manual certificates can be toggled',
            ], 403);
        }

        try {
            $certificate->is_active = !$certificate->is_active;
            $certificate->save();

            return response()->json([
                'success' => true,
                'is_active' => $certificate->is_active,
                'message' => $certificate->is_active
                    ? 'Certificate has been enabled'
                    : 'Certificate has been disabled',
            ]);
        } catch (\Exception $e) {
            Log::error('Error toggling certificate: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating',
            ], 500);
        }
    }

    /**
     * Delete a manual certificate (AJAX)
     */
    public function destroy(Certificate $certificate)
    {
        if ($certificate->exam_attempt_id !== null) {
            return response()->json([
                'success' => false,
                'message' => 'Only manual certificates can be deleted',
            ], 403);
        }

        try {
            if ($certificate->certificate_file) {
                Storage::disk('public')->delete($certificate->certificate_file);
            }
            if ($certificate->transcript_file) {
                Storage::disk('public')->delete($certificate->transcript_file);
            }
            $certificate->delete();

            return response()->json([
                'success' => true,
                'message' => 'Certificate deleted successfully',
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting certificate: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting',
            ], 500);
        }
    }

    /**
     * Search students for dropdown (AJAX)
     */
    public function searchStudents(Request $request)
    {
        $q = $request->get('q', '');
        $students = Student::where('status', 'active')
            ->where(function ($query) use ($q) {
                $query->where('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            })
            ->orderBy('first_name')
            ->limit(20)
            ->get(['id', 'first_name', 'last_name', 'email'])
            ->map(fn ($s) => [
                'id' => $s->id,
                'text' => $s->full_name . ' (' . $s->email . ')',
            ]);

        return response()->json(['results' => $students]);
    }

}
