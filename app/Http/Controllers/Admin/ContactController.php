<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(Request $request): View
    {
        $query = Contact::query();

        // Filter by read/unread
        if ($request->has('status')) {
            if ($request->status === 'unread') {
                $query->where('is_read', false);
            } elseif ($request->status === 'read') {
                $query->where('is_read', true);
            }
        }

        $contacts = $query->latest()->paginate(15);

        return view('admin.contacts.index', compact('contacts'));
    }

    public function show(Contact $contact): View
    {
        // Mark as read when viewed
        if (!$contact->is_read) {
            $contact->markAsRead();
        }

        return view('admin.contacts.show', compact('contact'));
    }

    public function markAsRead(Contact $contact): RedirectResponse
    {
        $contact->markAsRead();

        return back()->with('success', 'Message marked as read.');
    }

    public function destroy(Contact $contact): RedirectResponse
    {
        $contact->delete();

        return redirect()
            ->route('admin.contacts.index')
            ->with('success', 'Message deleted successfully.');
    }
}
