<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('talent_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('talent_id')->constrained('talents')->cascadeOnDelete();
            $table->foreignId('recommended_by')->constrained('users')->cascadeOnDelete();
            $table->string('reason', 500);
            $table->enum('scope', ['homepage', 'talents_page', 'specialty', 'job']);
            $table->unsignedBigInteger('scope_id')->nullable();
            $table->unsignedTinyInteger('priority')->default(0);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['scope', 'scope_id', 'is_active']);
            $table->index(['talent_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('talent_recommendations');
    }
};
