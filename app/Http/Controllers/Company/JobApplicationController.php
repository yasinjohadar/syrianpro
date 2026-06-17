<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobApplication;
use App\Notifications\JobApplicationStatusChangedNotification;
use App\Services\HireRecordService;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    public function __construct(
        private HireRecordService $hireRecordService
    ) {
        $this->middleware(['auth', 'check.user.active', 'role:company']);
    }

    public function index(Request $request)
    {
        $company = $request->user()->company;
        $applications = Job::query()->whereRaw('1 = 0')->paginate(20);
        $stats = ['total' => 0, 'pending' => 0, 'accepted' => 0, 'rejected' => 0];
        $filteredJob = null;

        if ($company) {
            $jobIds = $company->jobs()->pluck('id');

            $stats = [
                'total' => JobApplication::query()->whereIn('job_listing_id', $jobIds)->count(),
                'pending' => JobApplication::query()->whereIn('job_listing_id', $jobIds)->where('status', JobApplication::STATUS_PENDING)->count(),
                'accepted' => JobApplication::query()->whereIn('job_listing_id', $jobIds)->where('status', JobApplication::STATUS_ACCEPTED)->count(),
                'rejected' => JobApplication::query()->whereIn('job_listing_id', $jobIds)->where('status', JobApplication::STATUS_REJECTED)->count(),
            ];

            $query = JobApplication::query()
                ->with(['user', 'job'])
                ->whereIn('job_listing_id', $jobIds);

            if ($request->filled('job_id')) {
                $query->where('job_listing_id', $request->integer('job_id'));
                $filteredJob = Job::query()->find($request->integer('job_id'));
            }

            if ($request->filled('status')) {
                $query->where('status', $request->input('status'));
            }

            if ($request->filled('query')) {
                $search = $request->input('query');
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }

            $applications = $query->latest()->paginate(20)->withQueryString();
        }

        return view('company.pages.applications.index', [
            'company' => $company,
            'applications' => $applications,
            'stats' => $stats,
            'filteredJob' => $filteredJob,
            'statuses' => JobApplication::statusLabels(),
        ]);
    }

    public function show(Request $request, JobApplication $application)
    {
        $company = $request->user()->company;
        $application->load(['user.talent', 'job']);

        if (! $company || ! $application->job || $application->job->company_id !== $company->id) {
            abort(403);
        }

        return view('company.pages.applications.show', [
            'company' => $company,
            'application' => $application,
            'statuses' => JobApplication::statusLabels(),
        ]);
    }

    public function update(Request $request, JobApplication $application)
    {
        $company = $request->user()->company;
        $application->load('job');

        if (! $company || ! $application->job || $application->job->company_id !== $company->id) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => ['required', 'in:'.implode(',', array_keys(JobApplication::statusLabels()))],
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $previousStatus = $application->status;
        $application->update($validated);

        if ($previousStatus !== $application->status && $application->user) {
            $application->user->notify(new JobApplicationStatusChangedNotification($application->fresh(['job'])));
        }

        if ($application->status === JobApplication::STATUS_ACCEPTED) {
            $this->hireRecordService->recordFromApplication($application->fresh(['user.talent', 'job']));
        }

        return redirect()
            ->route('company.applications.show', $application)
            ->with('success', 'تم تحديث حالة الطلب');
    }
}
