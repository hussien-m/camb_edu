<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamStudentAssignment;
use App\Models\Student;
use App\Notifications\ExamAssignedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamAssignmentController extends Controller
{
    public function searchStudents(Request $request)
    {
        $query = trim((string) $request->get('q', ''));
        if ($query === '') {
            return response()->json(['data' => []]);
        }

        $students = Student::query()
            ->whereNotNull('email_verified_at')
            ->where(function ($q) use ($query) {
                $q->where('email', 'like', "%{$query}%")
                    ->orWhere('first_name', 'like', "%{$query}%")
                    ->orWhere('last_name', 'like', "%{$query}%");
            })
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->limit(25)
            ->get(['id', 'first_name', 'last_name', 'email']);

        $data = $students->map(function ($student) {
            return [
                'id' => $student->id,
                'name' => $student->full_name,
                'email' => $student->email,
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function store(Request $request, Exam $exam)
    {
        if (!$exam->group_assignment_enabled) {
            return redirect()->route('admin.exams.show', $exam)
                ->with('error', 'Group assignment is disabled for this exam.');
        }

        $data = $request->validate([
            'mode' => 'required|in:open,scheduled',
            'starts_at' => 'nullable|required_if:mode,scheduled|date',
            'ends_at' => 'nullable|date|after:starts_at',
            'emails' => 'nullable|string',
            'csv_file' => 'nullable|file|mimes:csv,txt',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'integer|exists:students,id',
        ]);

        $rawEmails = trim((string) ($data['emails'] ?? ''));
        $emailList = [];
        if ($rawEmails !== '') {
            $emailList = preg_split('/[\s,;]+/', $rawEmails) ?: [];
            $emailList = array_filter(array_map('strtolower', array_map('trim', $emailList)));
            $emailList = array_values(array_unique($emailList));
        }

        if ($request->hasFile('csv_file')) {
            $csvContents = file_get_contents($request->file('csv_file')->getRealPath());
            $csvEmails = preg_split('/[\s,;]+/', (string) $csvContents) ?: [];
            $csvEmails = array_filter(array_map('strtolower', array_map('trim', $csvEmails)));
            $emailList = array_values(array_unique(array_merge($emailList, $csvEmails)));
        }

        $studentIds = $data['student_ids'] ?? [];
        $studentIds = array_values(array_unique(array_filter($studentIds)));

        if (empty($emailList) && empty($studentIds)) {
            return redirect()->route('admin.exams.show', $exam)
                ->with('error', 'Please provide at least one email or select students.');
        }

        $studentsByEmail = empty($emailList)
            ? collect()
            : Student::whereIn('email', $emailList)
                ->whereNotNull('email_verified_at')
                ->get();

        $studentsById = empty($studentIds)
            ? collect()
            : Student::whereIn('id', $studentIds)
                ->whereNotNull('email_verified_at')
                ->get();

        $students = $studentsByEmail->merge($studentsById)->unique('id')->values();

        $foundEmails = $studentsByEmail->pluck('email')->map(fn ($email) => strtolower($email))->all();
        $skippedEmails = array_values(array_diff($emailList, $foundEmails));

        $foundIds = $studentsById->pluck('id')->all();
        $skippedIds = array_values(array_diff($studentIds, $foundIds));

        if ($students->isEmpty()) {
            return redirect()->route('admin.exams.show', $exam)
                ->with('error', 'No verified students were found for the provided inputs.');
        }

        $admin = Auth::guard('admin')->user();
        $assignedCount = 0;

        foreach ($students as $student) {
            $assignment = ExamStudentAssignment::create([
                'exam_id' => $exam->id,
                'student_id' => $student->id,
                'assigned_by_admin_id' => $admin?->id,
                'mode' => $data['mode'],
                'starts_at' => $data['mode'] === 'scheduled' ? $data['starts_at'] : null,
                'ends_at' => $data['mode'] === 'scheduled' ? $data['ends_at'] : null,
                'assigned_at' => now(),
                'status' => 'assigned',
            ]);

            $student->notify(new ExamAssignedNotification($exam, $assignment));
            $assignedCount++;
        }

        $message = "Assigned {$assignedCount} student(s) successfully.";
        if (!empty($skippedEmails)) {
            $message .= ' Skipped emails: ' . implode(', ', $skippedEmails) . '.';
        }
        if (!empty($skippedIds)) {
            $message .= ' Skipped student IDs: ' . implode(', ', $skippedIds) . '.';
        }

        return redirect()->route('admin.exams.show', $exam)->with('success', $message);
    }

    public function destroy(Exam $exam, ExamStudentAssignment $assignment)
    {
        if ($assignment->exam_id !== $exam->id) {
            abort(404);
        }

        $assignment->delete();

        return redirect()->route('admin.exams.show', $exam)
            ->with('success', 'Assignment removed successfully.');
    }
}
