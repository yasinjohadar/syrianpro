<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'newsletter_email' => 'required|email|unique:newsletter_subscribers,email',
            'source' => 'nullable|string|max:50',
        ], [
            'newsletter_email.required' => 'يرجى إدخال بريدك الإلكتروني.',
            'newsletter_email.email' => 'يرجى إدخال بريد إلكتروني صحيح.',
            'newsletter_email.unique' => 'هذا البريد مسجل مسبقاً في النشرة.',
        ]);

        NewsletterSubscriber::create([
            'email' => $validated['newsletter_email'],
            'source' => $request->input('source', 'general'),
        ]);

        return redirect()->back()
            ->with('newsletter_success', 'تم الاشتراك بنجاح. شكراً لك!');
    }

    public function unsubscribe(string $token)
    {
        $subscriber = NewsletterSubscriber::where('unsubscribe_token', $token)->first();

        if (!$subscriber) {
            return redirect()->route('home')
                ->with('error', 'رابط إلغاء الاشتراك غير صالح أو منتهي الصلاحية.');
        }

        $subscriber->unsubscribe();

        return view('newsletter.unsubscribed');
    }
}
