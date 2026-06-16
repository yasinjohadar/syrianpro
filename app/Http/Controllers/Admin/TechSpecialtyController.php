<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\RespondsWithAjaxTable;
use App\Http\Controllers\Controller;
use App\Models\TechSpecialty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TechSpecialtyController extends Controller
{
    use RespondsWithAjaxTable;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:tech-specialty-list')->only('index');
        $this->middleware('permission:tech-specialty-create')->only(['create', 'store']);
        $this->middleware('permission:tech-specialty-edit')->only(['edit', 'update']);
        $this->middleware('permission:tech-specialty-delete')->only('destroy');
        $this->middleware('permission:tech-specialty-toggle')->only('toggleActive');
    }

    public function index(Request $request)
    {
        $data = $this->buildIndexData($request);

        if ($response = $this->ajaxTableResponse(
            $request,
            $data,
            'admin.pages.tech-specialties.partials.list',
            'admin.pages.tech-specialties.partials.modals'
        )) {
            return $response;
        }

        return view('admin.pages.tech-specialties.index', $data);
    }

    public function create()
    {
        return view('admin.pages.tech-specialties.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateSpecialty($request);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('specialties', 'public');
        }

        if (! isset($validated['order'])) {
            $validated['order'] = (TechSpecialty::max('order') ?? 0) + 1;
        }

        $validated['is_active'] = $request->boolean('is_active');
        $validated['show_on_home'] = $request->boolean('show_on_home');
        $validated['slug'] = TechSpecialty::generateUniqueSlug($validated['name']);

        TechSpecialty::create($validated);

        return redirect()
            ->route('admin.tech-specialties.index')
            ->with('success', 'تم إنشاء التخصص بنجاح');
    }

    public function edit(TechSpecialty $techSpecialty)
    {
        return view('admin.pages.tech-specialties.edit', compact('techSpecialty'));
    }

    public function update(Request $request, TechSpecialty $techSpecialty)
    {
        $validated = $this->validateSpecialty($request, $techSpecialty->id);

        if ($request->hasFile('image')) {
            if ($techSpecialty->image) {
                Storage::disk('public')->delete($techSpecialty->image);
            }
            $validated['image'] = $request->file('image')->store('specialties', 'public');
        }

        if ($request->boolean('remove_image') && $techSpecialty->image) {
            Storage::disk('public')->delete($techSpecialty->image);
            $validated['image'] = null;
        }

        if ($techSpecialty->name !== $validated['name']) {
            $validated['slug'] = TechSpecialty::generateUniqueSlug($validated['name'], $techSpecialty->id);
        }

        $validated['is_active'] = $request->boolean('is_active');
        $validated['show_on_home'] = $request->boolean('show_on_home');

        $techSpecialty->update($validated);

        return redirect()
            ->route('admin.tech-specialties.index')
            ->with('success', 'تم تحديث التخصص بنجاح');
    }

    public function destroy(TechSpecialty $techSpecialty)
    {
        if ($techSpecialty->image) {
            Storage::disk('public')->delete($techSpecialty->image);
        }

        $techSpecialty->delete();

        return redirect()
            ->route('admin.tech-specialties.index')
            ->with('success', 'تم حذف التخصص بنجاح');
    }

    public function toggleActive(TechSpecialty $techSpecialty)
    {
        $techSpecialty->update(['is_active' => ! $techSpecialty->is_active]);

        return redirect()
            ->back()
            ->with('success', $techSpecialty->is_active ? 'تم تفعيل التخصص' : 'تم تعطيل التخصص');
    }

    private function buildIndexData(Request $request): array
    {
        $query = TechSpecialty::query()
            ->withCount(['jobs as active_jobs_count' => fn ($q) => $q->active()]);

        if ($request->filled('query')) {
            $search = $request->input('query');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->input('is_active') === '1');
        }

        if ($request->filled('show_on_home')) {
            $query->where('show_on_home', $request->input('show_on_home') === '1');
        }

        $stats = [
            'total' => TechSpecialty::count(),
            'active' => TechSpecialty::where('is_active', true)->count(),
            'inactive' => TechSpecialty::where('is_active', false)->count(),
            'on_home' => TechSpecialty::where('show_on_home', true)->where('is_active', true)->count(),
            'filtered' => (clone $query)->count(),
        ];

        $specialties = $query->orderBy('order')->orderBy('name')->paginate(10)->withQueryString();

        return compact('specialties', 'stats');
    }

    private function validateSpecialty(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tech_specialties', 'name')->ignore($ignoreId),
            ],
            'icon' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
            'jobs_count' => 'nullable|integer|min:0',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'show_on_home' => 'nullable|boolean',
            'remove_image' => 'nullable|boolean',
        ], [
            'name.required' => 'اسم التخصص مطلوب',
            'name.unique' => 'اسم التخصص موجود مسبقاً',
            'image.image' => 'يجب أن يكون الملف صورة',
            'image.max' => 'حجم الصورة يجب أن يكون أقل من 2 ميجابايت',
        ]);
    }
}
