<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_listings', function (Blueprint $table) {
            $table->foreignId('company_id')
                ->nullable()
                ->after('company_name')
                ->constrained('companies')
                ->nullOnDelete();

            $table->index('company_id');
        });

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement('
                UPDATE job_listings jl
                INNER JOIN companies c ON c.name = jl.company_name
                SET jl.company_id = c.id
                WHERE jl.company_id IS NULL
            ');
        } else {
            $jobs = DB::table('job_listings')->whereNull('company_id')->get(['id', 'company_name']);

            foreach ($jobs as $job) {
                $companyId = DB::table('companies')
                    ->where('name', $job->company_name)
                    ->value('id');

                if ($companyId) {
                    DB::table('job_listings')
                        ->where('id', $job->id)
                        ->update(['company_id' => $companyId]);
                }
            }
        }
    }

    public function down(): void
    {
        Schema::table('job_listings', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropIndex(['company_id']);
            $table->dropColumn('company_id');
        });
    }
};
