<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('talents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('city');
            $table->string('avatar', 10)->nullable();
            $table->string('avatar_image')->nullable();
            $table->text('bio')->nullable();
            $table->json('skills')->nullable();
            $table->boolean('is_remote')->default(true);
            $table->string('availability')->nullable();
            $table->unsignedInteger('rate_min')->nullable();
            $table->unsignedInteger('rate_max')->nullable();
            $table->string('rate_currency', 10)->default('$');
            $table->json('experience')->nullable();
            $table->json('projects')->nullable();
            $table->json('links')->nullable();
            $table->foreignId('tech_specialty_id')->nullable()->constrained('tech_specialties')->nullOnDelete();
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('talents');
    }
};
