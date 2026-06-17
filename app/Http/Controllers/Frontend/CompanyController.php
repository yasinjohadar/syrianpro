<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Job;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = Company::query()->active()->ordered();

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sector', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $companies = $query->get();
        $companiesJson = $companies->map(fn (Company $c) => $c->toFrontendArray())->values();

        return view('frontend.pages.companies', [
            'activePage' => 'companies',
            'companies' => $companies,
            'companiesJson' => $companiesJson,
            'searchQuery' => $request->input('q', ''),
        ]);
    }

    public function show(Company $company)
    {
        abort_unless($company->is_active, 404);

        $companyJobs = $company->jobs()
            ->active()
            ->ordered()
            ->limit(4)
            ->get();

        if ($companyJobs->isEmpty()) {
            $companyJobs = Job::query()->active()->ordered()->limit(3)->get();
        }

        return view('frontend.pages.company-profile', [
            'activePage' => 'companies',
            'company' => $company,
            'companyJobs' => $companyJobs,
        ]);
    }
}
