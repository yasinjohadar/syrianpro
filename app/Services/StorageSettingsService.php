<?php

namespace App\Services;

use App\Models\SystemSetting;
use App\Services\Storage\StorageRuntimeConfig;

class StorageSettingsService
{
    public const GROUP = 'storage';

    public const KEYS = [
        'storage_driver_mode',
        'storage_fallback_disk',
        'storage_default_cloud_disk',
        'storage_sync_queue',
        'storage_sync_retries',
        'storage_sync_backoff_seconds',
        'storage_max_upload_size_mb',
        'storage_media_presigned_urls',
        'storage_deduplication_enabled',
        'storage_auto_generate_thumbnails',
        'storage_auto_optimize_images',
        'storage_virus_scan_enabled',
        'storage_log_uploads',
        'storage_retention_days',
    ];

    protected ?array $cache = null;

    public function getSettings(): array
    {
        if ($this->cache !== null) {
            return $this->cache;
        }

        $stored = SystemSetting::where('group', self::GROUP)
            ->get()
            ->keyBy('key')
            ->map(fn ($row) => $row->value)
            ->toArray();

        $this->cache = [
            'storage_driver_mode' => $stored['storage_driver_mode'] ?? 'cloud_first',
            'storage_fallback_disk' => $stored['storage_fallback_disk'] ?? 'public',
            'storage_default_cloud_disk' => $stored['storage_default_cloud_disk'] ?? '',
            'storage_sync_queue' => $stored['storage_sync_queue'] ?? 'storage-sync',
            'storage_sync_retries' => (int) ($stored['storage_sync_retries'] ?? 3),
            'storage_sync_backoff_seconds' => (int) ($stored['storage_sync_backoff_seconds'] ?? 30),
            'storage_max_upload_size_mb' => (int) ($stored['storage_max_upload_size_mb'] ?? 500),
            'storage_media_presigned_urls' => $this->toBool($stored['storage_media_presigned_urls'] ?? 'true'),
            'storage_deduplication_enabled' => $this->toBool($stored['storage_deduplication_enabled'] ?? 'true'),
            'storage_auto_generate_thumbnails' => $this->toBool($stored['storage_auto_generate_thumbnails'] ?? 'true'),
            'storage_auto_optimize_images' => $this->toBool($stored['storage_auto_optimize_images'] ?? 'true'),
            'storage_virus_scan_enabled' => $this->toBool($stored['storage_virus_scan_enabled'] ?? 'false'),
            'storage_log_uploads' => $this->toBool($stored['storage_log_uploads'] ?? 'true'),
            'storage_retention_days' => (int) ($stored['storage_retention_days'] ?? 30),
        ];

        return $this->cache;
    }

    public function updateSettings(array $data): void
    {
        $allowed = array_flip(self::KEYS);

        foreach ($data as $key => $value) {
            if (! isset($allowed[$key])) {
                continue;
            }

            if (in_array($key, [
                'storage_media_presigned_urls',
                'storage_deduplication_enabled',
                'storage_auto_generate_thumbnails',
                'storage_auto_optimize_images',
                'storage_virus_scan_enabled',
                'storage_log_uploads',
            ], true)) {
                $type = 'boolean';
                $value = $this->toBool($value) ? 'true' : 'false';
            } elseif (in_array($key, [
                'storage_sync_retries',
                'storage_sync_backoff_seconds',
                'storage_max_upload_size_mb',
                'storage_retention_days',
            ], true)) {
                $type = 'integer';
                $value = (string) (int) $value;
            } else {
                $type = 'string';
                $value = trim((string) $value);
            }

            SystemSetting::set($key, $value, $type, self::GROUP);
        }

        $this->cache = null;
        StorageRuntimeConfig::resetApplicationCache();
        StorageRuntimeConfig::applyFromDatabase();
    }

    public function initializeDefaults(): void
    {
        if (SystemSetting::ofGroup(self::GROUP)->exists()) {
            StorageRuntimeConfig::applyFromDatabase();

            return;
        }

        // القيم الافتراضية تُنشأ عبر migration؛ إن لم تُنشأ نطبّق الحد الأدنى
        SystemSetting::set('storage_driver_mode', 'cloud_first', 'string', self::GROUP);
        SystemSetting::set('storage_fallback_disk', 'public', 'string', self::GROUP);

        StorageRuntimeConfig::resetApplicationCache();
        StorageRuntimeConfig::applyFromDatabase();
    }

    public function driverModes(): array
    {
        return [
            'cloud_first' => 'سحابة أولاً (مع fallback محلي)',
            'cloud_only' => 'سحابة فقط',
            'local_first' => 'محلي أولاً + مزامنة للسحابة',
            'dual_write' => 'كتابة مزدوجة (محلي + سحابة)',
            'local_only' => 'محلي فقط',
        ];
    }

    protected function toBool(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}
