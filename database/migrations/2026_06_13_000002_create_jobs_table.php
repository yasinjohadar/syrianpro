<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_listings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('company_name');
            $table->string('logo', 100)->nullable();
            $table->string('logo_image')->nullable();
            $table->string('location');
            $table->string('employment_type')->default('دوام كامل');
            $table->unsignedInteger('salary_min')->nullable();
            $table->unsignedInteger('salary_max')->nullable();
            $table->string('currency', 10)->default('$');
            $table->string('remote_type')->default('full-remote');
            $table->string('timezone')->nullable();
            $table->boolean('is_syria_friendly')->default(false);
            $table->json('payment_methods')->nullable();
            $table->json('skills')->nullable();
            $table->json('tags')->nullable();
            $table->json('tag_labels')->nullable();
            $table->text('description')->nullable();
            $table->json('responsibilities')->nullable();
            $table->json('requirements')->nullable();
            $table->json('benefits')->nullable();
            $table->foreignId('tech_specialty_id')->nullable()->constrained('tech_specialties')->nullOnDelete();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_new')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('order')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_listings');
    }
};
