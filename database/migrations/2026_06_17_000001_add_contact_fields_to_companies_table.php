<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->json('contact_emails')->nullable()->after('website');
            $table->json('contact_websites')->nullable()->after('contact_emails');
            $table->json('social_links')->nullable()->after('contact_websites');
        });

        DB::table('companies')
            ->whereNotNull('website')
            ->where('website', '!=', '')
            ->orderBy('id')
            ->each(function (object $company) {
                DB::table('companies')->where('id', $company->id)->update([
                    'contact_websites' => json_encode([
                        ['label' => 'الموقع الرئيسي', 'url' => $company->website],
                    ], JSON_UNESCAPED_UNICODE),
                ]);
            });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['contact_emails', 'contact_websites', 'social_links']);
        });
    }
};
