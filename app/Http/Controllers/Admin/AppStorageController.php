<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppStorageConfig;
use App\Services\Storage\AppStorageFactory;
use App\Services\Storage\StorageConfigNormalizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AppStorageController extends Controller
{
    /**
     * قائمة أماكن التخزين
     */
    public function index()
    {
        $configs = AppStorageConfig::with('creator')
                                   ->orderBy('priority', 'desc')
                                   ->get();

        $stats = [
            'total' => AppStorageConfig::count(),
            'active' => AppStorageConfig::where('is_active', true)->count(),
            'inactive' => AppStorageConfig::where('is_active', false)->count(),
            'drivers' => AppStorageConfig::query()->distinct()->count('driver'),
        ];

        return view('admin.pages.app-storage.index', compact('configs', 'stats'));
    }

    /**
     * إضافة مكان تخزين
     */
    public function create()
    {
        try {
            $drivers = AppStorageConfig::DRIVERS;
            return view('admin.pages.app-storage.create', compact('drivers'));
        } catch (\Exception $e) {
            Log::error('Error in AppStorageController::create: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('admin.storage.index')
                ->with('error', 'حدث خطأ أثناء تحميل الصفحة: ' . $e->getMessage());
        }
    }

    /**
     * حفظ الإعدادات
     */
    public function store(Request $request)
    {
        // التحقق الأساسي من الحقول العامة
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'driver' => 'required|in:' . implode(',', array_keys(AppStorageConfig::DRIVERS)),
            'config' => 'nullable|array', // لم تعد مطلوبة دائماً
            'priority' => 'nullable|integer|min:0',
            'cdn_url' => 'nullable|url',
            'file_types' => 'nullable|array',
        ], [
            'name.required' => 'اسم الإعداد مطلوب',
            'name.string' => 'اسم الإعداد يجب أن يكون نصاً',
            'name.max' => 'اسم الإعداد لا يمكن أن يتجاوز 255 حرفاً',
            'driver.required' => 'نوع التخزين مطلوب',
            'driver.in' => 'نوع التخزين المحدد غير صالح',
            'config.array' => 'إعدادات التخزين يجب أن تكون مصفوفة',
            'priority.integer' => 'الأولوية يجب أن تكون رقماً',
            'priority.min' => 'الأولوية يجب أن تكون على الأقل 0',
            'cdn_url.url' => 'رابط CDN غير صالح',
            'file_types.array' => 'أنواع الملفات يجب أن تكون مصفوفة',
        ]);

        try {
            $driver = $validated['driver'];
            $configData = $request->input('config', []);

            // للتخزين المحلي: يمكن أن يكون config فارغاً، نضع قيمة افتراضية للمسار
            if ($driver === 'local') {
                if (empty($configData)) {
                    $configData = ['path' => 'public'];
                }
            } else {
                // لباقي الأنواع: إعدادات التخزين مطلوبة
                if (empty($configData)) {
                    return redirect()->back()
                        ->withErrors(['config' => 'إعدادات التخزين مطلوبة لهذا النوع من التخزين'])
                        ->withInput();
                }

                // التحقق من الحقول المطلوبة حسب نوع التخزين
                $requiredFields = $this->getRequiredFieldsForDriver($driver);

                foreach ($requiredFields as $field) {
                    if (empty($configData[$field])) {
                        return redirect()->back()
                            ->withErrors(['config' => "الحقل '{$field}' مطلوب لنوع التخزين '{$driver}'"])
                            ->withInput();
                    }
                }
            }

            $createData = [
                'name' => $validated['name'],
                'driver' => $driver,
                'config' => $configData,
                'priority' => $validated['priority'] ?? 0,
                'created_by' => Auth::id(),
                'is_active' => $request->has('is_active'),
                'redundancy' => $request->has('redundancy'),
                'cdn_url' => $request->input('cdn_url'),
                'file_types' => $request->input('file_types'),
            ];

            if ($request->has('pricing_config')) {
                $createData['pricing_config'] = [
                    'storage_cost_per_gb' => $request->input('pricing_config.storage_cost_per_gb'),
                    'upload_cost_per_gb' => $request->input('pricing_config.upload_cost_per_gb'),
                    'download_cost_per_gb' => $request->input('pricing_config.download_cost_per_gb'),
                ];
            }

            if ($request->has('monthly_budget')) {
                $createData['monthly_budget'] = $request->input('monthly_budget');
            }

            if ($request->has('cost_alert_threshold')) {
                $createData['cost_alert_threshold'] = $request->input('cost_alert_threshold');
            }

            AppStorageConfig::create($createData);

            return redirect()->route('admin.storage.index')
                           ->with('success', 'تم إضافة مكان التخزين بنجاح.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                           ->withErrors($e->errors())
                           ->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating app storage config: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()
                           ->with('error', 'حدث خطأ أثناء إضافة مكان التخزين: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * تعديل الإعدادات
     */
    public function edit(AppStorageConfig $config)
    {
        $drivers = AppStorageConfig::DRIVERS;
        $config->load('creator');
        return view('admin.pages.app-storage.edit', compact('config', 'drivers'));
    }

    /**
     * تحديث الإعدادات
     */
    public function update(Request $request, AppStorageConfig $config)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'driver' => 'required|in:' . implode(',', array_keys(AppStorageConfig::DRIVERS)),
            'config' => 'nullable|array',
            'priority' => 'nullable|integer|min:0',
            'cdn_url' => 'nullable|url',
            'file_types' => 'nullable|array',
        ]);

        try {
            $driver = $validated['driver'];
            $configData = $request->input('config', []);

            // دمج config مع القيم القديمة (للحفاظ على passwords)
            $oldConfig = $config->getDecryptedConfig();

            foreach ($configData as $key => $value) {
                // إذا كان الحقل فارغاً وكان password/token، احتفظ بالقيمة القديمة
                if (empty($value) && (str_contains($key, 'password') || str_contains($key, 'token') || str_contains($key, 'secret') || str_contains($key, 'key'))) {
                    if (isset($oldConfig[$key])) {
                        $configData[$key] = $oldConfig[$key];
                    }
                }
            }

            // للتخزين المحلي: إذا بقي config فارغاً بعد الدمج، عيّن path افتراضي
            if ($driver === 'local' && empty($configData)) {
                $configData = ['path' => 'public'];
            }

            // لباقي الأنواع: تأكد من وجود إعدادات تخزين
            if ($driver !== 'local') {
                if (empty($configData)) {
                    return redirect()->back()
                        ->withErrors(['config' => 'إعدادات التخزين مطلوبة لهذا النوع من التخزين'])
                        ->withInput();
                }

                $requiredFields = $this->getRequiredFieldsForDriver($driver);

                foreach ($requiredFields as $field) {
                    if (empty($configData[$field]) && empty($oldConfig[$field] ?? null)) {
                        return redirect()->back()
                            ->withErrors(['config' => "الحقل '{$field}' مطلوب لنوع التخزين '{$driver}'"])
                            ->withInput();
                    }
                }
            }

            $updateData = [
                'name' => $validated['name'],
                'driver' => $driver,
                'config' => $configData,
                'priority' => $validated['priority'] ?? 0,
                'is_active' => $request->has('is_active'),
                'redundancy' => $request->has('redundancy'),
                'cdn_url' => $request->input('cdn_url'),
                'file_types' => $request->input('file_types'),
            ];

            if ($request->has('pricing_config')) {
                $updateData['pricing_config'] = [
                    'storage_cost_per_gb' => $request->input('pricing_config.storage_cost_per_gb'),
                    'upload_cost_per_gb' => $request->input('pricing_config.upload_cost_per_gb'),
                    'download_cost_per_gb' => $request->input('pricing_config.download_cost_per_gb'),
                ];
            }

            if ($request->has('monthly_budget')) {
                $updateData['monthly_budget'] = $request->input('monthly_budget');
            }

            if ($request->has('cost_alert_threshold')) {
                $updateData['cost_alert_threshold'] = $request->input('cost_alert_threshold');
            }

            $config->update($updateData);

            return redirect()->route('admin.storage.index')
                           ->with('success', 'تم تحديث إعدادات التخزين بنجاح.');
        } catch (\Exception $e) {
            Log::error('Error updating app storage config: ' . $e->getMessage());
            return redirect()->back()
                           ->with('error', 'حدث خطأ أثناء تحديث الإعدادات: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * حذف الإعدادات
     */
    public function destroy(AppStorageConfig $config)
    {
        try {
            $config->delete();

            return redirect()->route('admin.storage.index')
                           ->with('success', 'تم حذف إعدادات التخزين بنجاح.');
        } catch (\Exception $e) {
            Log::error('Error deleting app storage config: ' . $e->getMessage());
            return redirect()->back()
                           ->with('error', 'حدث خطأ أثناء حذف الإعدادات: ' . $e->getMessage());
        }
    }

    /**
     * اختبار الاتصال (لصفحة create - بدون config موجود)
     */
    public function testConnection(\Illuminate\Http\Request $request)
    {
        try {
            $request->validate([
                'driver' => 'required|string',
                'config' => 'required|array',
            ]);

            $driver = $request->input('driver');
            $configData = StorageConfigNormalizer::normalize(
                $request->input('config', []),
                $driver
            );

            // تنظيف وtrim للـ credentials (خاصة لـ Bunny Storage)
            if ($driver === 'bunny') {
                if (isset($configData['storage_zone'])) {
                    $configData['storage_zone'] = trim($configData['storage_zone']);
                }
                if (isset($configData['api_key'])) {
                    $configData['api_key'] = trim($configData['api_key']);
                }
                if (isset($configData['pull_zone'])) {
                    $configData['pull_zone'] = trim($configData['pull_zone']);
                }
                
                // التحقق من أن الـ credentials غير فارغة
                if (empty($configData['storage_zone'])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Storage Zone Name مطلوب ولا يمكن أن يكون فارغاً',
                    ], 422);
                }
                
                if (empty($configData['api_key'])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'API Key (FTP Password) مطلوب ولا يمكن أن يكون فارغاً',
                    ], 422);
                }
            }

            // التحقق من الإعدادات المطلوبة حسب نوع التخزين
            $validationErrors = $this->validateStorageConfig($driver, $configData);
            if (!empty($validationErrors)) {
                return response()->json([
                    'success' => false,
                    'message' => 'إعدادات غير مكتملة: ' . implode(', ', $validationErrors),
                ], 422);
            }

            $result = AppStorageFactory::testConnection($driver, $configData);

            return response()->json($result, $result['success'] ? 200 : 422);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة: ' . implode(', ', $e->errors()['driver'] ?? []),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Test connection failed: ' . $e->getMessage(), [
                'driver' => $request->input('driver'),
                'error_class' => get_class($e),
                'trace' => $e->getTraceAsString(),
            ]);
            
            $errorMessage = $e->getMessage();
            
            // تحسين رسائل الخطأ العامة
            if (str_contains($errorMessage, 'not found') || str_contains($errorMessage, 'missing')) {
                $errorMessage = 'إعدادات ناقصة: يرجى التأكد من ملء جميع الحقول المطلوبة';
            }
            
            return response()->json([
                'success' => false,
                'message' => 'فشل الاتصال: ' . $errorMessage,
            ], 500);
        }
    }

    /**
     * التحقق من الإعدادات المطلوبة حسب نوع التخزين
     */
    private function validateStorageConfig(string $driver, array $configData): array
    {
        $errors = [];

        switch ($driver) {
            case 'google_drive':
                if (empty($configData['client_id'])) {
                    $errors[] = 'Client ID مطلوب';
                }
                if (empty($configData['client_secret'])) {
                    $errors[] = 'Client Secret مطلوب';
                }
                if (empty($configData['refresh_token'])) {
                    $errors[] = 'Refresh Token مطلوب';
                }
                break;

            case 's3':
            case 'digitalocean':
            case 'wasabi':
            case 'backblaze':
                if (empty($configData['access_key_id'])) {
                    $errors[] = 'Access Key ID مطلوب';
                }
                if (empty($configData['secret_access_key'])) {
                    $errors[] = 'Secret Access Key مطلوب';
                }
                if (empty($configData['bucket'])) {
                    $errors[] = 'Bucket مطلوب';
                }
                break;

            case 'cloudflare_r2':
                if (empty($configData['account_id'])) {
                    $errors[] = 'Account ID مطلوب';
                }
                if (empty($configData['access_key_id'])) {
                    $errors[] = 'Access Key ID مطلوب';
                }
                if (empty($configData['secret_access_key'])) {
                    $errors[] = 'Secret Access Key مطلوب';
                }
                if (empty($configData['bucket'])) {
                    $errors[] = 'Bucket مطلوب';
                }
                break;

            case 'dropbox':
                if (empty($configData['access_token'])) {
                    $errors[] = 'Access Token مطلوب';
                }
                break;

            case 'azure':
                if (empty($configData['account_name'])) {
                    $errors[] = 'Account Name مطلوب';
                }
                if (empty($configData['account_key'])) {
                    $errors[] = 'Account Key مطلوب';
                }
                if (empty($configData['container'])) {
                    $errors[] = 'Container مطلوب';
                }
                break;

            case 'ftp':
            case 'sftp':
                if (empty($configData['host'])) {
                    $errors[] = 'Host مطلوب';
                }
                if (empty($configData['username'])) {
                    $errors[] = 'Username مطلوب';
                }
                break;

            case 'bunny':
                if (empty($configData['storage_zone'])) {
                    $errors[] = 'Storage Zone مطلوب';
                }
                if (empty($configData['api_key'])) {
                    $errors[] = 'API Key مطلوب';
                }
                break;

            case 'local':
                // لا توجد إعدادات مطلوبة للتخزين المحلي
                break;

            default:
                // التحقق العام للحقول المطلوبة
                break;
        }

        return $errors;
    }

    /**
     * اختبار الاتصال (لصفحة edit - مع config موجود)
     */
    public function test(AppStorageConfig $config)
    {
        try {
            $result = $config->testConnection();

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في الاختبار: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * الحصول على الحقول المطلوبة حسب نوع التخزين
     */
    private function getRequiredFieldsForDriver(string $driver): array
    {
        return match($driver) {
            'local' => [], // لا توجد حقول مطلوبة للتخزين المحلي
            's3', 'digitalocean', 'wasabi', 'backblaze' => ['access_key_id', 'secret_access_key', 'bucket'],
            'cloudflare_r2' => ['account_id', 'access_key_id', 'secret_access_key', 'bucket'],
            'google_drive' => ['client_id', 'client_secret', 'refresh_token'],
            'dropbox' => ['access_token'],
            'ftp', 'sftp' => ['host', 'username', 'password'],
            'azure' => ['account_name', 'account_key', 'container'],
            'bunny' => ['storage_zone', 'api_key'],
            default => [],
        };
    }
}
