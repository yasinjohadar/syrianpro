<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $query = Job::query()->active()->ordered();

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhereJsonContains('skills', $search);
            });
        }

        $jobs = $query->get();
        $jobsJson = $jobs->map(fn (Job $job) => $job->toFrontendArray())->values();

        return view('frontend.pages.jobs', [
            'activePage' => 'jobs',
            'jobs' => $jobs,
            'jobsJson' => $jobsJson,
            'searchQuery' => $request->input('q', ''),
        ]);
    }

    public function show(Job $job)
    {
        abort_unless($job->is_active, 404);

        $application = null;
        if (auth()->check()) {
            $application = \App\Models\JobApplication::query()
                ->where('user_id', auth()->id())
                ->where('job_listing_id', $job->id)
                ->first();
        }

        return view('frontend.pages.job-detail', [
            'activePage' => 'jobs',
            'job' => $job->load(['techSpecialty', 'company']),
            'hasApplied' => $application !== null,
            'applicationStatus' => $application?->status,
            'applicationStatusLabel' => $application?->statusLabel(),
        ]);
    }
}
