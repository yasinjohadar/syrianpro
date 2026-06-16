<?php

/**
 * انسخ هذا الملف إلى ai-panel.php أو أنشئه من لوحة التحكم.
 * الملف ai-panel.php مستثنى من Git ويحتوي على المفاتيح والموديلات الفعلية.
 */
return [

    'default' => 'openai',
    'default_for_images' => 'openai',
    'default_for_audio' => 'openai',
    'default_for_embeddings' => 'openai',

    'models' => [
        'text' => 'gpt-4o-mini',
        'chat' => 'gpt-4o-mini',
        'structured' => 'gpt-4o',
        'image' => 'gpt-image-1',
        'embeddings' => 'text-embedding-3-small',
        'audio' => 'gpt-4o-mini-tts',
    ],

    'request_timeout' => 300,

    'registry' => [
        [
            'id' => 'openai-gpt-4o-mini',
            'name' => 'GPT-4o Mini',
            'provider' => 'openai',
            'model_key' => 'gpt-4o-mini',
            'capabilities' => ['text', 'chat'],
            'is_default' => ['text', 'chat'],
            'is_active' => true,
        ],
        [
            'id' => 'openai-gpt-4o',
            'name' => 'GPT-4o',
            'provider' => 'openai',
            'model_key' => 'gpt-4o',
            'capabilities' => ['structured'],
            'is_default' => ['structured'],
            'is_active' => true,
        ],
    ],

    'providers' => [
        'openai' => [
            'key' => null,
        ],
    ],

];
