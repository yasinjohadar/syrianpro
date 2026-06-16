<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Job;
use App\Models\Talent;
use App\Models\TechSpecialty;

class HomeController extends Controller
{
    public function index()
    {
        $specialties = TechSpecialty::query()
            ->forHome()
            ->withCount(['jobs as active_jobs_count' => fn ($q) => $q->active()])
            ->get();
        $featuredJobs = Job::query()->forHome()->limit(6)->get();
        $featuredTalents = Talent::query()->forHome()->limit(4)->get();
        $featuredCompanies = Company::query()->forHome()->limit(8)->get();

        return view('frontend.pages.index', compact(
            'specialties',
            'featuredJobs',
            'featuredTalents',
            'featuredCompanies'
        ));
    }
}
