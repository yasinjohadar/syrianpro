<?php

namespace App\Services\Backup;

use App\Models\AppStorageAnalytic;
use App\Models\AppStorageConfig;
use Illuminate\Support\Facades\Log;

class StorageAnalyticsService
{
    public function trackStorageUsage(AppStorageConfig $config, int $bytes): void
    {
        try {
            $date = now()->toDateString();

            $analytic = AppStorageAnalytic::firstOrCreate(
                [
                    'storage_config_id' => $config->id,
                    'date' => $date,
                ],
                [
                    'bytes_stored' => 0,
                    'bytes_uploaded' => 0,
                    'bytes_downloaded' => 0,
                    'cost' => 0,
                    'operations_count' => 0,
                ]
            );

            $analytic->increment('bytes_stored', $bytes);
            $analytic->increment('cost', $this->calculateCost($config, $bytes));
        } catch (\Exception $e) {
            Log::error('Error tracking storage usage: ' . $e->getMessage());
        }
    }

    public function trackBandwidth(AppStorageConfig $config, string $operation, int $bytes): void
    {
        try {
            $date = now()->toDateString();

            $analytic = AppStorageAnalytic::firstOrCreate(
                [
                    'storage_config_id' => $config->id,
                    'date' => $date,
                ],
                [
                    'bytes_stored' => 0,
                    'bytes_uploaded' => 0,
                    'bytes_downloaded' => 0,
                    'cost' => 0,
                    'operations_count' => 0,
                ]
            );

            if ($operation === 'upload') {
                $analytic->increment('bytes_uploaded', $bytes);
                $analytic->increment('cost', $this->calculateUploadCost($config, $bytes));
            } elseif ($operation === 'download') {
                $analytic->increment('bytes_downloaded', $bytes);
                $analytic->increment('cost', $this->calculateDownloadCost($config, $bytes));
            }

            $analytic->increment('operations_count');
        } catch (\Exception $e) {
            Log::error('Error tracking bandwidth: ' . $e->getMessage());
        }
    }

    protected function calculateCost(AppStorageConfig $config, int $bytes): float
    {
        $gb = $bytes / (1024 * 1024 * 1024);

        return round($gb * (float) ($config->cost_per_gb ?? 0), 4);
    }

    protected function calculateUploadCost(AppStorageConfig $config, int $bytes): float
    {
        return $this->calculateCost($config, $bytes) * 0.1;
    }

    protected function calculateDownloadCost(AppStorageConfig $config, int $bytes): float
    {
        return $this->calculateCost($config, $bytes) * 0.15;
    }
}
