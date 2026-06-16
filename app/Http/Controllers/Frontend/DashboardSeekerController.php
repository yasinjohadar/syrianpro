<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardSeekerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'check.user.active']);
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $applications = $user->jobApplications()
            ->with('job')
            ->latest()
            ->get();

        return view('frontend.pages.dashboard-seeker', [
            'activePage' => 'dashboard-seeker',
            'user' => $user,
            'applications' => $applications,
        ]);
    }
}
