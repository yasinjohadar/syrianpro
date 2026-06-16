<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Queue
    |--------------------------------------------------------------------------
    */
    'queue' => true,

    'sync_in_local' => false,

    'job_timeout' => 600,

    /*
    |--------------------------------------------------------------------------
    | Database
    |--------------------------------------------------------------------------
    */
    'database' => [
        'connection' => env('DB_CONNECTION', 'mysql'),
        'prefer_mysqldump' => true,
        'chunk_size' => 100,
    ],

    /*
    |--------------------------------------------------------------------------
    | Default backup scope (used when scope JSON is empty)
    |--------------------------------------------------------------------------
    */
    'default_scope' => [
        'database' => true,
        'tables' => [],
        'files' => [
            'paths' => ['storage/app/public'],
            'include_storage_disks' => ['public'],
        ],
        'config' => [
            'enabled' => true,
            'files' => [
                'config/app.php',
                'config/database.php',
                'config/mail.php',
            ],
            'include_env' => false,
        ],
    ],

    'presets' => [
        'minimal' => [
            'label' => 'قاعدة البيانات فقط',
            'database' => true,
            'tables' => [],
            'files' => ['paths' => [], 'include_storage_disks' => []],
            'config' => ['enabled' => false, 'files' => [], 'include_env' => false],
        ],
        'standard' => [
            'label' => 'قياسي (قاعدة + ملفات عامة)',
            'database' => true,
            'tables' => [],
            'files' => [
                'paths' => ['storage/app/public'],
                'include_storage_disks' => ['public'],
            ],
            'config' => [
                'enabled' => true,
                'files' => ['config/app.php', 'config/database.php', 'config/mail.php'],
                'include_env' => false,
            ],
        ],
        'full' => [
            'label' => 'كامل',
            'database' => true,
            'tables' => [],
            'files' => [
                'paths' => ['storage/app/public', 'storage/app'],
                'include_storage_disks' => ['public', 'images'],
            ],
            'config' => [
                'enabled' => true,
                'files' => ['config/app.php', 'config/database.php', 'config/mail.php', 'config/filesystems.php'],
                'include_env' => false,
            ],
        ],
    ],

    'allowed_path_roots' => [
        'storage',
        'config',
    ],

    /*
    |--------------------------------------------------------------------------
    | Retention
    |--------------------------------------------------------------------------
    */
    'default_retention_days' => 30,

    'enforce_max_backups_per_storage' => true,

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'enabled' => true,
        'email' => null,
        'webhook_url' => null,
    ],

    'temp_path' => storage_path('app/backups'),

];
