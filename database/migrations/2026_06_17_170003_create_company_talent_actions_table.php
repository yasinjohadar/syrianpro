<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_talent_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('talent_id')->constrained('talents')->cascadeOnDelete();
            $table->foreignId('job_listing_id')->nullable()->constrained('job_listings')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('type', ['invite', 'shortlist', 'note']);
            $table->text('message')->nullable();
            $table->unsignedTinyInteger('fit_rating')->nullable();
            $table->enum('status', ['pending', 'viewed', 'applied', 'declined', 'expired', 'active', 'removed'])->default('pending');
            $table->timestamp('viewed_at')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'type', 'status']);
            $table->index(['talent_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_talent_actions');
    }
};
