<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Talent;
use App\Models\TalentRecommendation;
use App\Models\TechSpecialty;
use App\Services\TalentRecommendationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TalentRecommendationController extends Controller
{
    public function __construct(
        private TalentRecommendationService $service
    ) {
        $this->middleware('auth');
    }

    public function index(Request $request): View
    {
        $query = TalentRecommendation::query()->with(['talent', 'recommender']);

        if ($request->filled('scope')) {
            $query->where('scope', $request->input('scope'));
        }

        if ($request->boolean('active_only')) {
            $query->active();
        }

        $recommendations = $query->latest()->paginate(20)->withQueryString();

        return view('admin.pages.talent-recommendations.index', [
            'recommendations' => $recommendations,
            'scopes' => TalentRecommendation::scopeLabels(),
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.pages.talent-recommendations.create', [
            'talent' => $request->filled('talent_id') ? Talent::find($request->integer('talent_id')) : null,
            'talents' => Talent::query()->active()->orderBy('name')->get(['id', 'name', 'title']),
            'scopes' => TalentRecommendation::scopeLabels(),
            'specialties' => TechSpecialty::query()->orderBy('name')->get(),
            'jobs' => Job::query()->active()->orderBy('title')->get(['id', 'title']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'talent_id' => ['required', 'exists:talents,id'],
            'reason' => ['required', 'string', 'max:500'],
            'scope' => ['required', 'in:homepage,talents_page,specialty,job'],
            'scope_id' => ['nullable', 'integer'],
            'priority' => ['nullable', 'integer', 'min:0', 'max:100'],
            'expires_at' => ['nullable', 'date', 'after:today'],
        ]);

        if (in_array($validated['scope'], ['specialty', 'job'], true) && empty($validated['scope_id'])) {
            return back()->withErrors(['scope_id' => 'اختر التخصص أو الوظيفة'])->withInput();
        }

        $talent = Talent::findOrFail($validated['talent_id']);
        $this->service->create($request->user(), $talent, $validated);

        return redirect()
            ->route('admin.talent-recommendations.index')
            ->with('success', 'تم إنشاء التوصية بنجاح');
    }

    public function destroy(TalentRecommendation $talentRecommendation): RedirectResponse
    {
        $this->service->deactivate($talentRecommendation);

        return back()->with('success', 'تم إيقاف التوصية');
    }
}
