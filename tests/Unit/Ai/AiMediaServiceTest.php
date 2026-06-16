<?php

use App\Services\Ai\AiMediaService;
use Tests\TestCase;

uses(TestCase::class);
use Laravel\Ai\Embeddings;
use Laravel\Ai\Image;

it('fakes embedding generation', function () {
    Embeddings::fake([
        [[0.1, 0.2, 0.3]],
    ]);

    $vectors = app(AiMediaService::class)->generateEmbeddings(['hello']);

    expect($vectors)->toHaveCount(1)
        ->and($vectors[0])->toBe([0.1, 0.2, 0.3]);
});

it('fakes image generation storage', function () {
    Image::fake();

    // Image fake returns placeholder - ensure no exception
    expect(fn () => Image::of('test')->generate())->not->toThrow(Exception::class);
});
