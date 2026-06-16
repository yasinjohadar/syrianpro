<?php

namespace App\Services\Ai;

use App\Ai\Support\AiConfiguration;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Laravel\Ai\Audio;
use Laravel\Ai\Embeddings;
use Laravel\Ai\Image;

class AiMediaService
{
    /**
     * توليد صورة من وصف نصي.
     */
    public function generateImage(string $prompt, ?string $disk = 'public'): ?string
    {
        try {
            $response = Image::of($prompt)
                ->generate(
                    provider: AiConfiguration::imageProvider(),
                    model: AiConfiguration::imageModel(),
                );

            return $response->storePublicly('ai/images/'.uniqid('img_').'.png', $disk);
        } catch (\Exception $e) {
            Log::error('AI image generation failed: '.$e->getMessage());

            throw $e;
        }
    }

    /**
     * توليد تضمينات نصية (embeddings) لمجموعة نصوص.
     *
     * @param  string[]  $inputs
     * @return array<int, array<int, float>>
     */
    public function generateEmbeddings(array $inputs): array
    {
        $response = Embeddings::for($inputs)
            ->generate(
                provider: AiConfiguration::embeddingsProvider(),
                model: AiConfiguration::embeddingsModel(),
            );

        return $response->embeddings;
    }

    /**
     * تحويل نص إلى صوت (TTS).
     */
    public function textToSpeech(string $text, ?string $disk = 'public'): ?string
    {
        try {
            $response = Audio::of($text)
                ->generate(
                    provider: AiConfiguration::audioProvider(),
                    model: AiConfiguration::audioModel(),
                );

            return $response->storePublicly('ai/audio/'.uniqid('audio_').'.mp3', $disk);
        } catch (\Exception $e) {
            Log::error('AI TTS failed: '.$e->getMessage());

            throw $e;
        }
    }
}
