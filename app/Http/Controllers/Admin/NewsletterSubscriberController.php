<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NewsletterSubscriberController extends Controller
{
    public function index(Request $request)
    {
        $query = NewsletterSubscriber::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('email', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $filteredCount = (clone $query)->count();

        $subscribers = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        $stats = [
            'total' => NewsletterSubscriber::count(),
            'active' => NewsletterSubscriber::active()->count(),
            'this_month' => NewsletterSubscriber::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'filtered' => $filteredCount,
        ];

        return view('admin.newsletter-subscribers.index', compact('subscribers', 'stats'));
    }

    public function destroy(NewsletterSubscriber $newsletterSubscriber)
    {
        $newsletterSubscriber->delete();

        return redirect()->route('admin.newsletter-subscribers.index')
            ->with('success', 'تم حذف المشترك بنجاح.');
    }

    public function export(Request $request): StreamedResponse
    {
        $query = NewsletterSubscriber::active();

        if ($request->filled('search')) {
            $query->where('email', 'like', "%{$request->search}%");
        }

        $subscribers = $query->orderBy('email')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="newsletter-subscribers-' . date('Y-m-d') . '.csv"',
        ];

        return response()->stream(function () use ($subscribers) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['البريد الإلكتروني', 'المصدر', 'تاريخ الاشتراك'], ',');
            foreach ($subscribers as $s) {
                fputcsv($handle, [
                    $s->email,
                    NewsletterSubscriber::sourceLabel($s->source ?? ''),
                    $s->subscribed_at?->format('Y-m-d H:i') ?? $s->created_at->format('Y-m-d H:i'),
                ], ',');
            }
            fclose($handle);
        }, 200, $headers);
    }
}
