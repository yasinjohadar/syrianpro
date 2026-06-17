<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobApplication;
use App\Notifications\JobApplicationStatusChangedNotification;
use App\Services\HireRecordService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JobApplicationController extends Controller
{
    public function __construct(
        private HireRecordService $hireRecordService
    ) {
        $this->middleware('auth');
        $this->middleware('permission:job-application-list')->only('index');
        $this->middleware('permission:job-application-show')->only('show');
        $this->middleware('permission:job-application-update')->only('updateStatus');
        $this->middleware('permission:job-application-delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = JobApplication::query()
            ->with(['user', 'job']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('job', function ($jq) use ($search) {
                    $jq->where('title', 'like', "%{$search}%")
                        ->orWhere('company_name', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('job_id')) {
            $query->where('job_listing_id', $request->job_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $filteredCount = (clone $query)->count();

        $applications = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        $stats = [
            'total' => JobApplication::count(),
            'pending' => JobApplication::whereIn('status', [
                JobApplication::STATUS_PENDING,
                JobApplication::STATUS_REVIEWING,
            ])->count(),
            'accepted' => JobApplication::where('status', JobApplication::STATUS_ACCEPTED)->count(),
            'rejected' => JobApplication::where('status', JobApplication::STATUS_REJECTED)->count(),
            'filtered' => $filteredCount,
        ];

        $jobs = Job::query()->orderBy('title')->get(['id', 'title', 'company_name']);

        return view('admin.pages.job-applications.index', compact('applications', 'stats', 'jobs'));
    }

    public function show(JobApplication $jobApplication)
    {
        $jobApplication->load(['user', 'job']);

        return view('admin.pages.job-applications.show', compact('jobApplication'));
    }

    public function updateStatus(Request $request, JobApplication $jobApplication)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys(JobApplication::statusLabels()))],
            'admin_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $previousStatus = $jobApplication->status;
        $jobApplication->update($validated);

        if ($previousStatus !== $jobApplication->status && $jobApplication->user) {
            $jobApplication->user->notify(new JobApplicationStatusChangedNotification($jobApplication->fresh(['job'])));
        }

        if ($jobApplication->status === JobApplication::STATUS_ACCEPTED) {
            $this->hireRecordService->recordFromApplication($jobApplication->fresh(['user.talent', 'job']));
        }

        return redirect()
            ->route('admin.job-applications.show', $jobApplication)
            ->with('success', 'تم تحديث حالة الطلب بنجاح');
    }

    public function destroy(JobApplication $jobApplication)
    {
        $jobApplication->delete();

        return redirect()
            ->route('admin.job-applications.index')
            ->with('success', 'تم حذف الطلب بنجاح');
    }
}
