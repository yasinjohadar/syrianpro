<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('backups', function (Blueprint $table) {
            $table->json('scope')->nullable()->after('backup_type');
            $table->string('restore_status', 32)->nullable()->after('status');
            $table->text('restore_error_message')->nullable();
            $table->timestamp('restore_started_at')->nullable();
            $table->timestamp('restore_completed_at')->nullable();
        });

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE backups MODIFY status VARCHAR(32) NOT NULL DEFAULT 'pending'");
        } else {
            Schema::table('backups', function (Blueprint $table) {
                $table->string('status', 32)->default('pending')->change();
            });
        }
    }

    public function down(): void
    {
        Schema::table('backups', function (Blueprint $table) {
            $table->dropColumn([
                'scope',
                'restore_status',
                'restore_error_message',
                'restore_started_at',
                'restore_completed_at',
            ]);
        });
    }
};
