<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Demo quick login (local development only)
    |--------------------------------------------------------------------------
    */

    'enabled' => env('DEMO_LOGIN_ENABLED', env('APP_ENV') === 'local'),

    'password' => env('DEMO_LOGIN_PASSWORD', '123456789'),

    'accounts' => [
        'admin' => [
            'email' => 'admin@admin.com',
            'label' => 'مدير النظام',
            'description' => 'لوحة الإدارة',
            'role' => 'admin',
        ],
        'company' => [
            'email' => 'company@demo.com',
            'label' => 'شركة',
            'description' => 'SyriaDev Studio',
            'role' => 'company',
        ],
        'talent' => [
            'email' => 'talent@demo.com',
            'label' => 'تقني',
            'description' => 'أحمد الخطيب',
            'role' => 'talent',
        ],
        'user' => [
            'email' => 'user@example.com',
            'label' => 'مستخدم',
            'description' => 'حساب عام',
            'role' => 'talent',
            'password' => 'password',
        ],
    ],

];
