<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('backup_storage_configs', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('اسم الإعداد');
            $table->string('driver')->comment('نوع السائق (local, s3, google_drive, etc.)');
            $table->text('config')->comment('إعدادات السائق (encrypted)');
            $table->boolean('is_active')->default(true);
            $table->boolean('redundancy')->default(false)->comment('تفعيل التخزين المتعدد');
            $table->integer('priority')->default(0)->comment('الأولوية');
            $table->integer('max_backups')->nullable()->comment('الحد الأقصى للنسخ');
            $table->json('pricing_config')->nullable()->comment('إعدادات التسعير');
            $table->decimal('monthly_budget', 10, 2)->nullable()->comment('الميزانية الشهرية');
            $table->decimal('cost_alert_threshold', 10, 2)->nullable()->comment('حد تنبيه التكلفة');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            // Indexes
            $table->index('driver');
            $table->index('is_active');
            $table->index('priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backup_storage_configs');
    }
};
