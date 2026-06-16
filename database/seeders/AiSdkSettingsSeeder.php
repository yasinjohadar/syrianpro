<?php

namespace Database\Seeders;

use App\Models\AISetting;
use Illuminate\Database\Seeder;

class AiSdkSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            [
                'key' => 'text_provider',
                'value' => config('ai.default', 'openai'),
                'type' => 'string',
                'description' => 'مزود النص الافتراضي (openai, anthropic, groq, gemini, openrouter)',
                'category' => 'sdk',
            ],
            [
                'key' => 'text_model',
                'value' => config('ai.models.text', 'gpt-4o-mini'),
                'type' => 'string',
                'description' => 'موديل النص العام',
                'category' => 'sdk',
            ],
            [
                'key' => 'chat_model',
                'value' => config('ai.models.chat', 'gpt-4o-mini'),
                'type' => 'string',
                'description' => 'موديل المحادثة / الشات بوت',
                'category' => 'sdk',
            ],
            [
                'key' => 'structured_model',
                'value' => config('ai.models.structured', 'gpt-4o'),
                'type' => 'string',
                'description' => 'موديل المخرجات المنظمة (كورسات، مقالات)',
                'category' => 'sdk',
            ],
            [
                'key' => 'image_provider',
                'value' => config('ai.default_for_images', 'openai'),
                'type' => 'string',
                'description' => 'مزود توليد الصور',
                'category' => 'sdk',
            ],
            [
                'key' => 'image_model',
                'value' => config('ai.models.image', 'gpt-image-1'),
                'type' => 'string',
                'description' => 'موديل توليد الصور',
                'category' => 'sdk',
            ],
            [
                'key' => 'embeddings_provider',
                'value' => config('ai.default_for_embeddings', 'openai'),
                'type' => 'string',
                'description' => 'مزود التضمينات (embeddings)',
                'category' => 'sdk',
            ],
            [
                'key' => 'embeddings_model',
                'value' => config('ai.models.embeddings', 'text-embedding-3-small'),
                'type' => 'string',
                'description' => 'موديل التضمينات',
                'category' => 'sdk',
            ],
            [
                'key' => 'request_timeout',
                'value' => '300',
                'type' => 'integer',
                'description' => 'مهلة الطلب بالثواني',
                'category' => 'sdk',
            ],
        ];

        foreach ($defaults as $setting) {
            AISetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
