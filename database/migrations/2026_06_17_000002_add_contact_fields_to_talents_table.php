<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('talents', function (Blueprint $table) {
            $table->json('contact_emails')->nullable()->after('links');
            $table->json('contact_websites')->nullable()->after('contact_emails');
            $table->json('social_links')->nullable()->after('contact_websites');
        });

        DB::table('talents')->orderBy('id')->each(function (object $talent) {
            $links = json_decode($talent->links ?? 'null', true);

            if (! is_array($links)) {
                return;
            }

            $socialLinks = [];
            $contactWebsites = [];

            foreach (['github', 'linkedin'] as $platform) {
                if (! empty($links[$platform])) {
                    $socialLinks[] = [
                        'platform' => $platform,
                        'url' => $links[$platform],
                    ];
                }
            }

            if (! empty($links['portfolio'])) {
                $contactWebsites[] = [
                    'label' => 'Portfolio',
                    'url' => $links['portfolio'],
                ];
            }

            if ($socialLinks === [] && $contactWebsites === []) {
                return;
            }

            DB::table('talents')->where('id', $talent->id)->update([
                'social_links' => json_encode($socialLinks, JSON_UNESCAPED_UNICODE),
                'contact_websites' => json_encode($contactWebsites, JSON_UNESCAPED_UNICODE),
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('talents', function (Blueprint $table) {
            $table->dropColumn(['contact_emails', 'contact_websites', 'social_links']);
        });
    }
};
