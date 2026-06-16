<?php

namespace App\Services\Storage;

use App\Models\AppStorageConfig;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class AppStorageFactory
{
    /**
     * إنشاء Laravel Storage disk من AppStorageConfig
     */
    public static function create(AppStorageConfig $config): Filesystem
    {
        $freshConfig = $config->fresh();

        $driverConfig = StorageConfigNormalizer::normalize(
            $freshConfig->getDecryptedConfig(),
            $freshConfig->driver
        );

        $diskName = 'app_storage_'.$freshConfig->id.'_'.md5(json_encode($driverConfig));
        $diskConfig = self::buildDiskConfig($freshConfig->driver, $driverConfig);

        if ($freshConfig->cdn_url) {
            $diskConfig['url'] = rtrim($freshConfig->cdn_url, '/');
        }

        Config::set("filesystems.disks.{$diskName}", $diskConfig);

        return Storage::disk($diskName);
    }

    /**
     * اختبار الاتصال بإعدادات غير محفوظة (صفحة الإنشاء).
     */
    public static function testConnection(string $driver, array $rawConfig): array
    {
        try {
            $config = StorageConfigNormalizer::normalize($rawConfig, $driver);
            $diskName = 'test_storage_'.uniqid('', true);
            $diskConfig = self::buildDiskConfig($driver, $config, forTest: true);

            Config::set("filesystems.disks.{$diskName}", $diskConfig);
            $disk = Storage::disk($diskName);

            $testPath = '_connection_test/'.uniqid('', true).'.txt';
            $payload = 'connection-test-'.now()->toIso8601String();

            $options = self::isS3Compatible($driver)
                ? ['visibility' => 'private']
                : [];

            $written = $disk->put($testPath, $payload, $options);

            if (! $written) {
                return [
                    'success' => false,
                    'message' => self::formatSilentFailureHint($driver, $config),
                ];
            }

            try {
                $disk->delete($testPath);
            } catch (Throwable $deleteException) {
                Log::debug('Storage test file cleanup failed: '.$deleteException->getMessage());
            }

            return [
                'success' => true,
                'message' => 'الاتصال ناجح ✓',
            ];
        } catch (Throwable $e) {
            Log::warning('Storage connection test failed', [
                'driver' => $driver,
                'error' => $e->getMessage(),
                'class' => $e::class,
            ]);

            return [
                'success' => false,
                'message' => 'فشل الاتصال: '.self::humanizeConnectionError($driver, $e),
            ];
        }
    }

    /**
     * بناء مصفوفة إعدادات قرص Flysystem.
     */
    public static function buildDiskConfig(string $driver, array $config, bool $forTest = false): array
    {
        $diskConfig = match ($driver) {
            'local' => self::getLocalConfig($config),
            's3' => self::getS3Config($config),
            'google_drive' => self::getGoogleDriveConfig($config),
            'dropbox' => self::getDropboxConfig($config),
            'azure' => self::getAzureConfig($config),
            'ftp', 'sftp' => self::getFTPConfig($config),
            'digitalocean' => self::getDigitalOceanConfig($config),
            'wasabi' => self::getWasabiConfig($config),
            'backblaze' => self::getBackblazeConfig($config),
            'cloudflare_r2' => self::getCloudflareR2Config($config),
            'bunny' => self::getBunnyConfig($config),
            default => throw new \Exception("نوع التخزين غير مدعوم: {$driver}"),
        };

        if ($forTest) {
            $diskConfig['throw'] = true;
            $diskConfig['report'] = true;
        }

        return $diskConfig;
    }

    private static function isS3Compatible(string $driver): bool
    {
        return in_array($driver, ['s3', 'digitalocean', 'wasabi', 'backblaze', 'cloudflare_r2'], true);
    }

    private static function formatSilentFailureHint(string $driver, array $config): string
    {
        $hints = ['لم يتمكن النظام من كتابة ملف الاختبار على التخزين.'];

        if (self::isS3Compatible($driver)) {
            $hints[] = 'تحقق من: اسم الـ Bucket، المنطقة (Region)، صلاحيات PutObject/DeleteObject للمفتاح.';
            if (! empty($config['endpoint'])) {
                $hints[] = 'لـ MinIO وغيره: جرّب تفعيل أو إلغاء «Use Path Style Endpoint».';
            } else {
                $hints[] = 'لـ Amazon S3: عادةً يُترك Path Style غير مفعّل.';
            }
            $hints[] = 'إذا كان الـ Bucket يمنع ACL، تأكد أن السياسة تسمح بالرفع بدون صلاحيات عامة.';
        }

        return implode(' ', $hints);
    }

    public static function humanizeConnectionError(string $driver, Throwable $e): string
    {
        $message = $e->getMessage();
        $lower = strtolower($message);

        if (str_contains($lower, 'nosuchbucket') || str_contains($lower, 'bucket does not exist')) {
            return 'الـ Bucket غير موجود أو الاسم غير صحيح.';
        }

        if (str_contains($lower, 'invalidaccesskeyid') || str_contains($lower, 'invalid access key')) {
            return 'مفتاح Access Key غير صحيح.';
        }

        if (str_contains($lower, 'signaturedoesnotmatch') || str_contains($lower, 'signature we calculated')) {
            return 'Secret Access Key غير صحيح أو المنطقة (Region) لا تطابق الـ Bucket.';
        }

        if (str_contains($lower, 'accessdenied') || str_contains($lower, 'access denied') || str_contains($lower, '403')) {
            return 'رفض الوصول (403): المفتاح لا يملك صلاحية الكتابة على هذا الـ Bucket.';
        }

        if (str_contains($lower, 'could not resolve host') || str_contains($lower, 'name or service not known')) {
            return 'تعذّر الوصول إلى عنوان Endpoint — تحقق من الرابط والاتصال بالإنترنت.';
        }

        if (str_contains($lower, 'timed out') || str_contains($lower, 'timeout')) {
            return 'انتهت مهلة الاتصال — تحقق من الشبكة أو عنوان Endpoint.';
        }

        if (str_contains($lower, 'unauthorized') || str_contains($lower, '401')) {
            return $driver === 'bunny'
                ? 'فشل المصادقة: تحقق من Storage Zone و API Key.'
                : 'فشل المصادقة: تحقق من بيانات الاعتماد.';
        }

        if (str_contains($lower, 'accesscontrollistnotsupported') || str_contains($lower, 'acl')) {
            return 'الـ Bucket لا يدعم ACL العامة — استخدم سياسة Bucket تسمح بالرفع للمفتاح فقط (بدون ACL public).';
        }

        return $message !== '' ? $message : 'خطأ غير معروف أثناء الاتصال.';
    }

    private static function getLocalConfig(array $config): array
    {
        return [
            'driver' => 'local',
            'root' => storage_path('app/'.($config['path'] ?? 'public')),
            'visibility' => 'public',
            'throw' => false,
        ];
    }

    private static function getS3Config(array $config): array
    {
        $url = isset($config['url']) ? trim((string) $config['url']) : '';
        $endpoint = isset($config['endpoint']) ? trim((string) $config['endpoint']) : '';

        return [
            'driver' => 's3',
            'key' => $config['access_key_id'] ?? '',
            'secret' => $config['secret_access_key'] ?? '',
            'region' => $config['region'] ?? 'us-east-1',
            'bucket' => $config['bucket'] ?? '',
            'url' => $url !== '' ? $url : null,
            'endpoint' => $endpoint !== '' ? $endpoint : null,
            'use_path_style_endpoint' => StorageConfigNormalizer::pathStyleEndpoint($config),
            'throw' => false,
        ];
    }

    private static function getGoogleDriveConfig(array $config): array
    {
        return [
            'driver' => 'google',
            'clientId' => $config['client_id'] ?? '',
            'clientSecret' => $config['client_secret'] ?? '',
            'refreshToken' => $config['refresh_token'] ?? '',
            'folder' => $config['folder_id'] ?? null,
            'throw' => true,
        ];
    }

    private static function getDropboxConfig(array $config): array
    {
        return [
            'driver' => 'dropbox',
            'authorizationToken' => $config['access_token'] ?? '',
            'throw' => true,
        ];
    }

    private static function getAzureConfig(array $config): array
    {
        return [
            'driver' => 'azure',
            'accountName' => $config['account_name'] ?? '',
            'accountKey' => $config['account_key'] ?? '',
            'container' => $config['container'] ?? '',
            'endpoint' => ! empty($config['endpoint']) ? $config['endpoint'] : null,
            'throw' => true,
        ];
    }

    private static function getFTPConfig(array $config): array
    {
        $protocol = $config['protocol'] ?? 'ftp';

        if ($protocol === 'sftp') {
            return [
                'driver' => 'sftp',
                'host' => $config['host'] ?? '',
                'username' => $config['username'] ?? '',
                'password' => $config['password'] ?? '',
                'port' => $config['port'] ?? 22,
                'root' => $config['root'] ?? '/',
                'timeout' => 30,
                'throw' => true,
            ];
        }

        return [
            'driver' => 'ftp',
            'host' => $config['host'] ?? '',
            'username' => $config['username'] ?? '',
            'password' => $config['password'] ?? '',
            'port' => $config['port'] ?? 21,
            'root' => $config['root'] ?? '/',
            'passive' => StorageConfigNormalizer::toBool($config['passive'] ?? true),
            'ssl' => StorageConfigNormalizer::toBool($config['use_tls'] ?? false),
            'timeout' => 30,
            'throw' => true,
        ];
    }

    private static function getDigitalOceanConfig(array $config): array
    {
        $region = $config['region'] ?? 'nyc3';

        return [
            'driver' => 's3',
            'key' => $config['access_key_id'] ?? '',
            'secret' => $config['secret_access_key'] ?? '',
            'region' => $region,
            'bucket' => $config['bucket'] ?? '',
            'endpoint' => "https://{$region}.digitaloceanspaces.com",
            'use_path_style_endpoint' => true,
            'throw' => false,
        ];
    }

    private static function getWasabiConfig(array $config): array
    {
        $region = $config['region'] ?? 'us-east-1';

        return [
            'driver' => 's3',
            'key' => $config['access_key_id'] ?? '',
            'secret' => $config['secret_access_key'] ?? '',
            'region' => $region,
            'bucket' => $config['bucket'] ?? '',
            'endpoint' => "https://s3.{$region}.wasabisys.com",
            'use_path_style_endpoint' => true,
            'throw' => false,
        ];
    }

    private static function getBackblazeConfig(array $config): array
    {
        $region = $config['region'] ?? 'us-west-000';

        return [
            'driver' => 's3',
            'key' => $config['access_key_id'] ?? '',
            'secret' => $config['secret_access_key'] ?? '',
            'region' => $region,
            'bucket' => $config['bucket'] ?? '',
            'endpoint' => "https://s3.{$region}.backblazeb2.com",
            'use_path_style_endpoint' => true,
            'throw' => false,
        ];
    }

    private static function getCloudflareR2Config(array $config): array
    {
        $accountId = $config['account_id'] ?? '';

        return [
            'driver' => 's3',
            'key' => $config['access_key_id'] ?? '',
            'secret' => $config['secret_access_key'] ?? '',
            'region' => 'auto',
            'bucket' => $config['bucket'] ?? '',
            'endpoint' => "https://{$accountId}.r2.cloudflarestorage.com",
            'use_path_style_endpoint' => true,
            'throw' => false,
        ];
    }

    private static function getBunnyConfig(array $config): array
    {
        return [
            'driver' => 'bunnycdn',
            'storage_zone' => $config['storage_zone'] ?? '',
            'api_key' => $config['api_key'] ?? '',
            'region' => $config['region'] ?? 'de',
            'pull_zone' => $config['pull_zone'] ?? '',
            'throw' => true,
        ];
    }
}
