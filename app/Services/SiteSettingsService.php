<?php

namespace App\Services;

use App\Models\SystemSetting;

class SiteSettingsService
{
    public const GROUP = 'site';

    /** Keys allowed for site settings */
    public const KEYS = [
        'site_email',
        'site_phone',
        'site_whatsapp',
        'site_address',
        'site_working_hours',
        'facebook_url',
        'youtube_url',
        'instagram_url',
        'linkedin_url',
        'github_url',
        'telegram_url',
    ];

    /**
     * Get site settings from database.
     */
    public function getSettings(): array
    {
        $settings = SystemSetting::where('group', self::GROUP)
            ->get()
            ->keyBy('key')
            ->map(fn ($setting) => $setting->value)
            ->toArray();

        return [
            'site_email' => $settings['site_email'] ?? 'info@yasinjokhadar.net',
            'site_phone' => $settings['site_phone'] ?? '+963 XXX XXX XXX',
            'site_whatsapp' => $settings['site_whatsapp'] ?? '963XXXXXXXXX',
            'site_address' => $settings['site_address'] ?? 'سوريا',
            'site_working_hours' => $settings['site_working_hours'] ?? 'السبت - الخميس: 9:00 ص - 6:00 م',
            'facebook_url' => $settings['facebook_url'] ?? 'https://facebook.com',
            'youtube_url' => $settings['youtube_url'] ?? 'https://youtube.com',
            'instagram_url' => $settings['instagram_url'] ?? 'https://instagram.com',
            'linkedin_url' => $settings['linkedin_url'] ?? 'https://linkedin.com',
            'github_url' => $settings['github_url'] ?? 'https://github.com',
            'telegram_url' => $settings['telegram_url'] ?? 'https://t.me',
        ];
    }

    /**
     * Update site settings in database.
     */
    public function updateSettings(array $data): void
    {
        $allowed = array_flip(self::KEYS);
        foreach ($data as $key => $value) {
            if (!isset($allowed[$key])) {
                continue;
            }
            $value = is_string($value) ? trim($value) : (string) $value;
            SystemSetting::set($key, $value, 'string', self::GROUP);
        }
    }

    /**
     * Initialize default settings if none exist for site group.
     */
    public function initializeDefaults(): void
    {
        if (SystemSetting::ofGroup(self::GROUP)->exists()) {
            return;
        }
        $defaults = $this->getSettings();
        foreach ($defaults as $key => $value) {
            SystemSetting::set($key, $value, 'string', self::GROUP);
        }
    }
}
