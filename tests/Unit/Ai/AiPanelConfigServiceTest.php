<?php

use App\Services\Ai\AiPanelConfigService;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

uses(TestCase::class);

it('writes and reads panel config file', function () {
    $path = config_path('ai-panel.php');
    $backup = null;

    if (File::exists($path)) {
        $backup = File::get($path);
        File::delete($path);
    }

    try {
        $service = app(AiPanelConfigService::class);
        $service->savePanel([
            'default' => 'openai',
            'models' => ['text' => 'gpt-4o-mini'],
            'registry' => [],
            'providers' => [
                'openai' => ['key' => 'test-secret-key'],
            ],
        ]);

        expect(File::exists($path))->toBeTrue();

        $panel = $service->readPanel();
        expect($panel['default'])->toBe('openai')
            ->and($panel['providers']['openai']['key'])->toStartWith(AiPanelConfigService::ENCRYPTED_PREFIX);

        $decrypted = AiPanelConfigService::decryptProviderKeys($panel['providers']);
        expect($decrypted['openai']['key'])->toBe('test-secret-key');
    } finally {
        File::delete($path);
        if ($backup !== null) {
            File::put($path, $backup);
        }
    }
});
