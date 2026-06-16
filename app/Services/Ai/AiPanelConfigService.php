<?php

namespace App\Services\Ai;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AiPanelConfigService
{
    public const ENCRYPTED_PREFIX = 'enc:';

    public function panelPath(): string
    {
        return config_path('ai-panel.php');
    }

    public function panelExists(): bool
    {
        return is_file($this->panelPath());
    }

    public function readPanel(): array
    {
        if (! $this->panelExists()) {
            return [];
        }

        $panel = require $this->panelPath();

        return is_array($panel) ? $panel : [];
    }

    public function readPanelForAdmin(): array
    {
        $panel = $this->readPanel();

        if (! empty($panel['providers'])) {
            foreach ($panel['providers'] as $slug => $config) {
                if (! empty($config['key']) && str_starts_with((string) $config['key'], self::ENCRYPTED_PREFIX)) {
                    $panel['providers'][$slug]['key'] = '';
                    $panel['providers'][$slug]['has_key'] = true;
                }
            }
        }

        return $panel;
    }

    public function savePanel(array $panel): void
    {
        if (! empty($panel['providers'])) {
            $existing = $this->readPanel();
            $panel['providers'] = $this->prepareProvidersForStorage(
                $panel['providers'],
                $existing['providers'] ?? []
            );
        }

        $this->writePanelFile($panel);
    }

    public function ensurePanelFile(): void
    {
        if ($this->panelExists()) {
            return;
        }

        $example = config_path('ai-panel.example.php');
        if (is_file($example)) {
            File::copy($example, $this->panelPath());

            return;
        }

        $this->writePanelFile([]);
    }

    public function mergedConfig(): array
    {
        $defaults = require config_path('ai.defaults.php');
        $panel = $this->readPanel();
        $merged = array_replace_recursive($defaults, $panel);

        if (! empty($merged['providers'])) {
            $merged['providers'] = self::decryptProviderKeys($merged['providers']);
        }

        return $merged;
    }

    public function registry(): array
    {
        return config('ai.registry', []);
    }

    public function activeRegistry(): array
    {
        return array_values(array_filter(
            $this->registry(),
            fn (array $model) => ($model['is_active'] ?? true) === true
        ));
    }

    public function findRegistryModel(string $id): ?array
    {
        foreach ($this->registry() as $model) {
            if (($model['id'] ?? '') === $id) {
                return $model;
            }
        }

        return null;
    }

    public function findRegistryByProviderAndKey(string $provider, string $modelKey): ?array
    {
        foreach ($this->registry() as $model) {
            if (($model['provider'] ?? '') === $provider
                && ($model['model_key'] ?? '') === $modelKey) {
                return $model;
            }
        }

        return null;
    }

    public function registryHasProviderModel(string $provider, string $modelKey, ?string $exceptId = null): bool
    {
        $existing = $this->findRegistryByProviderAndKey($provider, $modelKey);

        if (! $existing) {
            return false;
        }

        return $exceptId === null || ($existing['id'] ?? '') !== $exceptId;
    }

    public function addRegistryModel(array $data): string
    {
        $panel = $this->readPanel();
        $registry = $panel['registry'] ?? [];

        $id = $data['id'] ?? $this->generateModelId($data['provider'], $data['model_key'], $registry);
        $entry = [
            'id' => $id,
            'name' => $data['name'],
            'provider' => $data['provider'],
            'model_key' => $data['model_key'],
            'capabilities' => array_values($data['capabilities'] ?? []),
            'is_default' => array_values($data['is_default'] ?? []),
            'is_active' => (bool) ($data['is_active'] ?? true),
        ];

        $registry = $this->upsertRegistryEntry($registry, $entry);
        $panel['registry'] = $registry;
        $panel = $this->syncModelDefaults($panel);

        $this->savePanel($panel);

        return $id;
    }

    public function updateRegistryModel(string $id, array $data): void
    {
        $panel = $this->readPanel();
        $registry = $panel['registry'] ?? [];
        $found = false;

        foreach ($registry as $index => $model) {
            if (($model['id'] ?? '') !== $id) {
                continue;
            }

            $registry[$index] = [
                'id' => $id,
                'name' => $data['name'],
                'provider' => $data['provider'],
                'model_key' => $data['model_key'],
                'capabilities' => array_values($data['capabilities'] ?? []),
                'is_default' => array_values($data['is_default'] ?? []),
                'is_active' => (bool) ($data['is_active'] ?? true),
            ];
            $found = true;
            break;
        }

        if (! $found) {
            throw new \InvalidArgumentException("Model [{$id}] not found in registry.");
        }

        $panel['registry'] = $registry;
        $panel = $this->syncModelDefaults($panel);
        $this->savePanel($panel);
    }

