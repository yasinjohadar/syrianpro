<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\RespondsWithAjaxTable;
use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\TechSpecialty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class JobController extends Controller
{
    use RespondsWithAjaxTable;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:job-list')->only('index');
        $this->middleware('permission:job-create')->only(['create', 'store']);
        $this->middleware('permission:job-edit')->only(['edit', 'update']);
        $this->middleware('permission:job-delete')->only('destroy');
        $this->middleware('permission:job-toggle')->only('toggleActive');
    }

    public function index(Request $request)
    {
        $data = $this->buildIndexData($request);

        if ($response = $this->ajaxTableResponse(
            $request,
            $data,
            'admin.pages.jobs.partials.list',
            'admin.pages.jobs.partials.modals'
        )) {
            return $response;
        }

        return view('admin.pages.jobs.index', $data);
    }

    public function create()
    {
        $specialties = TechSpecialty::query()->orderBy('order')->orderBy('name')->get();

        return view('admin.pages.jobs.create', compact('specialties'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateJob($request);

        if ($request->hasFile('logo_image')) {
            $validated['logo_image'] = $request->file('logo_image')->store('jobs/logos', 'public');
        }

        $validated = $this->mergeFormArrays($request, $validated);

        if (! isset($validated['order'])) {
            $validated['order'] = (Job::max('order') ?? 0) + 1;
        }

        $validated['slug'] = Job::generateUniqueSlug($validated['title']);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_new'] = $request->boolean('is_new');
        $validated['is_syria_friendly'] = $request->boolean('is_syria_friendly');

        Job::create($validated);

        return redirect()
            ->route('admin.jobs.index')
            ->with('success', 'تم إنشاء الوظيفة بنجاح');
    }

    public function edit(Job $job)
    {
        $specialties = TechSpecialty::query()->orderBy('order')->orderBy('name')->get();

        return view('admin.pages.jobs.create', compact('job', 'specialties'));
    }

    public function update(Request $request, Job $job)
    {
        $validated = $this->validateJob($request, $job->id);

        if ($request->hasFile('logo_image')) {
            if ($job->logo_image) {
                Storage::disk('public')->delete($job->logo_image);
            }
            $validated['logo_image'] = $request->file('logo_image')->store('jobs/logos', 'public');
        }

        if ($request->boolean('remove_logo_image') && $job->logo_image) {
            Storage::disk('public')->delete($job->logo_image);
            $validated['logo_image'] = null;
        }

        $validated = $this->mergeFormArrays($request, $validated);

        if ($job->title !== $validated['title']) {
            $validated['slug'] = Job::generateUniqueSlug($validated['title'], $job->id);
        }

        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_new'] = $request->boolean('is_new');
        $validated['is_syria_friendly'] = $request->boolean('is_syria_friendly');

        $job->update($validated);

        return redirect()
            ->route('admin.jobs.index')
            ->with('success', 'تم تحديث الوظيفة بنجاح');
    }

    public function destroy(Job $job)
    {
        if ($job->logo_image) {
            Storage::disk('public')->delete($job->logo_image);
        }

        $job->delete();

        return redirect()
            ->route('admin.jobs.index')
            ->with('success', 'تم حذف الوظيفة بنجاح');
    }

    public function toggleActive(Job $job)
    {
        $job->update(['is_active' => ! $job->is_active]);

        return redirect()
            ->back()
            ->with('success', $job->is_active ? 'تم تفعيل الوظيفة' : 'تم تعطيل الوظيفة');
    }

    private function buildIndexData(Request $request): array
    {
        $query = Job::query()->with('techSpecialty');

        if ($request->filled('query')) {
            $search = $request->input('query');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->input('is_active') === '1');
        }

        if ($request->filled('is_featured')) {
            $query->where('is_featured', $request->input('is_featured') === '1');
        }

        if ($request->filled('remote_type')) {
            $query->where('remote_type', $request->input('remote_type'));
        }

        $stats = [
            'total' => Job::count(),
            'active' => Job::where('is_active', true)->count(),
            'inactive' => Job::where('is_active', false)->count(),
            'featured' => Job::where('is_featured', true)->where('is_active', true)->count(),
            'filtered' => (clone $query)->count(),
        ];

        $jobs = $query->ordered()->paginate(10)->withQueryString();

        return compact('jobs', 'stats');
    }

    private function validateJob(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'logo' => 'nullable|string|max:100',
            'logo_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
            'location' => 'required|string|max:255',
            'employment_type' => 'required|string|max:100',
            'salary_min' => 'nullable|integer|min:0',
            'salary_max' => 'nullable|integer|min:0',
            'currency' => 'nullable|string|max:10',
            'remote_type' => 'required|string|max:50',
            'timezone' => 'nullable|string|max:50',
            'tech_specialty_id' => 'nullable|exists:tech_specialties,id',
            'description' => 'nullable|string',
            'responsibilities_text' => 'nullable|string',
            'requirements_text' => 'nullable|string',
            'benefits_text' => 'nullable|string',
            'skills_text' => 'nullable|string',
            'payment_methods_text' => 'nullable|string',
            'tags_text' => 'nullable|string',
            'tag_labels_json' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'published_at' => 'nullable|date',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'is_new' => 'nullable|boolean',
            'is_syria_friendly' => 'nullable|boolean',
            'remove_logo_image' => 'nullable|boolean',
        ], [
            'title.required' => 'عنوان الوظيفة مطلوب',
            'company_name.required' => 'اسم الشركة مطلوب',
            'location.required' => 'الموقع مطلوب',
            'employment_type.required' => 'نوع الدوام مطلوب',
            'remote_type.required' => 'نوع العمل عن بُعد مطلوب',
            'logo_image.max' => 'حجم الشعار يجب أن يكون أقل من 2 ميجابايت',
            'salary_max.gte' => 'الحد الأعلى للراتب يجب أن يكون أكبر من أو يساوي الحد الأدنى',
        ]);
    }

    private function mergeFormArrays(Request $request, array $validated): array
    {
        $validated['responsibilities'] = $this->linesToArray($request->input('responsibilities_text'));
        $validated['requirements'] = $this->linesToArray($request->input('requirements_text'));
        $validated['benefits'] = $this->linesToArray($request->input('benefits_text'));
        $validated['skills'] = $this->csvToArray($request->input('skills_text'));
        $validated['payment_methods'] = $this->csvToArray($request->input('payment_methods_text'));
        $validated['tags'] = $this->csvToArray($request->input('tags_text'));

        $tagLabelsJson = trim((string) $request->input('tag_labels_json', ''));
        if ($tagLabelsJson !== '') {
            $decoded = json_decode($tagLabelsJson, true);
            $validated['tag_labels'] = is_array($decoded) ? $decoded : [];
        } else {
            $validated['tag_labels'] = $this->buildDefaultTagLabels($validated);
        }

        unset(
            $validated['responsibilities_text'],
            $validated['requirements_text'],
            $validated['benefits_text'],
            $validated['skills_text'],
            $validated['payment_methods_text'],
            $validated['tags_text'],
            $validated['tag_labels_json'],
            $validated['remove_logo_image']
        );

        return $validated;
    }

    private function buildDefaultTagLabels(array $data): array
    {
        $labels = [];

        if (($data['remote_type'] ?? '') === 'full-remote') {
            $labels[] = ['t' => 'عن بُعد 🌐', 'c' => 'teal'];
        } elseif (($data['remote_type'] ?? '') === 'hybrid') {
            $labels[] = ['t' => 'هجين', 'c' => 'blue'];
        }

        if (! empty($data['tech_specialty_id'])) {
            $specialty = TechSpecialty::find($data['tech_specialty_id']);
            if ($specialty) {
                $labels[] = ['t' => $specialty->name, 'c' => 'blue'];
            }
        }

        return $labels;
    }

    private function linesToArray(?string $value): array
    {
        if (! $value) {
            return [];
        }

        return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $value))));
    }

    private function csvToArray(?string $value): array
    {
        if (! $value) {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', $value))));
    }
}
