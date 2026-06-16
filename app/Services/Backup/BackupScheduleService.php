<?php

namespace App\Services\Backup;

use App\Jobs\CreateBackupJob;
use App\Models\AppStorageConfig;
use App\Models\Backup;
use App\Models\BackupSchedule;
use App\Support\BackupQueue;
use Carbon\Carbon;

class BackupScheduleService
{
    public function __construct(
        private BackupScopeResolver $scopeResolver,
    ) {}

    public function createSchedule(array $data): BackupSchedule
    {
        $schedule = BackupSchedule::create($data);
        $schedule->update(['next_run_at' => $schedule->calculateNextRun()]);

        return $schedule;
    }

    public function updateSchedule(BackupSchedule $schedule, array $data): BackupSchedule
    {
        $schedule->update($data);
        $schedule->update(['next_run_at' => $schedule->calculateNextRun()]);

        return $schedule->fresh();
    }

    public function deleteSchedule(BackupSchedule $schedule): bool
    {
        return $schedule->delete();
    }

    public function executeSchedule(BackupSchedule $schedule): Backup
    {
        $storageConfig = $this->resolveStorageConfig($schedule);
        $compression = ($schedule->compression_types ?? ['zip'])[0] ?? 'zip';
        $scope = $this->scopeResolver->normalize(
            $schedule->scope,
            $schedule->backup_type
        );

        $backup = Backup::create([
            'name' => $schedule->name . '_' . now()->format('Y-m-d_H-i-s'),
            'type' => 'scheduled',
            'backup_type' => $schedule->backup_type,
            'scope' => $scope,
            'storage_driver' => $storageConfig->driver,
            'storage_config_id' => $storageConfig->id,
            'compression_type' => $compression,
            'status' => 'pending',
            'retention_days' => $schedule->retention_days,
            'schedule_id' => $schedule->id,
            'created_by' => $schedule->created_by,
        ]);

        $backup->update(['expires_at' => $backup->calculateExpiresAt()]);

        $jobOptions = [
            'backup_id' => $backup->id,
            'name' => $backup->name,
            'type' => 'scheduled',
            'backup_type' => $schedule->backup_type,
            'scope' => $scope,
            'storage_config_id' => $storageConfig->id,
            'storage_driver' => $storageConfig->driver,
            'compression_type' => $compression,
            'retention_days' => $schedule->retention_days,
            'schedule_id' => $schedule->id,
            'created_by' => $schedule->created_by,
        ];

        if (BackupQueue::shouldDispatchAsync()) {
            CreateBackupJob::dispatch($backup, $jobOptions);
        } else {
            CreateBackupJob::dispatchSync($backup, $jobOptions);
        }

        $schedule->update([
            'last_run_at' => now(),
            'next_run_at' => $schedule->calculateNextRun(),
        ]);

        return $backup;
    }

    public function runScheduledBackups(): int
    {
        $schedules = BackupSchedule::where('is_active', true)
            ->where('next_run_at', '<=', now())
            ->get();

        $count = 0;
        foreach ($schedules as $schedule) {
            if ($schedule->shouldRun()) {
                try {
                    $this->executeSchedule($schedule);
                    $count++;
                } catch (\Exception $e) {
                    \Log::error('Error executing backup schedule: ' . $e->getMessage(), [
                        'schedule_id' => $schedule->id,
                    ]);
                }
            }
        }

        return $count;
    }

    public function calculateNextRun(BackupSchedule $schedule): Carbon
    {
        return $schedule->calculateNextRun();
    }

    public function shouldRun(BackupSchedule $schedule): bool
    {
        return $schedule->shouldRun();
    }

    protected function resolveStorageConfig(BackupSchedule $schedule): AppStorageConfig
    {
        if ($schedule->storage_config_id) {
            $config = AppStorageConfig::where('id', $schedule->storage_config_id)
                ->where('is_active', true)
                ->first();
            if ($config) {
                return $config;
            }
        }

        $driver = ($schedule->storage_drivers ?? ['local'])[0] ?? 'local';
        $config = AppStorageConfig::where('driver', $driver)
            ->where('is_active', true)
            ->orderByDesc('priority')
            ->first();

        if (! $config) {
            throw new \Exception("لا يوجد مكان تخزين نشط للسائق: {$driver}");
        }

        return $config;
    }
}
