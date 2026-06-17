<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Talent;
use App\Models\TalentHiringRequest;
use Illuminate\Database\Seeder;

class TalentHiringRequestSeeder extends Seeder
{
    public function run(): void
    {
        $talent = Talent::query()->where('name', 'أحمد الخطيب')->first();
        $company = Company::query()->where('name', 'SyriaDev Studio')->first();

        if (! $talent || ! $talent->user_id) {
            return;
        }

        TalentHiringRequest::query()->updateOrCreate(
            [
                'talent_id' => $talent->id,
                'company_id' => null,
            ],
            [
                'user_id' => $talent->user_id,
                'headline' => 'مطور Full Stack — React & Node.js',
                'cover_message' => 'أبحث عن فرصة remote مع شركة تقنية تقدّر الجودة والنمو. خبرة 5 سنوات في بناء منتجات SaaS.',
                'employment_type' => TalentHiringRequest::TYPE_FULL_TIME,
                'is_remote' => true,
                'rate_min' => 20,
                'rate_max' => 30,
                'status' => TalentHiringRequest::STATUS_ACTIVE,
                'published_at' => now(),
            ]
        );

        $talent->update(['is_open_to_work' => true]);

        if ($company) {
            TalentHiringRequest::query()->updateOrCreate(
                [
                    'talent_id' => $talent->id,
                    'company_id' => $company->id,
                ],
                [
                    'user_id' => $talent->user_id,
                    'headline' => 'أرغب بالانضمام لفريق SyriaDev Studio',
                    'cover_message' => 'أتابع أعمالكم منذ فترة وأعتقد أن خبرتي في React و Node.js تتوافق مع احتياجات الفريق.',
                    'employment_type' => TalentHiringRequest::TYPE_FULL_TIME,
                    'is_remote' => true,
                    'rate_min' => 22,
                    'rate_max' => 28,
                    'status' => TalentHiringRequest::STATUS_ACTIVE,
                    'published_at' => now()->subDays(2),
                ]
            );
        }
    }
}
