<?php

namespace App\Http\Controllers\Concerns;

trait ParsesContactChannels
{
    protected function parseLabeledRows(?array $rows, string $valueKey): array
    {
        if (! is_array($rows)) {
            return [];
        }

        $result = [];

        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }

            $value = trim($row[$valueKey] ?? '');

            if ($value === '') {
                continue;
            }

            $result[] = [
                'label' => trim($row['label'] ?? ''),
                $valueKey => $value,
            ];
        }

        return $result;
    }

    protected function parseSocialLinks(?array $rows): array
    {
        if (! is_array($rows)) {
            return [];
        }

        $result = [];

        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }

            $url = trim($row['url'] ?? '');

            if ($url === '') {
                continue;
            }

            $platform = trim($row['platform'] ?? 'other') ?: 'other';

            $item = [
                'platform' => $platform,
                'url' => $url,
            ];

            $label = trim($row['label'] ?? '');

            if ($label !== '') {
                $item['label'] = $label;
            }

            $result[] = $item;
        }

        return $result;
    }

    protected function legacyWebsiteFromWebsites(array $websites): ?string
    {
        if ($websites === []) {
            return null;
        }

        $url = $websites[0]['url'];

        return preg_replace('#^https?://#i', '', rtrim($url, '/'));
    }

    protected function legacyTalentLinks(array $socialLinks, array $contactWebsites): array
    {
        $links = [];

        foreach ($socialLinks as $link) {
            $platform = $link['platform'] ?? '';

            if (in_array($platform, ['github', 'linkedin'], true)) {
                $links[$platform] = $link['url'];
            }
        }

        foreach ($contactWebsites as $website) {
            if (! empty($website['url'])) {
                $links['portfolio'] = $website['url'];
                break;
            }
        }

        return array_filter($links, fn ($value) => filled($value));
    }
}
