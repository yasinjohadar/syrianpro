<?php

/**
 * إعدادات AI الافتراضية (بدون مفاتيح API).
 * التخصيص الفعلي يُدار من لوحة التحكم ويُحفظ في config/ai-panel.php
 */
return [

    'default' => 'openai',
    'default_for_images' => 'openai',
    'default_for_audio' => 'openai',
    'default_for_transcription' => 'openai',
    'default_for_embeddings' => 'openai',
    'default_for_reranking' => 'cohere',

    'models' => [
        'text' => 'gpt-4o-mini',
        'chat' => 'gpt-4o-mini',
        'structured' => 'gpt-4o',
        'image' => 'gpt-image-1',
        'embeddings' => 'text-embedding-3-small',
        'audio' => 'gpt-4o-mini-tts',
    ],

    'request_timeout' => 300,

    'failover' => [
        'text' => ['openai', 'anthropic', 'groq'],
    ],

    'conversations' => [
        'connection' => null,
        'generate_title' => true,
        'tables' => [
            'conversations' => 'agent_conversations',
            'messages' => 'agent_conversation_messages',
        ],
    ],

    'caching' => [
        'embeddings' => [
            'cache' => false,
            'store' => 'database',
        ],
    ],

    'registry' => [],

    'providers' => [
        'anthropic' => [
            'driver' => 'anthropic',
            'key' => null,
            'url' => 'https://api.anthropic.com/v1',
        ],
        'azure' => [
            'driver' => 'azure',
            'key' => null,
            'url' => null,
            'api_version' => '2025-04-01-preview',
            'deployment' => 'gpt-4o',
            'embedding_deployment' => 'text-embedding-3-small',
            'image_deployment' => 'gpt-image-1',
        ],
        'bedrock' => [
            'driver' => 'bedrock',
            'region' => 'us-east-1',
            'key' => null,
            'access_key_id' => null,
            'secret_access_key' => null,
            'session_token' => null,
            'use_default_credential_provider' => true,
        ],
        'cohere' => [
            'driver' => 'cohere',
            'key' => null,
        ],
        'deepseek' => [
            'driver' => 'deepseek',
            'key' => null,
        ],
        'eleven' => [
            'driver' => 'eleven',
            'key' => null,
        ],
        'gemini' => [
            'driver' => 'gemini',
            'key' => null,
            'url' => 'https://generativelanguage.googleapis.com/v1beta/',
        ],
        'groq' => [
            'driver' => 'groq',
            'key' => null,
        ],
        'jina' => [
            'driver' => 'jina',
            'key' => null,
        ],
        'mistral' => [
            'driver' => 'mistral',
            'key' => null,
        ],
        'ollama' => [
            'driver' => 'ollama',
            'key' => null,
            'url' => 'http://localhost:11434',
        ],
        'openai' => [
            'driver' => 'openai',
            'key' => null,
            'url' => 'https://api.openai.com/v1',
        ],
        'openrouter' => [
            'driver' => 'openrouter',
            'key' => null,
        ],
        'voyageai' => [
            'driver' => 'voyageai',
            'key' => null,
        ],
        'xai' => [
            'driver' => 'xai',
            'key' => null,
        ],
    ],

    'supported_providers' => [
        'openai', 'anthropic', 'gemini', 'groq', 'openrouter', 'ollama',
        'azure', 'cohere', 'deepseek', 'mistral', 'xai', 'jina', 'voyageai',
    ],

    'provider_labels' => [
        'openai' => 'OpenAI',
        'anthropic' => 'Anthropic',
        'gemini' => 'Google Gemini',
        'groq' => 'Groq',
        'openrouter' => 'OpenRouter',
        'ollama' => 'Ollama (محلي)',
        'azure' => 'Azure OpenAI',
        'cohere' => 'Cohere',
        'deepseek' => 'DeepSeek',
        'mistral' => 'Mistral',
        'xai' => 'xAI',
        'jina' => 'Jina',
        'voyageai' => 'Voyage AI',
    ],

    'capability_labels' => [
        'text' => 'نص عام',
        'chat' => 'محادثة / شات',
        'structured' => 'مخرجات منظمة (كورسات، مقالات)',
        'image' => 'صور',
        'embeddings' => 'تضمينات (Embeddings)',
        'audio' => 'صوت / TTS',
    ],

];
