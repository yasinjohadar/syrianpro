<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('talent_hiring_request_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hiring_request_id')->constrained('talent_hiring_requests')->cascadeOnDelete();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['interested', 'contacted', 'declined'])->default('interested');
            $table->text('message')->nullable();
            $table->timestamps();

            $table->unique(['hiring_request_id', 'company_id'], 'thr_responses_request_company_unique');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('talent_hiring_request_responses');
    }
};
