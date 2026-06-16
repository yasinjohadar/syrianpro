<?php

namespace App\Services\Backup;

use App\Models\StorageDiskMapping;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class BackupScopeResolver
{
    /**
     * @param  array<string, mixed>|null  $scope
     * @return array<string, mixed>
     */
    public function normalize(?array $scope, string $backupType = 'full'): array
    {
        $base = config('backup.default_scope', []);

        if (empty($scope)) {
            $preset = config("backup.presets.{$backupType}");
            if (is_array($preset)) {
                $scope = $preset;
            }
        }

        $merged = array_replace_recursive($base, $scope ?? []);

        return $this->applyBackupTypeFilter($merged, $backupType);
    }

    /**
     * @param  array<string, mixed>  $scope
     */
    public function shouldBackupDatabase(array $scope): bool
    {
        return (bool) Arr::get($scope, 'database', true);
    }

    /**
     * @param  array<string, mixed>  $scope
     * @return list<string> absolute paths
     */
    public function resolveFilePaths(array $scope): array
    {
        $paths = [];

        foreach (Arr::get($scope, 'files.paths', []) as $relative) {
            $absolute = $this->resolveRelativePath((string) $relative);
            if ($absolute && is_dir($absolute)) {
                $paths[] = $absolute;
            }
        }

        foreach (Arr::get($scope, 'files.include_storage_disks', []) as $diskName) {
            try {
                if (Storage::disk($diskName)->path('') !== null) {
                    $root = Storage::disk($diskName)->path('');
                    if (is_dir($root)) {
                        $paths[] = $root;
                    }
                }
            } catch (\Throwable) {
                $mapping = StorageDiskMapping::where('disk_name', $diskName)->with('primaryStorage')->first();
                if ($mapping?->primaryStorage) {
                    $config = $mapping->primaryStorage->getDecryptedConfig();
                    $root = $config['path'] ?? $config['root'] ?? null;
                    if ($root) {
                        $absolute = str_starts_with($root, DIRECTORY_SEPARATOR)
                            ? $root
                            : storage_path('app/' . ltrim($root, '/'));
                        if (is_dir($absolute)) {
                            $paths[] = $absolute;
                        }
                    }
                }
            }
        }

        return array_values(array_unique($paths));
    }

    /**
     * @param  array<string, mixed>  $scope
     * @return list<string> paths relative to base_path
     */
    public function resolveConfigFiles(array $scope): array
    {
        if (! Arr::get($scope, 'config.enabled', false)) {
            return [];
        }

        $files = Arr::get($scope, 'config.files', []);

        if (Arr::get($scope, 'config.include_env', false)) {
            $files[] = '.env';
        }

        return array_values(array_unique(array_filter($files)));
    }

    /**
     * Build scope array from HTTP request checkboxes.
     *
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    public function fromRequest(array $input): array
    {
        $preset = $input['scope_preset'] ?? null;
        if ($preset && $preset !== 'custom' && config("backup.presets.{$preset}")) {
            $scope = config("backup.presets.{$preset}");
        } else {
            $scope = [
                'database' => filter_var($input['scope_database'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'tables' => array_filter($input['scope_tables'] ?? []),
                'files' => [
                    'paths' => array_filter($input['scope_file_paths'] ?? []),
                    'include_storage_disks' => array_filter($input['scope_disks'] ?? []),
                ],
                'config' => [
                    'enabled' => filter_var($input['scope_config_enabled'] ?? false, FILTER_VALIDATE_BOOLEAN),
                    'files' => array_filter($input['scope_config_files'] ?? []),
                    'include_env' => filter_var($input['scope_include_env'] ?? false, FILTER_VALIDATE_BOOLEAN),
                ],
            ];
        }

        if (is_array($input['scope'] ?? null)) {
            $scope = array_replace_recursive($scope, $input['scope']);
        }

        if (! empty($input['scope_file_paths_text'])) {
            $lines = preg_split('/\r\n|\r|\n/', (string) $input['scope_file_paths_text']);
            $scope['files']['paths'] = array_values(array_filter(array_map('trim', $lines)));
        }

        return $scope;
    }

    public function presetOptions(): array
    {
        $presets = config('backup.presets', []);

        return collect($presets)->mapWithKeys(fn ($data, $key) => [
            $key => $data['label'] ?? $key,
        ])->all();
    }

    protected function applyBackupTypeFilter(array $scope, string $backupType): array
    {
        return match ($backupType) {
            'database' => array_replace($scope, [
                'files' => ['paths' => [], 'include_storage_disks' => []],
                'config' => ['enabled' => false, 'files' => [], 'include_env' => false],
                'database' => true,
            ]),
            'files' => array_replace($scope, [
                'database' => false,
                'config' => ['enabled' => false, 'files' => [], 'include_env' => false],
            ]),
            'config' => array_replace($scope, [
                'database' => false,
                'files' => ['paths' => [], 'include_storage_disks' => []],
                'config' => array_merge($scope['config'] ?? [], ['enabled' => true]),
            ]),
            default => $scope,
        };
    }

    protected function resolveRelativePath(string $relative): ?string
    {
        $relative = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, trim($relative, '/\\'));

        if (str_starts_with($relative, 'storage' . DIRECTORY_SEPARATOR)) {
            $absolute = base_path($relative);
        } elseif (str_starts_with($relative, 'config' . DIRECTORY_SEPARATOR)) {
            $absolute = base_path($relative);
        } else {
            $absolute = storage_path('app/' . $relative);
        }

        if (! $this->isPathAllowed($absolute)) {
            return null;
        }

        return $absolute;
    }

    protected function isPathAllowed(string $absolute): bool
    {
        $real = realpath($absolute);
        if ($real === false) {
            $parent = dirname($absolute);
            $real = realpath($parent);
            if ($real === false) {
                return false;
            }
        }

        $allowedRoots = [
            realpath(storage_path()) ?: storage_path(),
            realpath(base_path('config')) ?: base_path('config'),
        ];

        foreach ($allowedRoots as $root) {
            if ($root && str_starts_with($real, $root)) {
                return true;
            }
        }

        return false;
    }
}
