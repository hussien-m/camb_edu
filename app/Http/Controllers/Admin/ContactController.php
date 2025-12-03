<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Services\Admin\ContactService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ContactController extends Controller
{
    protected $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    public function index(Request $request): View
    {
        try {
            $query = Contact::query();

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('subject', 'like', "%{$search}%")
                      ->orWhere('message', 'like', "%{$search}%");
                });
            }

            // Filter by read/unread
            if ($request->has('status')) {
                if ($request->status === 'unread') {
                    $query->where('is_read', false);
                } elseif ($request->status === 'read') {
                    $query->where('is_read', true);
                }
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            $contacts = $query->paginate(15)->withQueryString();

            return view('admin.contacts.index', compact('contacts'));
        } catch (\Exception $e) {
            Log::error('Error fetching contacts: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->route('admin.dashboard')
                ->with('error', 'An error occurred while loading contacts. Please try again.');
        }
    }

    public function show(Contact $contact): View
    {
        // Mark as read when viewed
        if (!$contact->is_read) {
            $this->contactService->markAsRead($contact);
        }

        return view('admin.contacts.show', compact('contact'));
    }

    public function markAsRead(Contact $contact): RedirectResponse
    {
        try {
            $this->contactService->markAsRead($contact);

            return back()->with('success', 'Message marked as read.');
        } catch (\Exception $e) {
            Log::error('Error marking contact as read: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'contact_id' => $contact->id
            ]);

            return back()->with('error', 'Failed to mark message as read. Please try again.');
        }
    }

    public function destroy(Contact $contact): RedirectResponse
    {
        try {
            $this->contactService->deleteContact($contact);

            return redirect()
                ->route('admin.contacts.index')
                ->with('success', 'Message deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting contact: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'contact_id' => $contact->id
            ]);

            return back()->with('error', 'Failed to delete message. Please try again.');
        }
    }
}