    public function deleteRegistryModel(string $id): void
    {
        $panel = $this->readPanel();
        $panel['registry'] = array_values(array_filter(
            $panel['registry'] ?? [],
            fn (array $model) => ($model['id'] ?? '') !== $id
        ));
        $panel = $this->syncModelDefaults($panel);
        $this->savePanel($panel);
    }

    public function importFromCatalog(string $provider, string $modelKey): ?string
    {
        $catalog = config('ai-catalog.'.$provider, []);
        foreach ($catalog as $item) {
            if (($item['model_key'] ?? '') !== $modelKey) {
                continue;
            }

            return $this->addRegistryModel([
                'name' => $item['name'],
                'provider' => $provider,
                'model_key' => $modelKey,
                'capabilities' => $item['capabilities'] ?? [],
                'is_active' => true,
            ]);
        }

        return null;
    }

    public function catalogForProvider(string $provider): array
    {
        return config('ai-catalog.'.$provider, []);
    }

    public static function decryptProviderKeys(array $providers): array
    {
        foreach ($providers as $slug => $config) {
            if (! is_array($config)) {
                continue;
            }

            $key = $config['key'] ?? null;
            if (filled($key) && str_starts_with((string) $key, self::ENCRYPTED_PREFIX)) {
                try {
                    $providers[$slug]['key'] = Crypt::decryptString(substr((string) $key, strlen(self::ENCRYPTED_PREFIX)));
                } catch (\Throwable) {
                    $providers[$slug]['key'] = null;
                }
            }
        }

        return $providers;
    }

    protected function prepareProvidersForStorage(array $incoming, array $existing): array
    {
        $stored = [];

        foreach ($incoming as $slug => $config) {
            if (! is_array($config)) {
                continue;
            }

            $key = trim((string) ($config['key'] ?? ''));
            $previous = $existing[$slug]['key'] ?? null;

            if ($key === '' && filled($previous)) {
                $stored[$slug] = array_merge($config, ['key' => $previous]);
            } elseif ($key !== '') {
                $stored[$slug] = array_merge($config, [
                    'key' => self::ENCRYPTED_PREFIX.Crypt::encryptString($key),
                ]);
            } else {
                $stored[$slug] = array_merge($config, ['key' => null]);
            }

            unset($stored[$slug]['has_key']);
        }

        return $stored;
    }

    protected function syncModelDefaults(array $panel): array
    {
        $fileDefaults = require config_path('ai.defaults.php');
        $models = array_merge($fileDefaults['models'] ?? [], $panel['models'] ?? []);

        foreach ($panel['registry'] ?? [] as $entry) {
            if (! ($entry['is_active'] ?? true)) {
                continue;
            }

            foreach ($entry['is_default'] ?? [] as $capability) {
                $models[$capability] = $entry['model_key'];

                if (in_array($capability, ['text', 'chat', 'structured'], true)) {
                    $panel['default'] = $entry['provider'];
                }
                if ($capability === 'image') {
                    $panel['default_for_images'] = $entry['provider'];
                }
                if ($capability === 'embeddings') {
                    $panel['default_for_embeddings'] = $entry['provider'];
                }
                if ($capability === 'audio') {
                    $panel['default_for_audio'] = $entry['provider'];
                }
            }
        }

        $panel['models'] = $models;

        return $panel;
    }

    protected function upsertRegistryEntry(array $registry, array $entry): array
    {
        foreach ($registry as $index => $model) {
            if (($model['id'] ?? '') === $entry['id']) {
                $registry[$index] = $entry;

                return $registry;
            }
        }

        $registry[] = $entry;

        return $registry;
    }

    protected function generateModelId(string $provider, string $modelKey, array $registry = []): string
    {
        $base = Str::slug($provider.'-'.$modelKey);
        $ids = array_column($registry, 'id');
        $id = $base;
        $suffix = 1;

        while (in_array($id, $ids, true)) {
            $id = $base.'-'.$suffix;
            $suffix++;
        }

        return $id;
    }

    protected function writePanelFile(array $panel): void
    {
        $export = var_export(Arr::sortRecursive($panel), true);
        $content = "<?php\n\nreturn {$export};\n";

        File::ensureDirectoryExists(dirname($this->panelPath()));
        File::put($this->panelPath(), $content);
    }
}
