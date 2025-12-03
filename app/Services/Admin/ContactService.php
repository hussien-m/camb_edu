<?php

namespace App\Services\Admin;

use App\Models\Contact;
use Illuminate\Support\Facades\Cache;

class ContactService
{
    /**
     * Get filtered contacts.
     */
    public function getFilteredContacts(array $filters)
    {
        $query = Contact::query();

        // Filter by read/unread status
        if (!empty($filters['status'])) {
            if ($filters['status'] === 'unread') {
                $query->where('is_read', false);
            } elseif ($filters['status'] === 'read') {
                $query->where('is_read', true);
            }
        }

        return $query->latest()->paginate(15);
    }

    /**
     * Mark a contact as read.
     */
    public function markAsRead(Contact $contact): bool
    {
        if (!$contact->is_read) {
            $contact->markAsRead();
            // Clear cache after marking as read
            Cache::forget('admin.unread_messages');
        }
        return true;
    }

    /**
     * Delete a contact.
     */
    public function deleteContact(Contact $contact): bool
    {
        $result = $contact->delete();
        // Clear cache after deletion
        Cache::forget('admin.unread_messages');
        return $result;
    }
}
