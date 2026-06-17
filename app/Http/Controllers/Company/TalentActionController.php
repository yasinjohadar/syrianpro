<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\CompanyTalentAction;
use App\Models\Job;
use App\Models\Talent;
use App\Services\CompanyTalentActionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TalentActionController extends Controller
{
    public function __construct(
        private CompanyTalentActionService $service
    ) {
        $this->middleware(['auth', 'check.user.active', 'role:company']);
    }

    public function shortlist(Request $request): View
    {
        $company = $request->user()->company;

        $items = CompanyTalentAction::query()
            ->when($company, fn ($q) => $q->where('company_id', $company->id), fn ($q) => $q->whereRaw('1 = 0'))
            ->where('type', CompanyTalentAction::TYPE_SHORTLIST)
            ->where('status', CompanyTalentAction::STATUS_ACTIVE)
            ->with('talent')
            ->latest()
            ->paginate(15);

        return view('company.pages.shortlist.index', compact('company', 'items'));
    }

    public function invite(Request $request, Talent $talent): RedirectResponse
    {
        $company = $request->user()->company;
        abort_unless($company, 403);

        $validated = $request->validate([
            'job_listing_id' => ['required', 'exists:job_listings,id'],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        $job = Job::query()->findOrFail($validated['job_listing_id']);
        $this->service->invite($company, $request->user(), $talent, $job, $validated['message'] ?? null);

        return back()->with('success', 'تم إرسال الدعوة للتقني');
    }

    public function toggleShortlist(Request $request, Talent $talent): RedirectResponse
    {
        $company = $request->user()->company;
        abort_unless($company, 403);

        $this->service->toggleShortlist($company, $request->user(), $talent, $request->integer('fit_rating') ?: null);

        return back()->with('success', 'تم تحديث القائمة المختصرة');
    }

    public function storeNote(Request $request, Talent $talent): RedirectResponse
    {
        $company = $request->user()->company;
        abort_unless($company, 403);

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
            'fit_rating' => ['nullable', 'integer', 'min:1', 'max:5'],
        ]);

        $this->service->addNote($company, $request->user(), $talent, $validated['message'], $validated['fit_rating'] ?? null);

        return back()->with('success', 'تم حفظ الملاحظة');
    }
}
