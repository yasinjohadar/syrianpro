<?php

return [
    'default' => 'contact',

    'sections' => [
        'contact' => [
            'label' => 'التواصل',
            'icon' => 'ri-phone-line',
            'description' => 'معلومات التواصل المعروضة في الموقع (البريد، الهاتف، العنوان)',
            'partial' => 'admin.settings.site.partials.contact',
            'keys' => [
                'site_email',
                'site_phone',
                'site_whatsapp',
                'site_address',
                'site_working_hours',
            ],
        ],
        'social' => [
            'label' => 'وسائل التواصل',
            'icon' => 'ri-share-line',
            'description' => 'روابط حسابات السوشيال ميديا المعروضة في الموقع',
            'partial' => 'admin.settings.site.partials.social',
            'keys' => [
                'facebook_url',
                'youtube_url',
                'instagram_url',
                'linkedin_url',
                'github_url',
                'telegram_url',
            ],
        ],
    ],
];
