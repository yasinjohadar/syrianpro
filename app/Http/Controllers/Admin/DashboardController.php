<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\ContactMessage;
use App\Models\ConsultationRequest;
use App\Models\NewsletterSubscriber;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $onlineThreshold = now()->subMinutes(5)->timestamp;

        $onlineCount = DB::table('sessions')
            ->where('last_activity', '>=', $onlineThreshold)
            ->whereNotNull('user_id')
            ->distinct()
            ->count('user_id');

        $blogTotal = BlogPost::count();
        $blogPublished = BlogPost::published()->count();

        $contactTotal = ContactMessage::count();
        $contactUnread = ContactMessage::unread()->count();

        $newsletterActive = NewsletterSubscriber::active()->count();
        $newsletterToday = NewsletterSubscriber::active()
            ->whereDate('subscribed_at', today())
            ->count();

        $consultationUnread = ConsultationRequest::unread()->count();

        $badges = [
            'contact_unread' => $contactUnread,
            'consultation_unread' => $consultationUnread,
        ];

        $user = auth()->user();
        $roleLabel = $user->roles->first()?->name ?? 'مدير';

        return view('admin.dashboard', [
            'roleLabel' => $roleLabel,
            'stats' => [
                'users_total' => User::count(),
                'users_online' => $onlineCount,
                'blog_published' => $blogPublished,
                'blog_total' => $blogTotal,
                'contact_total' => $contactTotal,
                'contact_unread' => $contactUnread,
                'newsletter_active' => $newsletterActive,
                'newsletter_today' => $newsletterToday,
            ],
            'shortcuts' => $this->resolveShortcuts($badges),
        ]);
    }

    private function resolveShortcuts(array $badges): array
    {
        $shortcuts = [];

        foreach (config('admin-dashboard.shortcuts', []) as $item) {
            if (! empty($item['permission']) && ! auth()->user()->can($item['permission'])) {
                continue;
            }

            if (! empty($item['route']) && ! \Route::has($item['route'])) {
                continue;
            }

            $badge = null;
            if (! empty($item['badge_key']) && isset($badges[$item['badge_key']]) && $badges[$item['badge_key']] > 0) {
                $badge = $badges[$item['badge_key']];
            }

            $shortcuts[] = array_merge($item, [
                'url' => route($item['route']),
                'badge' => $badge,
            ]);
        }

        return $shortcuts;
    }
}
