<?php

namespace App\Services\Ai;

use App\Ai\Support\AiConfiguration;
use Illuminate\Support\Facades\Config;
use Laravel\Ai\Enums\Lab;
use Throwable;

use function Laravel\Ai\agent;

class AiConnectionTestService
{
    /**
     * @param  array{key?: string, url?: string}|null  $providerOverrides
     * @return array{success: bool, message: string, reply?: string, provider?: string, model?: string}
     */
    public function test(string $provider, string $modelKey, ?array $providerOverrides = null): array
    {
        $provider = trim($provider);
        $modelKey = trim($modelKey);

        if ($provider === '' || $modelKey === '') {
            return [
                'success' => false,
                'message' => 'المزود ومعرّف الموديل مطلوبان للفحص.',
            ];
        }

        if ($providerOverrides !== null) {
            $this->applyTemporaryProviderConfig($provider, $providerOverrides);
        }

        $apiKey = config("ai.providers.{$provider}.key");

        if (! filled($apiKey)) {
            return [
                'success' => false,
                'message' => 'لم يُضبط مفتاح API لهذا المزود. أضفه من «إعدادات الذكاء الاصطناعي» ثم أعد المحاولة.',
            ];
        }

        try {
            $lab = Lab::tryFrom($provider) ?? $provider;
            $timeout = min(60, max(15, AiConfiguration::timeout()));

            $response = agent(
                instructions: 'You are a connection test assistant. Reply with exactly one word: OK'
            )->prompt(
                'ping',
                provider: $lab,
                model: $modelKey,
                timeout: $timeout,
            );

            $reply = trim((string) $response);

            if ($reply === '') {
                return [
                    'success' => false,
                    'message' => 'المزود أجاب برد فارغ. تحقق من المعرّف والصلاحيات.',
                    'provider' => $provider,
                    'model' => $modelKey,
                ];
            }

            return [
                'success' => true,
                'message' => 'الاتصال ناجح — الموديل يستجيب.',
                'reply' => mb_strlen($reply) > 160 ? mb_substr($reply, 0, 160).'…' : $reply,
                'provider' => $provider,
                'model' => $modelKey,
            ];
        } catch (Throwable $e) {
            return [
                'success' => false,
                'message' => $this->formatError($e),
                'provider' => $provider,
                'model' => $modelKey,
            ];
        }
    }

    /**
     * @param  array{key?: string, url?: string}  $overrides
     */
    protected function applyTemporaryProviderConfig(string $provider, array $overrides): void
    {
        $current = config("ai.providers.{$provider}", []);

        if (filled($overrides['key'] ?? null)) {
            $current['key'] = $overrides['key'];
        }

        if (filled($overrides['url'] ?? null)) {
            $current['url'] = $overrides['url'];
        }

        Config::set("ai.providers.{$provider}", $current);
    }

    protected function formatError(Throwable $e): string
    {
        $message = $e->getMessage();

        if (str_contains($message, 'Incorrect API key') || str_contains($message, 'invalid_api_key')) {
            return 'مفتاح API غير صالح لهذا المزود.';
        }

        if (str_contains($message, 'model') && (str_contains($message, 'not found') || str_contains($message, 'does not exist'))) {
            return 'الموديل غير موجود لدى المزود. تحقق من معرّف Model ID.';
        }

        if (str_contains($message, 'insufficient_quota') || str_contains($message, 'billing')) {
            return 'رصيد أو فوترة المزود غير كافية.';
        }

        if (str_contains($message, 'rate limit') || str_contains($message, '429')) {
            return 'تم تجاوز حد الطلبات لدى المزود. حاول بعد قليل.';
        }

        if (str_contains($message, 'Connection refused') || str_contains($message, 'Could not resolve host')) {
            return 'تعذّر الوصول لخادم المزود. تحقق من الاتصال أو عنوان API.';
        }

        if (mb_strlen($message) > 220) {
            $message = mb_substr($message, 0, 220).'…';
        }

        return 'فشل الاتصال: '.$message;
    }
}
