<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sector');
            $table->string('category')->default('tech');
            $table->string('logo', 100)->nullable();
            $table->string('logo_image')->nullable();
            $table->unsignedInteger('jobs_count')->default(0);
            $table->decimal('rating', 2, 1)->default(0);
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_remote_friendly')->default(true);
            $table->boolean('is_syria_friendly')->default(true);
            $table->string('location');
            $table->string('founded')->nullable();
            $table->string('team_size')->nullable();
            $table->string('website')->nullable();
            $table->string('timezone')->nullable();
            $table->json('payment_methods')->nullable();
            $table->text('about')->nullable();
            $table->text('mission')->nullable();
            $table->json('values')->nullable();
            $table->json('perks')->nullable();
            $table->json('culture')->nullable();
            $table->json('tech_stack')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
