<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\RespondsWithAjaxTable;
use App\Http\Controllers\Controller;
use App\Models\Talent;
use App\Models\TechSpecialty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TalentController extends Controller
{
    use RespondsWithAjaxTable;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:talent-list')->only('index');
        $this->middleware('permission:talent-create')->only(['create', 'store']);
        $this->middleware('permission:talent-edit')->only(['edit', 'update']);
        $this->middleware('permission:talent-delete')->only('destroy');
        $this->middleware('permission:talent-toggle')->only('toggleActive');
    }

    public function index(Request $request)
    {
        $data = $this->buildIndexData($request);

        if ($response = $this->ajaxTableResponse(
            $request,
            $data,
            'admin.pages.talents.partials.list',
            'admin.pages.talents.partials.modals'
        )) {
            return $response;
        }

        return view('admin.pages.talents.index', $data);
    }

    public function create()
    {
        $specialties = TechSpecialty::query()->orderBy('order')->orderBy('name')->get();

        return view('admin.pages.talents.create', compact('specialties'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateTalent($request);

        if ($request->hasFile('avatar_image')) {
            $validated['avatar_image'] = $request->file('avatar_image')->store('talents/avatars', 'public');
        }

        $validated = $this->mergeFormData($request, $validated);

        if (! isset($validated['order'])) {
            $validated['order'] = (Talent::max('order') ?? 0) + 1;
        }

        $validated['slug'] = Talent::generateUniqueSlug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_verified'] = $request->boolean('is_verified');
        $validated['is_remote'] = $request->boolean('is_remote');

        Talent::create($validated);

        return redirect()
            ->route('admin.talents.index')
            ->with('success', 'تم إنشاء الموهبة بنجاح');
    }

    public function edit(Talent $talent)
    {
        $specialties = TechSpecialty::query()->orderBy('order')->orderBy('name')->get();

        return view('admin.pages.talents.create', compact('talent', 'specialties'));
    }

    public function update(Request $request, Talent $talent)
    {
        $validated = $this->validateTalent($request, $talent->id);

        if ($request->hasFile('avatar_image')) {
            if ($talent->avatar_image) {
                Storage::disk('public')->delete($talent->avatar_image);
            }
            $validated['avatar_image'] = $request->file('avatar_image')->store('talents/avatars', 'public');
        }

        if ($request->boolean('remove_avatar_image') && $talent->avatar_image) {
            Storage::disk('public')->delete($talent->avatar_image);
            $validated['avatar_image'] = null;
        }

        $validated = $this->mergeFormData($request, $validated);

        if ($talent->name !== $validated['name']) {
            $validated['slug'] = Talent::generateUniqueSlug($validated['name'], $talent->id);
        }

        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_verified'] = $request->boolean('is_verified');
        $validated['is_remote'] = $request->boolean('is_remote');

        $talent->update($validated);

        return redirect()
            ->route('admin.talents.index')
            ->with('success', 'تم تحديث الموهبة بنجاح');
    }

    public function destroy(Talent $talent)
    {
        if ($talent->avatar_image) {
            Storage::disk('public')->delete($talent->avatar_image);
        }

        $talent->delete();

        return redirect()
            ->route('admin.talents.index')
            ->with('success', 'تم حذف الموهبة بنجاح');
    }

    public function toggleActive(Talent $talent)
    {
        $talent->update(['is_active' => ! $talent->is_active]);

        return redirect()
            ->back()
            ->with('success', $talent->is_active ? 'تم تفعيل الموهبة' : 'تم تعطيل الموهبة');
    }

    private function buildIndexData(Request $request): array
    {
        $query = Talent::query()->with('techSpecialty');

        if ($request->filled('query')) {
            $search = $request->input('query');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->input('is_active') === '1');
        }

        if ($request->filled('is_featured')) {
            $query->where('is_featured', $request->input('is_featured') === '1');
        }

        if ($request->filled('is_verified')) {
            $query->where('is_verified', $request->input('is_verified') === '1');
        }

        $stats = [
            'total' => Talent::count(),
            'active' => Talent::where('is_active', true)->count(),
            'inactive' => Talent::where('is_active', false)->count(),
            'featured' => Talent::where('is_featured', true)->where('is_active', true)->count(),
            'filtered' => (clone $query)->count(),
        ];

        $talents = $query->ordered()->paginate(10)->withQueryString();

        return compact('talents', 'stats');
    }

    private function validateTalent(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'avatar' => 'nullable|string|max:10',
            'avatar_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
            'bio' => 'nullable|string',
            'skills_text' => 'nullable|string',
            'availability' => 'nullable|string|max:255',
            'rate_min' => 'nullable|integer|min:0',
            'rate_max' => 'nullable|integer|min:0',
            'rate_currency' => 'nullable|string|max:10',
            'tech_specialty_id' => 'nullable|exists:tech_specialties,id',
            'experience_json' => 'nullable|string',
            'projects_json' => 'nullable|string',
            'link_github' => 'nullable|string|max:500',
            'link_linkedin' => 'nullable|string|max:500',
            'link_portfolio' => 'nullable|string|max:500',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'is_verified' => 'nullable|boolean',
            'is_remote' => 'nullable|boolean',
            'remove_avatar_image' => 'nullable|boolean',
        ], [
            'name.required' => 'الاسم مطلوب',
            'title.required' => 'المسمى الوظيفي مطلوب',
            'city.required' => 'المدينة مطلوبة',
            'avatar_image.max' => 'حجم الصورة يجب أن يكون أقل من 2 ميجابايت',
        ]);
    }

    private function mergeFormData(Request $request, array $validated): array
    {
        $validated['skills'] = $this->csvToArray($request->input('skills_text'));
        $validated['experience'] = $this->jsonToArray($request->input('experience_json'));
        $validated['projects'] = $this->jsonToArray($request->input('projects_json'));
        $validated['links'] = array_filter([
            'github' => $request->input('link_github'),
            'linkedin' => $request->input('link_linkedin'),
            'portfolio' => $request->input('link_portfolio'),
        ], fn ($v) => filled($v));

        unset(
            $validated['skills_text'],
            $validated['experience_json'],
            $validated['projects_json'],
            $validated['link_github'],
            $validated['link_linkedin'],
            $validated['link_portfolio'],
            $validated['remove_avatar_image']
        );

        return $validated;
    }

    private function csvToArray(?string $value): array
    {
        if (! $value) {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', $value))));
    }

    private function jsonToArray(?string $value): array
    {
        if (! trim((string) $value)) {
            return [];
        }

        $decoded = json_decode($value, true);

        return is_array($decoded) ? $decoded : [];
    }
}
