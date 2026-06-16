<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogTagController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\ConsultationRequestController;
use App\Http\Controllers\Admin\NewsletterSubscriberController;
use App\Http\Controllers\Admin\AppStorageController;
use App\Http\Controllers\Admin\AppStorageAnalyticsController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\BackupScheduleController;
use App\Http\Controllers\Admin\BackupStorageController;
use App\Http\Controllers\Admin\BackupStorageAnalyticsController;
use App\Http\Controllers\Admin\StorageDiskMappingController;
use App\Http\Controllers\Admin\WhatsAppSettingsController;
use App\Http\Controllers\Admin\WhatsAppMessageController;
use App\Http\Controllers\Admin\WhatsAppWebController;
use App\Http\Controllers\Admin\WhatsAppWebSettingsController;
use App\Http\Controllers\Admin\WhatsAppWebhookController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\JobApplicationController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\TalentController;
use App\Http\Controllers\Admin\TechSpecialtyController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\BackupSettingsController;
use App\Http\Controllers\Admin\StorageSettingsController;
use App\Http\Controllers\Admin\StorageMigrationController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\MediaMonitoringController;
use App\Http\Controllers\Admin\DashboardController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['auth', 'check.user.active'])->prefix('admin')->name('admin.')->group(function () {
    // لوحة التحكم الرئيسية
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // المستخدمون
    Route::resource('users', UserController::class);
    Route::post('users/{user}/update-password', [UserController::class, 'updatePassword'])->name('users.update-password');
    Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // التخصصات التقنية
    Route::resource('tech-specialties', TechSpecialtyController::class)
        ->parameters(['tech-specialties' => 'techSpecialty']);
    Route::post('tech-specialties/{techSpecialty}/toggle-active', [TechSpecialtyController::class, 'toggleActive'])
        ->name('tech-specialties.toggle-active');

    // الوظائف
    Route::resource('jobs', JobController::class)
        ->parameters(['jobs' => 'job']);
    Route::post('jobs/{job}/toggle-active', [JobController::class, 'toggleActive'])
        ->name('jobs.toggle-active');

    // طلبات التوظيف
    Route::get('job-applications', [JobApplicationController::class, 'index'])->name('job-applications.index');
    Route::get('job-applications/{jobApplication}', [JobApplicationController::class, 'show'])->name('job-applications.show');
    Route::patch('job-applications/{jobApplication}/status', [JobApplicationController::class, 'updateStatus'])->name('job-applications.update-status');
    Route::delete('job-applications/{jobApplication}', [JobApplicationController::class, 'destroy'])->name('job-applications.destroy');

    // المواهب
    Route::resource('talents', TalentController::class);
    Route::post('talents/{talent}/toggle-active', [TalentController::class, 'toggleActive'])
        ->name('talents.toggle-active');

    // الشركات
    Route::resource('companies', CompanyController::class);
    Route::post('companies/{company}/toggle-active', [CompanyController::class, 'toggleActive'])
        ->name('companies.toggle-active');

    // الصلاحيات والأدوار
    Route::resource('roles', RoleController::class);

    // Contact messages (from contact form)
    Route::get('contact-messages', [ContactMessageController::class, 'index'])->name('contact-messages.index');
    Route::get('contact-messages/{contactMessage}', [ContactMessageController::class, 'show'])->name('contact-messages.show');
    Route::delete('contact-messages/{contactMessage}', [ContactMessageController::class, 'destroy'])->name('contact-messages.destroy');

    // Consultation requests (from consultation booking form)
    Route::get('consultation-requests', [ConsultationRequestController::class, 'index'])->name('consultation-requests.index');
    Route::get('consultation-requests/{consultationRequest}', [ConsultationRequestController::class, 'show'])->name('consultation-requests.show');
    Route::delete('consultation-requests/{consultationRequest}', [ConsultationRequestController::class, 'destroy'])->name('consultation-requests.destroy');

    // Newsletter subscribers
    Route::get('newsletter-subscribers', [NewsletterSubscriberController::class, 'index'])->name('newsletter-subscribers.index');
    Route::get('newsletter-subscribers/export', [NewsletterSubscriberController::class, 'export'])->name('newsletter-subscribers.export');
    Route::delete('newsletter-subscribers/{newsletterSubscriber}', [NewsletterSubscriberController::class, 'destroy'])->name('newsletter-subscribers.destroy');

    // Blog routes
    Route::prefix('blog')->name('blog.')->group(function () {
        // Blog Posts routes
        Route::resource('posts', BlogPostController::class);
        Route::post('posts/{post}/toggle-featured', [BlogPostController::class, 'toggleFeatured'])->name('posts.toggle-featured');
        Route::post('posts/{post}/toggle-publish', [BlogPostController::class, 'togglePublish'])->name('posts.toggle-publish');
        Route::delete('posts/{post}/featured-image', [BlogPostController::class, 'deleteFeaturedImage'])->name('posts.delete-featured-image');
        
        // Blog Categories routes
        Route::resource('categories', BlogCategoryController::class);
        Route::post('categories/{category}/toggle-active', [BlogCategoryController::class, 'toggleActive'])->name('categories.toggle-active');
        
        // Blog Tags routes
        Route::resource('tags', BlogTagController::class);
    });



    // ========== Email Settings Routes ==========
    Route::prefix('settings/email')->name('settings.email.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\EmailSettingController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\EmailSettingController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\EmailSettingController::class, 'store'])->name('store');
        Route::post('/test-temp', [\App\Http\Controllers\Admin\EmailSettingController::class, 'testTemp'])->name('test-temp');
        Route::get('/{emailSetting}/edit', [\App\Http\Controllers\Admin\EmailSettingController::class, 'edit'])->name('edit');
        Route::put('/{emailSetting}', [\App\Http\Controllers\Admin\EmailSettingController::class, 'update'])->name('update');
        Route::delete('/{emailSetting}', [\App\Http\Controllers\Admin\EmailSettingController::class, 'destroy'])->name('destroy');
        Route::post('/{emailSetting}/activate', [\App\Http\Controllers\Admin\EmailSettingController::class, 'activate'])->name('activate');
        Route::post('/{emailSetting}/test', [\App\Http\Controllers\Admin\EmailSettingController::class, 'test'])->name('test');
        Route::get('/provider/{provider}', [\App\Http\Controllers\Admin\EmailSettingController::class, 'getProviderPreset'])->name('provider.preset');
    });

    // ========== Site Settings Routes ==========
    Route::prefix('settings/site')->name('settings.site.')->group(function () {
        Route::get('/', [SiteSettingController::class, 'index'])->name('index');
        Route::put('/', [SiteSettingController::class, 'update'])->name('update');
    });

    // ========== Backup Settings Routes ==========
    Route::prefix('settings/backup')->name('settings.backup.')->group(function () {
        Route::get('/', [BackupSettingsController::class, 'index'])->name('index');
        Route::put('/', [BackupSettingsController::class, 'update'])->name('update');
        Route::post('/test-webhook', [BackupSettingsController::class, 'testWebhook'])->name('test-webhook');
    });

    // ========== Storage Settings Routes ==========
    Route::prefix('settings/storage')->name('settings.storage.')->group(function () {
        Route::get('/', [StorageSettingsController::class, 'index'])->name('index');
        Route::put('/', [StorageSettingsController::class, 'update'])->name('update');
    });

    // ========== Storage Migration Routes ==========
    Route::prefix('storage-migration')->name('storage-migration.')->group(function () {
        Route::get('/', [StorageMigrationController::class, 'index'])->name('index');
        Route::get('analyze/{disk?}', [StorageMigrationController::class, 'analyze'])->name('analyze');
        Route::post('migrate', [StorageMigrationController::class, 'startMigration'])->name('migrate');
        Route::post('migrate-all', [StorageMigrationController::class, 'startAllMigration'])->name('migrate-all');
        Route::get('batch/{batchId}', [StorageMigrationController::class, 'batchStatus'])->name('batch-status');
        Route::post('batch/{batchId}/cancel', [StorageMigrationController::class, 'cancelBatch'])->name('batch-cancel');
        Route::get('verify/{diskName}', [StorageMigrationController::class, 'verify'])->name('verify');
        Route::post('cleanup/{diskName}', [StorageMigrationController::class, 'cleanup'])->name('cleanup');
        Route::get('batches', [StorageMigrationController::class, 'batches'])->name('batches');
    });

    // ========== Media Monitoring ==========
    Route::prefix('media-monitoring')->name('media-monitoring.')->group(function () {
        Route::get('/', [MediaMonitoringController::class, 'index'])->name('index');
        Route::post('retry-conversion/{conversion}', [MediaMonitoringController::class, 'retryConversion'])->name('retry-conversion');
        Route::post('retry-dead-letter/{deadLetter}', [MediaMonitoringController::class, 'retryDeadLetter'])->name('retry-dead-letter');
        Route::post('cleanup-orphans', [MediaMonitoringController::class, 'cleanupOrphans'])->name('cleanup-orphans');
        Route::post('cleanup-soft-deleted', [MediaMonitoringController::class, 'cleanupSoftDeleted'])->name('cleanup-soft-deleted');
    });

    // ========== Media Management ==========
    Route::prefix('media')->name('media.')->group(function () {
        Route::get('/', [MediaController::class, 'index'])->name('index');
        Route::get('/dead-letters', [MediaController::class, 'deadLetters'])->name('dead-letters');
        Route::post('/dead-letters/{deadLetter}/retry', [MediaController::class, 'retryDeadLetter'])->name('dead-letters.retry');
        Route::delete('/dead-letters/{deadLetter}', [MediaController::class, 'deleteDeadLetter'])->name('dead-letters.delete');
        Route::post('/dead-letters/resolve-all', [MediaController::class, 'resolveAllDeadLetters'])->name('dead-letters.resolve-all');
        Route::get('/conversions', [MediaController::class, 'conversions'])->name('conversions');
        Route::post('/conversions/{conversion}/retry', [MediaController::class, 'retryConversion'])->name('retry-conversion');
        Route::delete('/conversions/{conversion}', [MediaController::class, 'deleteConversion'])->name('delete-conversion');
        Route::get('/orphans', [MediaController::class, 'orphans'])->name('orphans');
        Route::post('/orphans/delete', [MediaController::class, 'deleteOrphans'])->name('delete-orphans');
        Route::get('/{medium}', [MediaController::class, 'show'])->name('show');
        Route::delete('/{medium}', [MediaController::class, 'destroy'])->name('destroy');
        Route::delete('/{medium}/soft', [MediaController::class, 'softDelete'])->name('soft-delete');
        Route::post('/{medium}/restore', [MediaController::class, 'restore'])->name('restore');
        Route::post('/{medium}/sync', [MediaController::class, 'syncNow'])->name('sync');
    });

    // ========== App Storage Routes ==========
    Route::prefix('storage')->name('storage.')->group(function () {
        Route::get('/', [AppStorageController::class, 'index'])->name('index');
        Route::get('/create', [AppStorageController::class, 'create'])->name('create');
        Route::post('/', [AppStorageController::class, 'store'])->name('store');
        Route::get('/{config}/edit', [AppStorageController::class, 'edit'])->name('edit');
        Route::put('/{config}', [AppStorageController::class, 'update'])->name('update');
        Route::delete('/{config}', [AppStorageController::class, 'destroy'])->name('destroy');
        Route::post('/{config}/test', [AppStorageController::class, 'test'])->name('test');
        Route::post('/test-connection', [AppStorageController::class, 'testConnection'])->name('test-connection');
        Route::get('/analytics', [AppStorageAnalyticsController::class, 'index'])->name('analytics');
    });

    // ========== Backup Routes ==========
    Route::prefix('backups')->name('backups.')->group(function () {
        Route::get('/', [BackupController::class, 'index'])->name('index');
        Route::get('/create', [BackupController::class, 'create'])->name('create');
        Route::post('/', [BackupController::class, 'store'])->name('store');
        Route::get('/{backup}', [BackupController::class, 'show'])->name('show');
        Route::delete('/{backup}', [BackupController::class, 'destroy'])->name('destroy');
        Route::get('/{backup}/download', [BackupController::class, 'download'])->name('download');
        Route::post('/{backup}/restore', [BackupController::class, 'restore'])->name('restore');
        Route::get('/{backup}/status', [BackupController::class, 'status'])->name('status');
        Route::get('/{backup}/restore-status', [BackupController::class, 'restoreStatus'])->name('restore-status');
        Route::post('/{backup}/run', [BackupController::class, 'run'])->name('run');
    });

    // ========== Backup Schedule Routes ==========
    Route::prefix('backup-schedules')->name('backup-schedules.')->group(function () {
        Route::get('/', [BackupScheduleController::class, 'index'])->name('index');
        Route::get('/create', [BackupScheduleController::class, 'create'])->name('create');
        Route::post('/', [BackupScheduleController::class, 'store'])->name('store');
        Route::get('/{schedule}/edit', [BackupScheduleController::class, 'edit'])->name('edit');
        Route::put('/{schedule}', [BackupScheduleController::class, 'update'])->name('update');
        Route::delete('/{schedule}', [BackupScheduleController::class, 'destroy'])->name('destroy');
        Route::post('/{schedule}/execute', [BackupScheduleController::class, 'execute'])->name('execute');
        Route::post('/{schedule}/toggle-active', [BackupScheduleController::class, 'toggleActive'])->name('toggle-active');
    });

    // Legacy backup-storage URLs → unified app storage
    Route::redirect('backup-storage', '/admin/storage')->name('backup-storage.index');
    Route::redirect('backup-storage/create', '/admin/storage/create')->name('backup-storage.create');
    Route::redirect('backup-storage/analytics', '/admin/storage/analytics')->name('backup-storage.analytics');

    // ========== Storage Disk Mappings Routes ==========
    Route::prefix('storage-disk-mappings')->name('storage-disk-mappings.')->group(function () {
        Route::get('/', [StorageDiskMappingController::class, 'index'])->name('index');
        Route::get('/create', [StorageDiskMappingController::class, 'create'])->name('create');
        Route::post('/', [StorageDiskMappingController::class, 'store'])->name('store');
        Route::get('/{mapping}/edit', [StorageDiskMappingController::class, 'edit'])->name('edit');
        Route::put('/{mapping}', [StorageDiskMappingController::class, 'update'])->name('update');
        Route::delete('/{mapping}', [StorageDiskMappingController::class, 'destroy'])->name('destroy');
    });



    Route::prefix('ai')->name('ai.')->group(function () {
        // Content
        Route::post('content/summarize', [\App\Http\Controllers\Admin\AIContentController::class, 'summarize'])->name('content.summarize');
        Route::post('content/improve', [\App\Http\Controllers\Admin\AIContentController::class, 'improve'])->name('content.improve');
        Route::post('content/grammar-check', [\App\Http\Controllers\Admin\AIContentController::class, 'grammarCheck'])->name('content.grammar-check');
        
        // Settings & models (config/ai-panel.php)
        Route::get('settings', [\App\Http\Controllers\Admin\AISettingsController::class, 'index'])->name('settings.index');
        Route::put('settings', [\App\Http\Controllers\Admin\AISettingsController::class, 'update'])->name('settings.update');
        Route::post('models/test-connection', [\App\Http\Controllers\Admin\AiModelConfigController::class, 'testConnection'])
            ->name('models.test-connection');
        Route::post('models/import-catalog', [\App\Http\Controllers\Admin\AiModelConfigController::class, 'importCatalog'])
            ->name('models.import-catalog');
        Route::resource('models', \App\Http\Controllers\Admin\AiModelConfigController::class)
            ->except(['show']);
    });
    
    /**
     * Blog AI Posts Routes
     * These should be placed in the blog route group
     */
    Route::prefix('blog')->name('blog.')->group(function () {
        Route::get('ai-posts/create', [\App\Http\Controllers\Admin\AIBlogPostController::class, 'create'])->name('ai-posts.create');
        Route::post('ai-posts', [\App\Http\Controllers\Admin\AIBlogPostController::class, 'store'])->name('ai-posts.store');
        Route::post('ai-posts/generate', [\App\Http\Controllers\Admin\AIBlogPostController::class, 'generate'])->name('ai-posts.generate');
    });

    // WhatsApp Settings Routes
    Route::prefix('whatsapp-settings')
        ->middleware(['role:admin'])
        ->name('whatsapp-settings.')
        ->group(function () {
            Route::get('/', [WhatsAppSettingsController::class, 'index'])->name('index');
            Route::post('/', [WhatsAppSettingsController::class, 'update'])->name('update');
            Route::post('/test-connection', [WhatsAppSettingsController::class, 'testConnection'])->name('test-connection');
        });

    // WhatsApp Messages Routes
    Route::prefix('whatsapp-messages')
        ->middleware(['role:admin'])
        ->name('whatsapp-messages.')
        ->group(function () {
            Route::get('/', [WhatsAppMessageController::class, 'index'])->name('index');
            Route::get('/send', [WhatsAppMessageController::class, 'create'])->name('create');
            Route::get('/search-students', [WhatsAppMessageController::class, 'searchStudents'])->name('search-students');
            Route::post('/send', [WhatsAppMessageController::class, 'send'])->name('send');
            Route::post('/broadcast', [WhatsAppMessageController::class, 'broadcast'])->name('broadcast');
            Route::get('/broadcast/students-count', [WhatsAppMessageController::class, 'getStudentsCount'])->name('broadcast.students-count');
            Route::post('/{message}/retry', [WhatsAppMessageController::class, 'retry'])->name('retry');
            Route::get('/{message}', [WhatsAppMessageController::class, 'show'])->name('show');
        });

    // WhatsApp Web Routes
    Route::prefix('whatsapp-web')
        ->middleware(['role:admin'])
        ->name('whatsapp-web.')
        ->group(function () {
            Route::get('/connect', [WhatsAppWebController::class, 'connect'])->name('connect');
            Route::post('/start-connection', [WhatsAppWebController::class, 'startConnection'])->name('start-connection');
            Route::get('/qr/{sessionId}', [WhatsAppWebController::class, 'getQrCode'])->name('qr');
            Route::get('/status/{sessionId}', [WhatsAppWebController::class, 'getStatus'])->name('status');
            Route::post('/disconnect/{sessionId}', [WhatsAppWebController::class, 'disconnect'])->name('disconnect');
        });

    // WhatsApp Web Settings Routes
    Route::prefix('whatsapp-web-settings')
        ->middleware(['role:admin'])
        ->name('whatsapp-web-settings.')
        ->group(function () {
            Route::get('/', [WhatsAppWebSettingsController::class, 'index'])->name('index');
            Route::post('/', [WhatsAppWebSettingsController::class, 'update'])->name('update');
            Route::post('/test-connection', [WhatsAppWebSettingsController::class, 'testConnection'])->name('test-connection');
        });

});
