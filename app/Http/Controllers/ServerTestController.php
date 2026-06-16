<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ServerTestController extends Controller
{
    /**
     * عرض صفحة اختبار السيرفر — الإصدارات، قاعدة البيانات، ENV، الفرونتند.
     * الوصول عبر: /server-test?key=YOUR_SECRET_KEY
     * ضع في .env: SERVER_TEST_KEY=your_secret_key
     */
    public function index()
    {
        $key = request()->query('key');
        $envKey = config('app.server_test_key', env('SERVER_TEST_KEY'));

        if ($envKey && $key !== $envKey) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $checks = [
            'php' => $this->checkPhp(),
            'laravel' => $this->checkLaravel(),
            'database' => $this->checkDatabase(),
            'env' => $this->checkEnv(),
            'storage' => $this->checkStorage(),
            'frontend' => $this->checkFrontend(),
            'extensions' => $this->checkExtensions(),
        ];

        $checks['all_ok'] = collect($checks)->every(fn ($c) => ($c['ok'] ?? true) !== false);

        if (request()->wantsJson()) {
            return response()->json($checks);
        }

        return response()->view('server-test', $checks)->header('Content-Type', 'text/html; charset=utf-8');
    }

    private function checkPhp(): array
    {
        return [
            'ok' => true,
            'version' => PHP_VERSION,
            'min_required' => '8.2',
            'sapi' => php_sapi_name(),
        ];
    }

    private function checkLaravel(): array
    {
        return [
            'ok' => true,
            'version' => app()->version(),
        ];
    }

    private function checkDatabase(): array
    {
        try {
            DB::connection()->getPdo();
            $driver = config('database.default');
            $dbName = config("database.connections.{$driver}.database");
            $tables = [];
            try {
                $tables = DB::select('SHOW TABLES');
            } catch (\Throwable) {
                if ($driver === 'sqlite') {
                    $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table'");
                }
            }
            return [
                'ok' => true,
                'driver' => $driver,
                'database' => $dbName,
                'tables_count' => count($tables),
            ];
        } catch (\Throwable $e) {
            return [
                'ok' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    private function checkEnv(): array
    {
        $safe = [
            'APP_NAME', 'APP_ENV', 'APP_DEBUG', 'APP_URL', 'APP_LOCALE',
            'DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME',
            'CACHE_STORE', 'SESSION_DRIVER', 'FILESYSTEM_DISK', 'QUEUE_CONNECTION',
            'LOG_CHANNEL', 'MAIL_MAILER', 'BROADCAST_CONNECTION',
        ];
        $values = [];
        foreach ($safe as $k) {
            $values[$k] = env($k);
        }
        $loaded = file_exists(base_path('.env'));
        return [
            'ok' => $loaded && config('app.key'),
            'env_file_exists' => $loaded,
            'app_key_set' => !empty(config('app.key')),
            'values' => $values,
        ];
    }

    private function checkStorage(): array
    {
        $writable = is_writable(storage_path()) && is_writable(storage_path('framework'));
        $linkExists = File::exists(public_path('storage'));
        return [
            'ok' => $writable,
            'storage_writable' => $writable,
            'storage_link_exists' => $linkExists,
        ];
    }

    private function checkFrontend(): array
    {
        $css = public_path('frontend/assets/css/style.css');
        $js = public_path('frontend/assets/js/main.js');
        $logo = public_path('frontend/assets/images/logo.png');
        $cssExists = File::exists($css);
        $jsExists = File::exists($js);
        $logoExists = File::exists($logo);
        return [
            'ok' => $cssExists && $jsExists,
            'css_exists' => $cssExists,
            'js_exists' => $jsExists,
            'logo_exists' => $logoExists,
            'public_path' => public_path(),
        ];
    }

    private function checkExtensions(): array
    {
        $required = ['pdo', 'mbstring', 'openssl', 'tokenizer', 'xml', 'ctype', 'json', 'fileinfo'];
        $missing = [];
        foreach ($required as $ext) {
            if (!extension_loaded($ext)) {
                $missing[] = $ext;
            }
        }
        return [
            'ok' => empty($missing),
            'missing' => $missing,
            'loaded' => array_diff($required, $missing),
        ];
    }
}
