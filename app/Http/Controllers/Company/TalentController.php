<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\CompanyTalentAction;
use App\Models\Talent;
use App\Models\TalentHiringRequest;
use App\Models\TechSpecialty;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TalentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'check.user.active', 'role:company']);
    }

    public function index(Request $request): View
    {
        $baseQuery = Talent::query()->active();

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'verified' => (clone $baseQuery)->where('is_verified', true)->count(),
            'featured' => (clone $baseQuery)->where('is_featured', true)->count(),
            'remote' => (clone $baseQuery)->where('is_remote', true)->count(),
            'open_to_work' => (clone $baseQuery)->where('is_open_to_work', true)->count(),
        ];

        $query = Talent::query()
            ->active()
            ->with('techSpecialty');

        if ($request->filled('query')) {
            $search = $request->input('query');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('bio', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tech_specialty_id')) {
            $query->where('tech_specialty_id', $request->integer('tech_specialty_id'));
        }

        if ($request->filled('city')) {
            $query->where('city', $request->input('city'));
        }

        if ($request->filled('is_remote')) {
            $query->where('is_remote', $request->input('is_remote') === '1');
        }

        if ($request->filled('open_to_work')) {
            $query->where('is_open_to_work', $request->input('open_to_work') === '1');
        }

        if ($request->filled('is_verified')) {
            $query->where('is_verified', $request->input('is_verified') === '1');
        }

        if ($request->filled('is_featured')) {
            $query->where('is_featured', $request->input('is_featured') === '1');
        }

        if ($request->filled('availability')) {
            $query->where('availability', 'like', '%'.$request->input('availability').'%');
        }

        if ($request->filled('skill')) {
            $skill = $request->input('skill');
            $query->where(function ($q) use ($skill) {
                $q->whereJsonContains('skills', $skill)
                    ->orWhere('skills', 'like', '%"'.$skill.'"%');
            });
        }

        if ($request->filled('rate_min')) {
            $query->where('rate_min', '>=', (int) $request->input('rate_min'));
        }

        if ($request->filled('rate_max')) {
            $query->where('rate_max', '<=', (int) $request->input('rate_max'));
        }

        $sort = $request->input('sort', 'order');
        match ($sort) {
            'name' => $query->orderBy('name'),
            'rate_asc' => $query->orderBy('rate_min'),
            'rate_desc' => $query->orderByDesc('rate_max'),
            'featured' => $query->orderByDesc('is_featured')->orderBy('order'),
            default => $query->ordered(),
        };

        $talents = $query->paginate(12)->withQueryString();

        $specialties = TechSpecialty::query()->orderBy('order')->orderBy('name')->get();
        $cities = Talent::query()->active()->distinct()->orderBy('city')->pluck('city');
        $availabilities = Talent::query()
            ->active()
            ->whereNotNull('availability')
            ->where('availability', '!=', '')
            ->distinct()
            ->orderBy('availability')
            ->pluck('availability');

        return view('company.pages.talents.index', compact(
            'talents',
            'stats',
            'specialties',
            'cities',
            'availabilities'
        ));
    }

    public function show(Talent $talent): View
    {
        abort_unless($talent->is_active, 404);

        $talent->load(['techSpecialty', 'activePublicHiringRequest']);

        $company = auth()->user()?->company;
        $hiringResponse = null;
        $activeRequest = $talent->activePublicHiringRequest;

        if ($company && $activeRequest) {
            $hiringResponse = $activeRequest->responses()
                ->where('company_id', $company->id)
                ->first();
        }

        $pitchRequest = null;
        $companyJobs = collect();
        $isShortlisted = false;
        $notes = collect();

        if ($company) {
            $pitchRequest = TalentHiringRequest::query()
                ->forTalent($talent->id)
                ->pitchesForCompany($company->id)
                ->latest()
                ->first();

            $companyJobs = $company->jobs()->active()->orderBy('title')->get(['id', 'title']);

            $isShortlisted = CompanyTalentAction::query()
                ->where('company_id', $company->id)
                ->where('talent_id', $talent->id)
                ->where('type', CompanyTalentAction::TYPE_SHORTLIST)
                ->where('status', CompanyTalentAction::STATUS_ACTIVE)
                ->exists();

            $notes = CompanyTalentAction::query()
                ->where('company_id', $company->id)
                ->where('talent_id', $talent->id)
                ->where('type', CompanyTalentAction::TYPE_NOTE)
                ->latest()
                ->take(5)
                ->get();
        }

        return view('company.pages.talents.show', compact(
            'talent',
            'activeRequest',
            'hiringResponse',
            'pitchRequest',
            'company',
            'companyJobs',
            'isShortlisted',
            'notes'
        ));
    }
}
