<?php

namespace App\Console\Commands;

use App\Support\PermissionRegistry;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SyncPermissionsCommand extends Command
{
    protected $signature = 'permissions:sync {--admin : منح دور admin جميع الصلاحيات بعد المزامنة}';

    protected $description = 'مزامنة صلاحيات النظام من config/permissions.php إلى قاعدة البيانات';

    public function handle(): int
    {
        PermissionRegistry::syncToDatabase();

        $count = Permission::count();
        $this->info("تمت مزامنة {$count} صلاحية.");

        if ($this->option('admin')) {
            $adminRole = Role::where('name', 'admin')->first();
            if ($adminRole) {
                $adminRole->syncPermissions(Permission::all());
                $this->info('تم تحديث صلاحيات دور admin.');
            } else {
                $this->warn('دور admin غير موجود.');
            }
        }

        return self::SUCCESS;
    }
}
