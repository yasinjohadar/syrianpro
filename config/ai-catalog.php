<?php

/**
 * كتالوج موديلات مقترحة لإضافتها بسرعة من لوحة التحكم.
 */
return [
    'openai' => [
        ['name' => 'GPT-4o', 'model_key' => 'gpt-4o', 'capabilities' => ['text', 'chat', 'structured']],
        ['name' => 'GPT-4o Mini', 'model_key' => 'gpt-4o-mini', 'capabilities' => ['text', 'chat', 'structured']],
        ['name' => 'GPT Image 1', 'model_key' => 'gpt-image-1', 'capabilities' => ['image']],
        ['name' => 'text-embedding-3-small', 'model_key' => 'text-embedding-3-small', 'capabilities' => ['embeddings']],
        ['name' => 'gpt-4o-mini-tts', 'model_key' => 'gpt-4o-mini-tts', 'capabilities' => ['audio']],
    ],
    'anthropic' => [
        ['name' => 'Claude Sonnet 4', 'model_key' => 'claude-sonnet-4-20250514', 'capabilities' => ['text', 'chat', 'structured']],
        ['name' => 'Claude Haiku 3.5', 'model_key' => 'claude-3-5-haiku-20241022', 'capabilities' => ['text', 'chat']],
    ],
    'gemini' => [
        ['name' => 'Gemini 2.0 Flash', 'model_key' => 'gemini-2.0-flash', 'capabilities' => ['text', 'chat', 'structured']],
        ['name' => 'Gemini 2.0 Flash Lite', 'model_key' => 'gemini-2.0-flash-lite', 'capabilities' => ['text', 'chat']],
    ],
    'groq' => [
        ['name' => 'Llama 3.3 70B', 'model_key' => 'llama-3.3-70b-versatile', 'capabilities' => ['text', 'chat', 'structured']],
        ['name' => 'Llama 3.1 8B', 'model_key' => 'llama-3.1-8b-instant', 'capabilities' => ['text', 'chat']],
    ],
    'openrouter' => [
        ['name' => 'OpenRouter Auto', 'model_key' => 'openrouter/auto', 'capabilities' => ['text', 'chat']],
        ['name' => 'Claude 3.5 Sonnet (via OpenRouter)', 'model_key' => 'anthropic/claude-3.5-sonnet', 'capabilities' => ['text', 'chat', 'structured']],
        ['name' => 'Llama 3.1 70B (via OpenRouter)', 'model_key' => 'meta-llama/llama-3.1-70b-instruct', 'capabilities' => ['text', 'chat']],
    ],
    'ollama' => [
        ['name' => 'Llama 3.2', 'model_key' => 'llama3.2', 'capabilities' => ['text', 'chat']],
    ],
    'mistral' => [
        ['name' => 'Mistral Large', 'model_key' => 'mistral-large-latest', 'capabilities' => ['text', 'chat', 'structured']],
    ],
    'deepseek' => [
        ['name' => 'DeepSeek Chat', 'model_key' => 'deepseek-chat', 'capabilities' => ['text', 'chat', 'structured']],
    ],
];
