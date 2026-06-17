<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('talent_hiring_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('talent_id')->constrained('talents')->cascadeOnDelete();
            $table->foreignId('company_id')->nullable()->constrained('companies')->cascadeOnDelete();
            $table->string('headline');
            $table->text('cover_message')->nullable();
            $table->enum('employment_type', ['full_time', 'part_time', 'freelance', 'contract'])->default('full_time');
            $table->boolean('is_remote')->default(true);
            $table->unsignedInteger('rate_min')->nullable();
            $table->unsignedInteger('rate_max')->nullable();
            $table->enum('status', ['draft', 'active', 'paused', 'closed', 'hired'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'company_id']);
            $table->index(['talent_id', 'status']);
            $table->index('published_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('talent_hiring_requests');
    }
};
