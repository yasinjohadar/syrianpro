<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('backup_schedules', function (Blueprint $table) {
            $table->foreignId('storage_config_id')
                ->nullable()
                ->after('backup_type')
                ->constrained('app_storage_configs')
                ->nullOnDelete();
            $table->json('scope')->nullable()->after('storage_config_id');
        });
    }

    public function down(): void
    {
        Schema::table('backup_schedules', function (Blueprint $table) {
            $table->dropForeign(['storage_config_id']);
            $table->dropColumn(['storage_config_id', 'scope']);
        });
    }
};
