<?php

namespace App\Services\Admin;

use App\Models\NewsletterSubscriber;

class NewsletterService
{
    /**
     * Delete a newsletter subscriber.
     */
    public function deleteSubscriber(NewsletterSubscriber $subscriber): bool
    {
        return $subscriber->delete();
    }

    /**
     * Get all subscribers for export.
     */
    public function getAllSubscribers()
    {
        return NewsletterSubscriber::all();
    }

    /**
     * Generate CSV data for subscribers.
     */
    public function generateCsvData($subscribers)
    {
        $callback = function() use ($subscribers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Email', 'Subscribed At']);

            foreach ($subscribers as $subscriber) {
                fputcsv($file, [
                    $subscriber->id,
                    $subscriber->email,
                    $subscriber->subscribed_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return $callback;
    }
}
