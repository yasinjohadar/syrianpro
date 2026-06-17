<?php

namespace App\Models\Concerns;

use App\Models\Company;

trait HasContactChannels
{
    public function socialPlatformLabel(?string $platform): string
    {
        return Company::socialPlatformMeta($platform)['label'];
    }

    public function externalUrl(string $url): string
    {
        $url = trim($url);

        if ($url === '') {
            return '';
        }

        if (preg_match('#^https?://#i', $url)) {
            return $url;
        }

        if (str_contains($url, '@') && ! str_contains($url, '://')) {
            return 'mailto:'.$url;
        }

        return 'https://'.$url;
    }
}
