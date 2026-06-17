<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Job;
use App\Models\Talent;
use App\Models\TechSpecialty;
use App\Services\TalentRecommendationService;

class HomeController extends Controller
{
    public function index(TalentRecommendationService $recommendationService)
    {
        $specialties = TechSpecialty::query()
            ->forHome()
            ->withCount(['jobs as active_jobs_count' => fn ($q) => $q->active()])
            ->get();
        $featuredJobs = Job::query()
            ->forHome()
            ->with('techSpecialty')
            ->limit(6)
            ->get();
        $featuredTalents = Talent::query()
            ->forHome()
            ->with(['activePublicHiringRequest', 'techSpecialty'])
            ->limit(4)
            ->get();
        $featuredCompanies = Company::query()->forHome()->limit(8)->get();
        $recommendedTalents = $recommendationService->activeForHomepage(6);

        return view('frontend.pages.index', compact(
            'specialties',
            'featuredJobs',
            'featuredTalents',
            'featuredCompanies',
            'recommendedTalents'
        ));
    }
}
