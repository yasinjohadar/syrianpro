<?php

namespace App\Http\Controllers\Talent;

use App\Http\Controllers\Controller;
use App\Services\TalentJobMatchingService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private TalentJobMatchingService $matchingService
    ) {
        $this->middleware(['auth', 'check.user.active', 'role:talent']);
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $applications = $user->jobApplications()
            ->with('job')
            ->latest()
            ->get();

        $stats = [
            'applications_total' => $applications->count(),
            'applications_pending' => $applications->where('status', 'pending')->count(),
            'applications_accepted' => $applications->where('status', 'accepted')->count(),
        ];

        $talent = $user->talent;
        $publicHiringRequest = $talent?->activePublicHiringRequest;
        $hiringResponsesCount = $talent
            ? $talent->hiringRequests()->whereHas('responses')->withCount('responses')->get()->sum('responses_count')
            : 0;
        $hiresCount = $talent ? $talent->hires()->count() : 0;
        $latestHire = $talent ? $talent->hires()->with('company')->latest('hired_at')->first() : null;
        $matchedJobs = $talent ? $this->matchingService->topJobsForTalent($talent, 5) : collect();

        return view('talents.pages.dashboard.index', [
            'user' => $user,
            'talent' => $talent,
            'recentApplications' => $applications->take(5),
            'stats' => $stats,
            'publicHiringRequest' => $publicHiringRequest,
            'hiringResponsesCount' => $hiringResponsesCount,
            'hiresCount' => $hiresCount,
            'latestHire' => $latestHire,
            'matchedJobs' => $matchedJobs,
            'matchedJobsCount' => $matchedJobs->count(),
            'profileCompletion' => $this->profileCompletion($talent),
            'roleLabel' => 'تقني',
            'shortcuts' => $this->shortcuts($stats['applications_pending'], $hiresCount),
        ]);
    }

    private function profileCompletion(?\App\Models\Talent $talent): int
    {
        if (! $talent) {
            return 0;
        }

        $checks = [
            filled($talent->title),
            filled($talent->bio),
            ! empty($talent->skills),
            filled($talent->avatar_image) || filled($talent->avatar),
            $talent->tech_specialty_id !== null,
            ! empty($talent->experience),
            ! empty($talent->projects) || ! empty($talent->links),
            $talent->rate_min !== null,
        ];

        return (int) round((array_sum($checks) / count($checks)) * 100);
    }

    private function shortcuts(int $pendingApplications, int $hiresCount): array
    {
        return [
            [
                'url' => route('jobs.index'),
                'title' => 'تصفح الوظائف',
                'description' => 'اكتشف فرص remote جديدة',
                'icon' => 'ri-briefcase-line',
                'icon_color' => 'primary',
            ],
            [
                'url' => route('talent.applications.index'),
                'title' => 'طلباتي',
                'description' => 'تابع حالة التقديمات',
                'icon' => 'ri-send-plane-line',
                'icon_color' => 'info',
                'badge' => $pendingApplications > 0 ? (string) $pendingApplications : null,
            ],
            [
                'url' => route('talent.hiring-request.index'),
                'title' => 'طلب التوظيف',
                'description' => 'انشر أنك تبحث عن عمل',
                'icon' => 'ri-megaphone-line',
                'icon_color' => 'warning',
            ],
            [
                'url' => route('talent.hires.index'),
                'title' => 'سجل التوظيف',
                'description' => 'إنجازاتك المهنية',
                'icon' => 'ri-trophy-line',
                'icon_color' => 'success',
                'badge' => $hiresCount > 0 ? (string) $hiresCount : null,
            ],
            [
                'url' => route('talent.profile.edit'),
                'title' => 'ملفي الشخصي',
                'description' => 'حدّث مهاراتك وبياناتك',
                'icon' => 'ri-user-settings-line',
                'icon_color' => 'purple',
            ],
        ];
    }
}
