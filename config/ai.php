<?php

use App\Services\Ai\AiPanelConfigService;

$defaults = require __DIR__.'/ai.defaults.php';

$panel = is_file(__DIR__.'/ai-panel.php')
    ? require __DIR__.'/ai-panel.php'
    : [];

$merged = array_replace_recursive($defaults, $panel);

if (! empty($merged['providers'])) {
    $merged['providers'] = AiPanelConfigService::decryptProviderKeys($merged['providers']);
}

return $merged;
