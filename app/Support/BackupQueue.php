<?php

namespace App\Support;

class BackupQueue
{
    public static function shouldDispatchAsync(): bool
    {
        if (! config('backup.queue', true)) {
            return false;
        }

        if (config('backup.sync_in_local', false) && app()->environment('local')) {
            return false;
        }

        return true;
    }
}
