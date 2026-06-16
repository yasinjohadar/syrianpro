<?php

namespace App\Jobs;

use App\Models\Backup;
use App\Services\Backup\BackupService;
use App\Services\BackupSettingsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RestoreBackupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    public $timeout;

    public function __construct(
        public Backup $backup,
        public array $options = []
    ) {
        app(BackupSettingsService::class)->applyToConfig();
        $this->timeout = (int) config('backup.job_timeout', 600);
    }

    public function handle(BackupService $backupService): void
    {
        app(BackupSettingsService::class)->applyToConfig();

        try {
            $backupService->restoreBackup($this->backup, $this->options);
        } catch (\Exception $e) {
            Log::error('Restore backup job failed: ' . $e->getMessage(), [
                'backup_id' => $this->backup->id,
            ]);

            throw $e;
        }
    }
}
