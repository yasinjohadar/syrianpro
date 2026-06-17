<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\TalentHiringRequest;
use App\Models\TalentHiringRequestResponse;
use App\Services\TalentHiringRequestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HiringRequestController extends Controller
{
    public function __construct(
        private TalentHiringRequestService $service
    ) {
        $this->middleware(['auth', 'check.user.active', 'role:company']);
    }

    public function index(Request $request): View
    {
        $company = $request->user()->company;
        $tab = $request->input('tab', 'public');

        $publicQuery = TalentHiringRequest::query()
            ->publicActive()
            ->with(['talent.techSpecialty', 'responses' => fn ($q) => $q->where('company_id', $company?->id)]);

        $pitchesQuery = TalentHiringRequest::query()
            ->with(['talent.techSpecialty', 'responses' => fn ($q) => $q->where('company_id', $company?->id)]);

        if ($company) {
            $pitchesQuery->pitchesForCompany($company->id);
        } else {
            $pitchesQuery->whereRaw('1 = 0');
        }

        $this->applyFilters($publicQuery, $request);
        $this->applyFilters($pitchesQuery, $request);

        $publicRequests = $publicQuery->latest('published_at')->paginate(12, ['*'], 'public_page')->withQueryString();
        $pitchRequests = $pitchesQuery->latest('published_at')->paginate(12, ['*'], 'pitch_page')->withQueryString();

        $stats = [
            'public' => $company ? TalentHiringRequest::publicActive()->count() : 0,
            'pitches' => $company ? TalentHiringRequest::pitchesForCompany($company->id)->count() : 0,
            'interested' => $company
                ? TalentHiringRequestResponse::query()->where('company_id', $company->id)->where('status', '!=', 'declined')->count()
                : 0,
            'total' => $company
                ? TalentHiringRequest::publicActive()->count() + TalentHiringRequest::pitchesForCompany($company->id)->count()
                : 0,
        ];

        return view('company.pages.hiring-requests.index', [
            'company' => $company,
            'tab' => $tab,
            'publicRequests' => $publicRequests,
            'pitchRequests' => $pitchRequests,
            'stats' => $stats,
            'employmentTypes' => TalentHiringRequest::employmentTypeLabels(),
        ]);
    }

    public function show(Request $request, TalentHiringRequest $hiringRequest): View
    {
        $company = $request->user()->company;

        if (! $hiringRequest->isVisible()) {
            abort(404);
        }

        if ($hiringRequest->isPitch() && $company && $hiringRequest->company_id !== $company->id) {
            abort(403);
        }

        $hiringRequest->load(['talent.techSpecialty', 'company']);

        $response = null;
        if ($company) {
            $response = $hiringRequest->responses()
                ->where('company_id', $company->id)
                ->first();
        }

        return view('company.pages.hiring-requests.show', [
            'company' => $company,
            'hiringRequest' => $hiringRequest,
            'response' => $response,
            'responseStatuses' => TalentHiringRequestResponse::statusLabels(),
        ]);
    }

    public function respond(Request $request, TalentHiringRequest $hiringRequest): RedirectResponse
    {
        $company = $request->user()->company;

        if (! $company) {
            return back()->with('error', 'يجب إكمال ملف الشركة أولاً.');
        }

        $validated = $request->validate([
            'status' => ['required', 'in:interested,contacted,declined'],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        $this->service->respond(
            $hiringRequest,
            $company,
            $request->user(),
            $validated['status'],
            $validated['message'] ?? null
        );

        return redirect()
            ->route('company.hiring-requests.show', $hiringRequest)
            ->with('success', 'تم تسجيل ردك بنجاح');
    }

    public function markHired(Request $request, TalentHiringRequest $hiringRequest): RedirectResponse
    {
        $company = $request->user()->company;

        if (! $company) {
            return back()->with('error', 'يجب إكمال ملف الشركة أولاً.');
        }

        $this->service->markAsHiredByCompany($hiringRequest, $company, $request->user());

        return redirect()
            ->route('company.hiring-requests.show', $hiringRequest)
            ->with('success', 'تم تأكيد التوظيف بنجاح');
    }

    private function applyFilters($query, Request $request): void
    {
        if ($request->filled('query')) {
            $search = $request->input('query');
            $query->where(function ($q) use ($search) {
                $q->where('headline', 'like', "%{$search}%")
                    ->orWhere('cover_message', 'like', "%{$search}%")
                    ->orWhereHas('talent', function ($tq) use ($search) {
                        $tq->where('name', 'like', "%{$search}%")
                            ->orWhere('title', 'like', "%{$search}%")
                            ->orWhere('city', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('employment_type')) {
            $query->where('employment_type', $request->input('employment_type'));
        }

        if ($request->filled('is_remote')) {
            $query->where('is_remote', $request->input('is_remote') === '1');
        }
    }
}
