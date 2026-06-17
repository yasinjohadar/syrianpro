<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('talent_id')->constrained('talents')->cascadeOnDelete();
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->foreignId('job_listing_id')->nullable()->constrained('job_listings')->nullOnDelete();
            $table->enum('source', ['application', 'public_request', 'pitch']);
            $table->unsignedBigInteger('source_id');
            $table->timestamp('hired_at');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['source', 'source_id']);
            $table->index('talent_id');
            $table->index('company_id');
            $table->index('hired_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hires');
    }
};
