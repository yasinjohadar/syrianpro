<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\TechSpecialty;
use App\Notifications\JobMatchNotification;
use App\Services\JobListingService;
use App\Services\TalentJobMatchingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class JobController extends Controller
{
    public function __construct(
        private JobListingService $jobListingService,
        private TalentJobMatchingService $matchingService
    ) {
        $this->middleware(['auth', 'check.user.active', 'role:company']);
    }

    public function index(Request $request): View
    {
        $company = $request->user()->company;

        if (! $company) {
            return view('company.pages.jobs.index', [
                'company' => null,
                'jobs' => Job::query()->whereRaw('1 = 0')->paginate(15),
                'stats' => ['total' => 0, 'active' => 0, 'inactive' => 0, 'applications' => 0],
            ]);
        }

        $baseQuery = $company->jobs();

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'active' => (clone $baseQuery)->where('is_active', true)->count(),
            'inactive' => (clone $baseQuery)->where('is_active', false)->count(),
            'applications' => JobApplication::query()
                ->whereIn('job_listing_id', (clone $baseQuery)->pluck('id'))
                ->count(),
        ];

        $query = $company->jobs()->withCount('applications');

        if ($request->filled('query')) {
            $search = $request->input('query');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->input('is_active') === '1');
        }

        if ($request->filled('remote_type')) {
            $query->where('remote_type', $request->input('remote_type'));
        }

        $jobs = $query->latest('published_at')->paginate(12)->withQueryString();

        return view('company.pages.jobs.index', compact('company', 'jobs', 'stats'));
    }

    public function create(Request $request): View|RedirectResponse
    {
        if ($redirect = $this->requireCompanyProfile($request)) {
            return $redirect;
        }

        $specialties = TechSpecialty::query()->orderBy('order')->orderBy('name')->get();
        $company = $request->user()->company;

        return view('company.pages.jobs.create', compact('specialties', 'company'));
    }

    public function store(Request $request): RedirectResponse
    {
        if ($redirect = $this->requireCompanyProfile($request)) {
            return $redirect;
        }

        $company = $request->user()->company;
        $request->merge([
            'company_name' => $company->name,
            'company_id' => $company->id,
        ]);

        $validated = $this->jobListingService->validate($request);

        if ($request->hasFile('logo_image')) {
            $validated['logo_image'] = $request->file('logo_image')->store('jobs/logos', 'public');
        } elseif (! $request->hasFile('logo_image') && empty($validated['logo']) && $company->logo) {
            $validated['logo'] = $company->logo;
        }

        $validated = $this->jobListingService->mergeFormArrays($request, $validated);
        $validated['company_id'] = $company->id;
        $validated['company_name'] = $company->name;
        $validated['order'] = $validated['order'] ?? ((Job::max('order') ?? 0) + 1);
        $validated['slug'] = Job::generateUniqueSlug($validated['title']);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_new'] = $request->boolean('is_new', true);
        $validated['is_syria_friendly'] = $request->boolean('is_syria_friendly', true);
        $validated['is_featured'] = false;
        $validated['published_at'] = $validated['published_at'] ?? now();

        $job = Job::create($validated);

        $minNotify = config('matching.min_notify_score', 60);
        foreach ($this->matchingService->topTalentsForJob($job, 20) as $row) {
            if ($row['score'] >= $minNotify && $row['talent']->user) {
                $row['talent']->user->notify(new JobMatchNotification($job, $row['score']));
            }
        }

        return redirect()
            ->route('company.jobs.index')
            ->with('success', 'تم نشر الوظيفة بنجاح');
    }

    public function edit(Request $request, Job $job): View|RedirectResponse
    {
        $this->authorizeCompanyJob($request, $job);

        $specialties = TechSpecialty::query()->orderBy('order')->orderBy('name')->get();
        $company = $request->user()->company;

        return view('company.pages.jobs.edit', compact('job', 'specialties', 'company'));
    }

    public function update(Request $request, Job $job): RedirectResponse
    {
        $this->authorizeCompanyJob($request, $job);

        $company = $request->user()->company;
        $request->merge([
            'company_name' => $company->name,
            'company_id' => $company->id,
        ]);

        $validated = $this->jobListingService->validate($request, $job->id);

        if ($request->hasFile('logo_image')) {
            if ($job->logo_image) {
                Storage::disk('public')->delete($job->logo_image);
            }
            $validated['logo_image'] = $request->file('logo_image')->store('jobs/logos', 'public');
        }

        if ($request->boolean('remove_logo_image') && $job->logo_image) {
            Storage::disk('public')->delete($job->logo_image);
            $validated['logo_image'] = null;
        }

        $validated = $this->jobListingService->mergeFormArrays($request, $validated);
        $validated['company_id'] = $company->id;
        $validated['company_name'] = $company->name;

        if ($job->title !== $validated['title']) {
            $validated['slug'] = Job::generateUniqueSlug($validated['title'], $job->id);
        }

        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_new'] = $request->boolean('is_new');
        $validated['is_syria_friendly'] = $request->boolean('is_syria_friendly');

        $job->update($validated);

        return redirect()
            ->route('company.jobs.index')
            ->with('success', 'تم تحديث الوظيفة بنجاح');
    }

    public function destroy(Request $request, Job $job): RedirectResponse
    {
        $this->authorizeCompanyJob($request, $job);

        if ($job->logo_image) {
            Storage::disk('public')->delete($job->logo_image);
        }

        $job->delete();

        return redirect()
            ->route('company.jobs.index')
            ->with('success', 'تم حذف الوظيفة');
    }

    public function toggleActive(Request $request, Job $job): RedirectResponse
    {
        $this->authorizeCompanyJob($request, $job);

        $job->update(['is_active' => ! $job->is_active]);

        return redirect()
            ->back()
            ->with('success', $job->is_active ? 'تم تفعيل الوظيفة' : 'تم إيقاف الوظيفة');
    }

    private function requireCompanyProfile(Request $request): ?RedirectResponse
    {
        if ($request->user()->company) {
            return null;
        }

        return redirect()
            ->route('company.profile.edit')
            ->with('warning', 'أكمل ملف شركتك أولاً قبل نشر الوظائف.');
    }

    private function authorizeCompanyJob(Request $request, Job $job): void
    {
        $company = $request->user()->company;

        if (! $company || $job->company_id !== $company->id) {
            abort(403);
        }
    }
}
