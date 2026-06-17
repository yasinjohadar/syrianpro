<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\Talent;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'check.user.active', 'role:company']);
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $company = $user->company;

        $stats = [
            'active_jobs' => 0,
            'applications_total' => 0,
            'applications_pending' => 0,
            'talents_pool' => Talent::active()->count(),
            'hires_total' => 0,
        ];

        $recentApplications = collect();
        $activeJobs = collect();

        if ($company) {
            $jobIds = $company->jobs()->pluck('id');

            $stats['active_jobs'] = $company->jobs()->active()->count();
            $stats['applications_total'] = JobApplication::query()
                ->whereIn('job_listing_id', $jobIds)
                ->count();
            $stats['applications_pending'] = JobApplication::query()
                ->whereIn('job_listing_id', $jobIds)
                ->pending()
                ->count();
            $recentApplications = JobApplication::query()
                ->with(['user', 'job'])
                ->whereIn('job_listing_id', $jobIds)
                ->latest()
                ->take(5)
                ->get();
            $activeJobs = $company->jobs()
                ->active()
                ->latest('published_at')
                ->take(5)
                ->get();
            $stats['hires_total'] = $company->hires()->count();
        }

        return view('company.pages.dashboard.index', [
            'user' => $user,
            'company' => $company,
            'stats' => $stats,
            'recentApplications' => $recentApplications,
            'activeJobs' => $activeJobs,
            'roleLabel' => 'شركة',
            'shortcuts' => $this->shortcuts(),
        ]);
    }

    private function shortcuts(): array
    {
        return [
            [
                'url' => route('company.jobs.create'),
                'title' => 'أضف وظيفة',
                'description' => 'انشر فرصة remote جديدة',
                'icon' => 'ri-add-circle-line',
                'icon_color' => 'primary',
            ],
            [
                'url' => route('company.jobs.index'),
                'title' => 'وظائفي',
                'description' => 'إدارة الوظائف المنشورة',
                'icon' => 'ri-briefcase-line',
                'icon_color' => 'warning',
            ],
            [
                'url' => route('company.applications.index'),
                'title' => 'المتقدمون',
                'description' => 'مراجعة طلبات التوظيف',
                'icon' => 'ri-team-line',
                'icon_color' => 'info',
            ],
            [
                'url' => route('company.talents.index'),
                'title' => 'قاعدة المواهب',
                'description' => 'تصفح التقنيين السوريين',
                'icon' => 'ri-user-star-line',
                'icon_color' => 'success',
            ],
            [
                'url' => route('company.profile.edit'),
                'title' => 'ملف الشركة',
                'description' => 'تحديث بيانات شركتك',
                'icon' => 'ri-building-2-line',
                'icon_color' => 'secondary',
            ],
        ];
    }
}
