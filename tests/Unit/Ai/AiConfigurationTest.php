<?php

use App\Ai\Support\AiConfiguration;
use Laravel\Ai\Enums\Lab;
use Tests\TestCase;

uses(TestCase::class);

it('reads text provider and model from config', function () {
    config([
        'ai.default' => 'openai',
        'ai.models.text' => 'gpt-4o-mini',
        'ai.registry' => [],
    ]);

    expect(AiConfiguration::textProvider())->toBe(Lab::OpenAI)
        ->and(AiConfiguration::textModel())->toBe('gpt-4o-mini');
});

it('prefers registry default for capability', function () {
    config([
        'ai.models.chat' => 'fallback-model',
        'ai.registry' => [
            [
                'model_key' => 'registry-chat-model',
                'is_active' => true,
                'is_default' => ['chat'],
            ],
        ],
    ]);

    expect(AiConfiguration::textModel('chat'))->toBe('registry-chat-model');
});
