<?php

namespace App\Services;

use App\Models\SystemSetting;

class BackupSettingsService
{
    public const GROUP = 'backup';

    public const KEYS = [
        'notifications_enabled',
        'notify_email',
        'webhook_url',
        'use_queue',
        'sync_in_local',
        'default_retention_days',
        'prefer_mysqldump',
        'job_timeout',
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
            'notifications_enabled' => $this->toBool($stored['notifications_enabled'] ?? 'true'),
            'notify_email' => $stored['notify_email'] ?? '',
            'webhook_url' => $stored['webhook_url'] ?? '',
            'use_queue' => $this->toBool($stored['use_queue'] ?? 'true'),
            'sync_in_local' => $this->toBool($stored['sync_in_local'] ?? 'false'),
            'default_retention_days' => (int) ($stored['default_retention_days'] ?? 30),
            'prefer_mysqldump' => $this->toBool($stored['prefer_mysqldump'] ?? 'true'),
            'job_timeout' => max(60, (int) ($stored['job_timeout'] ?? 600)),
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

            if (in_array($key, ['notifications_enabled', 'use_queue', 'sync_in_local', 'prefer_mysqldump'], true)) {
                $type = 'boolean';
                $value = $this->toBool($value) ? 'true' : 'false';
            } elseif (in_array($key, ['default_retention_days', 'job_timeout'], true)) {
                $type = 'integer';
                $value = (string) (int) $value;
            } else {
                $type = 'string';
                $value = trim((string) $value);
            }

            SystemSetting::set($key, $value, $type, self::GROUP);
        }

        $this->cache = null;
        $this->applyToConfig();
    }

    public function initializeDefaults(): void
    {
        if (SystemSetting::ofGroup(self::GROUP)->exists()) {
            $this->applyToConfig();

            return;
        }

        $defaults = [
            'notifications_enabled' => 'true',
            'notify_email' => '',
            'webhook_url' => '',
            'use_queue' => 'true',
            'sync_in_local' => 'false',
            'default_retention_days' => '30',
            'prefer_mysqldump' => 'true',
            'job_timeout' => '600',
        ];

        foreach ($defaults as $key => $value) {
            $type = in_array($key, ['notifications_enabled', 'use_queue', 'sync_in_local', 'prefer_mysqldump'], true)
                ? 'boolean'
                : (in_array($key, ['default_retention_days', 'job_timeout'], true) ? 'integer' : 'string');
            SystemSetting::set($key, $value, $type, self::GROUP);
        }

        $this->cache = null;
        $this->applyToConfig();
    }

    public function applyToConfig(): void
    {
        $s = $this->getSettings();

        config([
            'backup.queue' => $s['use_queue'],
            'backup.sync_in_local' => $s['sync_in_local'],
            'backup.job_timeout' => $s['job_timeout'],
            'backup.default_retention_days' => $s['default_retention_days'],
            'backup.database.prefer_mysqldump' => $s['prefer_mysqldump'],
            'backup.notifications.enabled' => $s['notifications_enabled'],
            'backup.notifications.email' => $s['notify_email'] ?: null,
            'backup.notifications.webhook_url' => $s['webhook_url'] ?: null,
        ]);
    }

    public function notificationsEnabled(): bool
    {
        return $this->getSettings()['notifications_enabled'];
    }

    public function notifyEmail(): ?string
    {
        $email = trim($this->getSettings()['notify_email']);

        return $email !== '' ? $email : null;
    }

    public function webhookUrl(): ?string
    {
        $url = trim($this->getSettings()['webhook_url']);

        return $url !== '' ? $url : null;
    }

    public function useQueue(): bool
    {
        return $this->getSettings()['use_queue'];
    }

    public function syncInLocal(): bool
    {
        return $this->getSettings()['sync_in_local'];
    }

    public function defaultRetentionDays(): int
    {
        return $this->getSettings()['default_retention_days'];
    }

    public function preferMysqldump(): bool
    {
        return $this->getSettings()['prefer_mysqldump'];
    }

    public function jobTimeout(): int
    {
        return $this->getSettings()['job_timeout'];
    }

    protected function toBool(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}
