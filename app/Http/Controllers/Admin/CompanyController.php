<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\RespondsWithAjaxTable;
use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    use RespondsWithAjaxTable;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:company-list')->only('index');
        $this->middleware('permission:company-create')->only(['create', 'store']);
        $this->middleware('permission:company-edit')->only(['edit', 'update']);
        $this->middleware('permission:company-delete')->only('destroy');
        $this->middleware('permission:company-toggle')->only('toggleActive');
    }

    public function index(Request $request)
    {
        $data = $this->buildIndexData($request);

        if ($response = $this->ajaxTableResponse(
            $request,
            $data,
            'admin.pages.companies.partials.list',
            'admin.pages.companies.partials.modals'
        )) {
            return $response;
        }

        return view('admin.pages.companies.index', $data);
    }

    public function create()
    {
        return view('admin.pages.companies.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateCompany($request);

        if ($request->hasFile('logo_image')) {
            $validated['logo_image'] = $request->file('logo_image')->store('companies/logos', 'public');
        }

        $validated = $this->mergeFormData($request, $validated);

        if (! isset($validated['order'])) {
            $validated['order'] = (Company::max('order') ?? 0) + 1;
        }

        $validated['slug'] = Company::generateUniqueSlug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_verified'] = $request->boolean('is_verified');
        $validated['is_remote_friendly'] = $request->boolean('is_remote_friendly');
        $validated['is_syria_friendly'] = $request->boolean('is_syria_friendly');

        Company::create($validated);

        return redirect()->route('admin.companies.index')->with('success', 'تم إنشاء الشركة بنجاح');
    }

    public function edit(Company $company)
    {
        return view('admin.pages.companies.create', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $validated = $this->validateCompany($request, $company->id);

        if ($request->hasFile('logo_image')) {
            if ($company->logo_image) {
                Storage::disk('public')->delete($company->logo_image);
            }
            $validated['logo_image'] = $request->file('logo_image')->store('companies/logos', 'public');
        }

        if ($request->boolean('remove_logo_image') && $company->logo_image) {
            Storage::disk('public')->delete($company->logo_image);
            $validated['logo_image'] = null;
        }

        $validated = $this->mergeFormData($request, $validated);

        if ($company->name !== $validated['name']) {
            $validated['slug'] = Company::generateUniqueSlug($validated['name'], $company->id);
        }

        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_verified'] = $request->boolean('is_verified');
        $validated['is_remote_friendly'] = $request->boolean('is_remote_friendly');
        $validated['is_syria_friendly'] = $request->boolean('is_syria_friendly');

        $company->update($validated);

        return redirect()->route('admin.companies.index')->with('success', 'تم تحديث الشركة بنجاح');
    }

    public function destroy(Company $company)
    {
        if ($company->logo_image) {
            Storage::disk('public')->delete($company->logo_image);
        }

        $company->delete();

        return redirect()->route('admin.companies.index')->with('success', 'تم حذف الشركة بنجاح');
    }

    public function toggleActive(Company $company)
    {
        $company->update(['is_active' => ! $company->is_active]);

        return redirect()->back()->with('success', $company->is_active ? 'تم تفعيل الشركة' : 'تم تعطيل الشركة');
    }

    private function buildIndexData(Request $request): array
    {
        $query = Company::query();

        if ($request->filled('query')) {
            $search = $request->input('query');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sector', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->input('is_active') === '1');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $stats = [
            'total' => Company::count(),
            'active' => Company::where('is_active', true)->count(),
            'inactive' => Company::where('is_active', false)->count(),
            'featured' => Company::where('is_featured', true)->where('is_active', true)->count(),
            'filtered' => (clone $query)->count(),
        ];

        $companies = $query->ordered()->paginate(10)->withQueryString();

        return compact('companies', 'stats');
    }

    private function validateCompany(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'sector' => 'required|string|max:255',
            'category' => 'required|string|max:50',
            'logo' => 'nullable|string|max:100',
            'logo_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
            'jobs_count' => 'nullable|integer|min:0',
            'rating' => 'nullable|numeric|min:0|max:5',
            'location' => 'required|string|max:255',
            'founded' => 'nullable|string|max:20',
            'team_size' => 'nullable|string|max:50',
            'website' => 'nullable|string|max:255',
            'timezone' => 'nullable|string|max:50',
            'about' => 'nullable|string',
            'mission' => 'nullable|string',
            'payment_methods_text' => 'nullable|string',
            'values_text' => 'nullable|string',
            'perks_text' => 'nullable|string',
            'culture_text' => 'nullable|string',
            'tech_stack_text' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'is_verified' => 'nullable|boolean',
            'is_remote_friendly' => 'nullable|boolean',
            'is_syria_friendly' => 'nullable|boolean',
            'remove_logo_image' => 'nullable|boolean',
        ], [
            'name.required' => 'اسم الشركة مطلوب',
            'sector.required' => 'القطاع مطلوب',
            'location.required' => 'الموقع مطلوب',
        ]);
    }

    private function mergeFormData(Request $request, array $validated): array
    {
        $validated['payment_methods'] = $this->csvToArray($request->input('payment_methods_text'));
        $validated['values'] = $this->linesToArray($request->input('values_text'));
        $validated['perks'] = $this->linesToArray($request->input('perks_text'));
        $validated['culture'] = $this->linesToArray($request->input('culture_text'));
        $validated['tech_stack'] = $this->csvToArray($request->input('tech_stack_text'));

        unset(
            $validated['payment_methods_text'],
            $validated['values_text'],
            $validated['perks_text'],
            $validated['culture_text'],
            $validated['tech_stack_text'],
            $validated['remove_logo_image']
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

    private function linesToArray(?string $value): array
    {
        if (! $value) {
            return [];
        }

        return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $value))));
    }
}
