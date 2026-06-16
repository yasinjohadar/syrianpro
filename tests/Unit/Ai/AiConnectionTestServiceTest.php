<?php

use App\Services\Ai\AiConnectionTestService;
use Laravel\Ai\AnonymousAgent;
use Tests\TestCase;

uses(TestCase::class);

it('returns success when the model responds', function () {
    AnonymousAgent::fake(['OK']);

    config([
        'ai.providers.openai.key' => 'sk-test-key',
    ]);

    $result = app(AiConnectionTestService::class)->test('openai', 'gpt-4o-mini');

    expect($result['success'])->toBeTrue()
        ->and($result['message'])->toContain('ناجح');
});

it('fails when api key is missing', function () {
    config([
        'ai.providers.openai.key' => null,
    ]);

    $result = app(AiConnectionTestService::class)->test('openai', 'gpt-4o-mini');

    expect($result['success'])->toBeFalse()
        ->and($result['message'])->toContain('مفتاح API');
});
