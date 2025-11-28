<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use App\Services\Admin\NewsletterService;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    protected $newsletterService;

    public function __construct(NewsletterService $newsletterService)
    {
        $this->newsletterService = $newsletterService;
    }

    public function index()
    {
        $subscribers = NewsletterSubscriber::latest('subscribed_at')->paginate(50);
        return view('admin.newsletter.index', compact('subscribers'));
    }

    public function destroy($id)
    {
        $subscriber = NewsletterSubscriber::findOrFail($id);
        $this->newsletterService->deleteSubscriber($subscriber);

        return redirect()->route('admin.newsletter.index')
            ->with('success', 'Subscriber deleted successfully');
    }

    public function export()
    {
        $subscribers = $this->newsletterService->getAllSubscribers();

        $filename = 'newsletter_subscribers_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = $this->newsletterService->generateCsvData($subscribers);

        return response()->stream($callback, 200, $headers);
    }
}
