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

    public function index(Request $request)
    {
        try {
            $query = NewsletterSubscriber::query();

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where('email', 'like', "%{$search}%");
            }

            $sortBy = $request->get('sort_by', 'subscribed_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            $subscribers = $query->paginate(50)->withQueryString();
            return view('admin.newsletter.index', compact('subscribers'));
        } catch (\Exception $e) {
            \Log::error('Error fetching subscribers: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')
                ->with('error', 'An error occurred while loading subscribers.');
        }
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
