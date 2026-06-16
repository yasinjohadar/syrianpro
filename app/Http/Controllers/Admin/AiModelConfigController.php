<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Ai\AiConnectionTestService;
use App\Services\Ai\AiPanelConfigService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AiModelConfigController extends Controller
{
    public function __construct(
        protected AiPanelConfigService $panelConfig
    ) {}

    public function index()
    {
        $this->panelConfig->ensurePanelFile();

        $registry = $this->panelConfig->registry();
        $providerLabels = config('ai.provider_labels', []);
        $capabilityLabels = config('ai.capability_labels', []);

        $collection = collect($registry);
        $stats = [
            'total' => $collection->count(),
            'active' => $collection->filter(fn ($m) => $m['is_active'] ?? true)->count(),
            'inactive' => $collection->filter(fn ($m) => ! ($m['is_active'] ?? true))->count(),
            'providers' => $collection->pluck('provider')->unique()->count(),
        ];

        return view('admin.ai.models.index', compact(
            'registry',
            'providerLabels',
            'capabilityLabels',
            'stats'
        ));
    }

    public function create(Request $request)
    {
        $this->panelConfig->ensurePanelFile();

        $mode = $request->query('mode', 'catalog');
        if (! in_array($mode, ['catalog', 'custom'], true)) {
            $mode = 'catalog';
        }

        return view('admin.ai.models.create', array_merge($this->formData(), [
            'mode' => $mode,
        ]));
    }

    public function store(Request $request)
    {
        $data = $this->validateModel($request);

        try {
            $this->panelConfig->addRegistryModel($data);
            $this->saveProviderApiKeyIfPresent($request, $data['provider']);
            $this->refreshConfig();

            return redirect()
                ->route('admin.ai.models.index')
                ->with('success', 'تمت إضافة الموديل بنجاح.');
        } catch (\Exception $e) {
            Log::error('Error adding AI model: '.$e->getMessage());

            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function edit(string $model)
    {
        $entry = $this->panelConfig->findRegistryModel($model);

        if (! $entry) {
            return redirect()
                ->route('admin.ai.models.index')
                ->with('error', 'الموديل غير موجود.');
        }

        return view('admin.ai.models.edit', array_merge($this->formData(), [
            'entry' => $entry,
        ]));
    }

    public function update(Request $request, string $model)
    {
        if (! $this->panelConfig->findRegistryModel($model)) {
            return redirect()
                ->route('admin.ai.models.index')
                ->with('error', 'الموديل غير موجود.');
        }

        $data = $this->validateModel($request, $model);

        try {
            $this->panelConfig->updateRegistryModel($model, $data);
            $this->saveProviderApiKeyIfPresent($request, $data['provider']);
            $this->refreshConfig();

            return redirect()
                ->route('admin.ai.models.index')
                ->with('success', 'تم تحديث الموديل بنجاح.');
        } catch (\Exception $e) {
            Log::error('Error updating AI model: '.$e->getMessage());

            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function destroy(string $model)
    {
        try {
            $this->panelConfig->deleteRegistryModel($model);
            $this->refreshConfig();

            return redirect()
                ->route('admin.ai.models.index')
                ->with('success', 'تم حذف الموديل.');
        } catch (\Exception $e) {
            Log::error('Error deleting AI model: '.$e->getMessage());

            return back()->with('error', $e->getMessage());
        }
    }

    public function testConnection(Request $request, AiConnectionTestService $connectionTest)
    {
        $supported = implode(',', config('ai.supported_providers', []));

        $validated = $request->validate([
            'provider' => "required|string|in:{$supported}",
            'model_key' => 'required|string|max:191',
            'provider_key' => 'nullable|string|max:500',
        ]);

        $overrides = null;
        if ($request->filled('provider_key')) {
            $overrides = ['key' => $request->input('provider_key')];
        }

        $result = $connectionTest->test(
            $validated['provider'],
            $validated['model_key'],
            $overrides
        );

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    public function importCatalog(Request $request)
    {
        $validated = $request->validate([
            'provider' => 'required|string',
            'model_key' => 'required|string',
        ]);

        $id = $this->panelConfig->importFromCatalog(
            $validated['provider'],
            $validated['model_key']
        );

        if (! $id) {
            return back()->with('error', 'لم يُعثر على الموديل في الكتالوج.');
        }

        $this->refreshConfig();

        return redirect()
            ->route('admin.ai.models.edit', $id)
            ->with('success', 'تم استيراد الموديل من الكتالوج. راجع الإعدادات واحفظ.');
    }

    protected function validateModel(Request $request, ?string $exceptId = null): array
    {
        $capabilities = array_keys(config('ai.capability_labels', []));

        $validated = $request->validate([
            'add_mode' => 'nullable|in:catalog,custom',
            'name' => 'required|string|max:191',
            'provider' => 'required|string|in:'.implode(',', config('ai.supported_providers', [])),
            'model_key' => ['required', 'string', 'max:191', 'regex:/^[^\s]+$/u'],
            'capabilities' => 'required|array|min:1',
            'capabilities.*' => 'in:'.implode(',', $capabilities),
            'is_default' => 'nullable|array',
            'is_default.*' => 'in:'.implode(',', $capabilities),
            'is_active' => 'boolean',
        ], [
            'name.required' => 'اسم الموديل مطلوب.',
            'model_key.required' => 'معرّف الموديل عند المزود مطلوب.',
            'model_key.regex' => 'معرّف الموديل لا يجب أن يحتوي على مسافات.',
            'capabilities.required' => 'اختر قدرة واحدة على الأقل.',
        ]);

        $validated['model_key'] = trim($validated['model_key']);
        $validated['name'] = trim($validated['name']);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_default'] = $request->input('is_default', []);
        unset($validated['add_mode']);

        if ($this->panelConfig->registryHasProviderModel(
            $validated['provider'],
            $validated['model_key'],
            $exceptId
        )) {
            throw ValidationException::withMessages([
                'model_key' => 'هذا الموديل مسجّل مسبقاً لنفس المزود. عدّل الموديل الحالي أو غيّر المعرّف.',
            ]);
        }

        return $validated;
    }

    protected function formData(): array
    {
        $this->panelConfig->ensurePanelFile();

        $panel = $this->panelConfig->readPanelForAdmin();
        $defaults = require config_path('ai.defaults.php');
        $providerConfigs = [];

        foreach (config('ai.supported_providers', []) as $slug) {
            $providerConfigs[$slug] = array_merge(
                $defaults['providers'][$slug] ?? ['driver' => $slug],
                $panel['providers'][$slug] ?? []
            );
        }

        return [
            'supportedProviders' => config('ai.supported_providers', []),
            'providerLabels' => config('ai.provider_labels', []),
            'capabilityLabels' => config('ai.capability_labels', []),
            'catalog' => config('ai-catalog', []),
            'providerConfigs' => $providerConfigs,
        ];
    }

    protected function saveProviderApiKeyIfPresent(Request $request, string $provider): void
    {
        $key = trim((string) $request->input('provider_api_key', ''));

        if ($key === '') {
            return;
        }

        $panel = $this->panelConfig->readPanel();
        $panel['providers'] = $panel['providers'] ?? [];
        $panel['providers'][$provider] = array_merge(
            $panel['providers'][$provider] ?? ['driver' => $provider],
            ['key' => $key]
        );

        $this->panelConfig->savePanel($panel);
    }

    protected function refreshConfig(): void
    {
        if (app()->configurationIsCached()) {
            Artisan::call('config:clear');
        }
    }
}
