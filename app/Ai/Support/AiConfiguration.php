<?php

namespace App\Ai\Support;

use Laravel\Ai\Enums\Lab;

class AiConfiguration
{
    public static function textProvider(): Lab|string
    {
        return self::resolveProvider(config('ai.default', 'openai'));
    }

    public static function textModel(?string $capability = null): string
    {
        $capability = match ($capability) {
            'chat' => 'chat',
            'structured' => 'structured',
            default => 'text',
        };

        if ($fromRegistry = self::defaultModelForCapability($capability)) {
            return $fromRegistry;
        }

        return config("ai.models.{$capability}", 'gpt-4o-mini');
    }

    public static function imageProvider(): Lab|string
    {
        return self::resolveProvider(config('ai.default_for_images', 'openai'));
    }

    public static function imageModel(): string
    {
        return self::defaultModelForCapability('image')
            ?? config('ai.models.image', 'gpt-image-1');
    }

    public static function embeddingsProvider(): Lab|string
    {
        return self::resolveProvider(config('ai.default_for_embeddings', 'openai'));
    }

    public static function embeddingsModel(): string
    {
        return self::defaultModelForCapability('embeddings')
            ?? config('ai.models.embeddings', 'text-embedding-3-small');
    }

    public static function audioProvider(): Lab|string
    {
        return self::resolveProvider(config('ai.default_for_audio', 'openai'));
    }

    public static function audioModel(): string
    {
        return self::defaultModelForCapability('audio')
            ?? config('ai.models.audio', 'gpt-4o-mini-tts');
    }

    public static function timeout(): int
    {
        return (int) config('ai.request_timeout', 300);
    }

    public static function providerForCapability(string $capability): Lab|string
    {
        foreach (config('ai.registry', []) as $model) {
            if (! ($model['is_active'] ?? true)) {
                continue;
            }

            if (in_array($capability, $model['is_default'] ?? [], true)) {
                return self::resolveProvider($model['provider']);
            }
        }

        return match ($capability) {
            'image' => self::imageProvider(),
            'embeddings' => self::embeddingsProvider(),
            'audio' => self::audioProvider(),
            default => self::textProvider(),
        };
    }

    protected static function defaultModelForCapability(string $capability): ?string
    {
        foreach (config('ai.registry', []) as $model) {
            if (! ($model['is_active'] ?? true)) {
                continue;
            }

            if (in_array($capability, $model['is_default'] ?? [], true)) {
                return $model['model_key'];
            }
        }

        return null;
    }

    protected static function resolveProvider(string $value): Lab|string
    {
        return Lab::tryFrom($value) ?? $value;
    }
}
