<?php

namespace App\Http\Controllers\Talent;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobApplicationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'check.user.active', 'role:talent']);
    }

    public function index(Request $request): View
    {
        $user = $request->user();
        $baseQuery = JobApplication::query()->forUser($user->id);

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->where('status', JobApplication::STATUS_PENDING)->count(),
            'accepted' => (clone $baseQuery)->where('status', JobApplication::STATUS_ACCEPTED)->count(),
            'rejected' => (clone $baseQuery)->where('status', JobApplication::STATUS_REJECTED)->count(),
        ];

        $query = $baseQuery->with('job');

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('query')) {
            $search = $request->input('query');
            $query->where(function ($q) use ($search) {
                $q->whereHas('job', function ($jobQuery) use ($search) {
                    $jobQuery->where('title', 'like', "%{$search}%")
                        ->orWhere('company_name', 'like', "%{$search}%");
                });
            });
        }

        $applications = $query->latest()->paginate(20)->withQueryString();

        return view('talents.pages.applications.index', [
            'user' => $user,
            'applications' => $applications,
            'stats' => $stats,
            'statuses' => JobApplication::statusLabels(),
        ]);
    }
}
