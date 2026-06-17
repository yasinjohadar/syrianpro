<?php

namespace App\Http\Controllers\Talent;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Talent;
use App\Models\TalentHiringRequest;
use App\Services\TalentHiringRequestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HiringRequestController extends Controller
{
    public function __construct(
        private TalentHiringRequestService $service
    ) {
        $this->middleware(['auth', 'check.user.active', 'role:talent']);
    }

    public function index(Request $request): View
    {
        $user = $request->user();
        $talent = $this->resolveTalent($user);

        $publicRequest = null;
        $pitches = collect();
        $responses = collect();

        if ($talent) {
            $publicRequest = TalentHiringRequest::query()
                ->forTalent($talent->id)
                ->public()
                ->latest()
                ->first();

            $pitches = TalentHiringRequest::query()
                ->forTalent($talent->id)
                ->pitches()
                ->with('company')
                ->latest()
                ->get();

            $responses = $talent->hiringRequests()
                ->with(['responses.company', 'company'])
                ->whereHas('responses')
                ->latest()
                ->get()
                ->flatMap(fn (TalentHiringRequest $req) => $req->responses);
        }

        $companies = Company::query()->active()->ordered()->get(['id', 'name', 'sector', 'location']);

        return view('talents.pages.hiring-request.index', [
            'user' => $user,
            'talent' => $talent,
            'publicRequest' => $publicRequest,
            'pitches' => $pitches,
            'responses' => $responses,
            'companies' => $companies,
            'employmentTypes' => TalentHiringRequest::employmentTypeLabels(),
        ]);
    }

    public function storePublic(Request $request): RedirectResponse
    {
        $talent = $this->resolveTalent($request->user(), required: true);
        $validated = $this->validateRequestData($request);

        $this->service->upsertPublicRequest($request->user(), $talent, $validated);

        return redirect()
            ->route('talent.hiring-request.index')
            ->with('success', $request->boolean('publish') ? 'تم نشر طلب التوظيف بنجاح' : 'تم حفظ الطلب كمسودة');
    }

    public function storePitch(Request $request): RedirectResponse
    {
        $talent = $this->resolveTalent($request->user(), required: true);
        $validated = $request->validate([
            'company_id' => ['required', 'exists:companies,id'],
            'headline' => ['required', 'string', 'max:255'],
            'cover_message' => ['nullable', 'string', 'max:2000'],
            'employment_type' => ['nullable', 'in:full_time,part_time,freelance,contract'],
            'is_remote' => ['nullable', 'boolean'],
            'rate_min' => ['nullable', 'integer', 'min:0'],
            'rate_max' => ['nullable', 'integer', 'min:0'],
        ], [
            'company_id.required' => 'اختر الشركة',
            'headline.required' => 'العنوان مطلوب',
        ]);

        $company = Company::query()->active()->findOrFail($validated['company_id']);
        $this->service->createPitch($request->user(), $talent, $company, $validated);

        return redirect()
            ->route('talent.hiring-request.index')
            ->with('success', 'تم إرسال العرض إلى '.$company->name);
    }

    public function pause(Request $request, TalentHiringRequest $hiringRequest): RedirectResponse
    {
        $this->authorizeTalentRequest($request, $hiringRequest);
        $this->service->pauseRequest($hiringRequest, $request->user()->talent);

        return back()->with('success', 'تم إيقاف الطلب مؤقتاً');
    }

    public function resume(Request $request, TalentHiringRequest $hiringRequest): RedirectResponse
    {
        $this->authorizeTalentRequest($request, $hiringRequest);
        $this->service->resumeRequest($hiringRequest, $request->user()->talent);

        return back()->with('success', 'تم استئناف الطلب');
    }

    public function close(Request $request, TalentHiringRequest $hiringRequest): RedirectResponse
    {
        $this->authorizeTalentRequest($request, $hiringRequest);
        $this->service->closeRequest($hiringRequest, $request->user()->talent);

        return back()->with('success', 'تم إغلاق الطلب');
    }

    public function toggleOpenToWork(Request $request): RedirectResponse
    {
        $talent = $this->resolveTalent($request->user(), required: true);
        $enabled = $request->boolean('is_open_to_work');
        $this->service->toggleOpenToWork($talent, $enabled);

        return back()->with('success', $enabled ? 'أنت الآن متاح للتوظيف' : 'تم إيقاف حالة البحث عن عمل');
    }

    public function markHired(Request $request, TalentHiringRequest $hiringRequest): RedirectResponse
    {
        $talent = $this->resolveTalent($request->user(), required: true);
        $this->authorizeTalentRequest($request, $hiringRequest);
        $this->service->markAsHiredByTalent($hiringRequest, $talent);

        return back()->with('success', 'تم تأكيد التوظيف — مبروك!');
    }

    private function resolveTalent($user, bool $required = false): ?Talent
    {
        $talent = $user->talent;

        if ($required && ! $talent) {
            abort(422, 'يجب إكمال ملفك أولاً من صفحة تعديل الملف.');
        }

        return $talent;
    }

    private function authorizeTalentRequest(Request $request, TalentHiringRequest $hiringRequest): void
    {
        $talent = $request->user()->talent;

        if (! $talent || $hiringRequest->talent_id !== $talent->id) {
            abort(403);
        }
    }

    private function validateRequestData(Request $request): array
    {
        return $request->validate([
            'headline' => ['required', 'string', 'max:255'],
            'cover_message' => ['nullable', 'string', 'max:2000'],
            'employment_type' => ['nullable', 'in:full_time,part_time,freelance,contract'],
            'is_remote' => ['nullable', 'boolean'],
            'rate_min' => ['nullable', 'integer', 'min:0'],
            'rate_max' => ['nullable', 'integer', 'min:0'],
            'expires_at' => ['nullable', 'date', 'after:today'],
            'publish' => ['nullable', 'boolean'],
        ], [
            'headline.required' => 'عنوان الطلب مطلوب',
        ]);
    }
}
