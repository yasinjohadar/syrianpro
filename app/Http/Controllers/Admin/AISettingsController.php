<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Ai\AiPanelConfigService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class AISettingsController extends Controller
{
    public function __construct(
        protected AiPanelConfigService $panelConfig
    ) {}

    public function index()
    {
        $this->panelConfig->ensurePanelFile();

        $panel = $this->panelConfig->readPanelForAdmin();
        $defaults = require config_path('ai.defaults.php');

        $supportedProviders = config('ai.supported_providers', []);
        $providerLabels = config('ai.provider_labels', []);
        $capabilityLabels = config('ai.capability_labels', []);

        $providerConfigs = [];
        foreach ($supportedProviders as $slug) {
            $providerConfigs[$slug] = array_merge(
                $defaults['providers'][$slug] ?? ['driver' => $slug],
                $panel['providers'][$slug] ?? []
            );
        }

        return view('admin.ai.settings.index', [
            'panel' => $panel,
            'defaults' => $defaults,
            'providerConfigs' => $providerConfigs,
            'supportedProviders' => $supportedProviders,
            'providerLabels' => $providerLabels,
            'capabilityLabels' => $capabilityLabels,
            'panelExists' => $this->panelConfig->panelExists(),
            'panelPath' => $this->panelConfig->panelPath(),
        ]);
    }

    public function update(Request $request)
    {
        $supported = implode(',', config('ai.supported_providers', []));

        $validated = $request->validate([
            'default' => "required|string|in:{$supported}",
            'default_for_images' => "required|string|in:{$supported}",
            'default_for_audio' => "required|string|in:{$supported}",
            'default_for_embeddings' => "required|string|in:{$supported}",
            'request_timeout' => 'required|integer|min:30|max:600',
            'models.text' => 'required|string|max:191',
            'models.chat' => 'required|string|max:191',
            'models.structured' => 'required|string|max:191',
            'models.image' => 'required|string|max:191',
            'models.embeddings' => 'required|string|max:191',
            'models.audio' => 'required|string|max:191',
            'providers' => 'nullable|array',
            'providers.*.key' => 'nullable|string|max:500',
            'providers.*.url' => 'nullable|string|max:500',
        ]);

        try {
            $existing = $this->panelConfig->readPanel();

            $panel = array_replace_recursive($existing, [
                'default' => $validated['default'],
                'default_for_images' => $validated['default_for_images'],
                'default_for_audio' => $validated['default_for_audio'],
                'default_for_embeddings' => $validated['default_for_embeddings'],
                'request_timeout' => (int) $validated['request_timeout'],
                'models' => $validated['models'],
            ]);

            if (! empty($validated['providers'])) {
                $panel['providers'] = array_replace_recursive(
                    $existing['providers'] ?? [],
                    $validated['providers']
                );
            }

            $this->panelConfig->savePanel($panel);

            if (app()->configurationIsCached()) {
                Artisan::call('config:clear');
            }

            return redirect()
                ->route('admin.ai.settings.index')
                ->with('success', 'تم حفظ الإعدادات في ملف config/ai-panel.php بنجاح.');
        } catch (\Exception $e) {
            Log::error('Error updating AI panel config: '.$e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء حفظ الإعدادات: '.$e->getMessage())
                ->withInput();
        }
    }
}
