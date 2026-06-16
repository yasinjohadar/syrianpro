<?php

namespace Database\Seeders;

use App\Models\TechSpecialty;
use Illuminate\Database\Seeder;

class TechSpecialtySeeder extends Seeder
{
    public function run(): void
    {
        $specialties = [
            ['name' => 'Frontend', 'icon' => '⚛️', 'jobs_count' => 45, 'order' => 1],
            ['name' => 'Backend', 'icon' => '⚙️', 'jobs_count' => 38, 'order' => 2],
            ['name' => 'Mobile', 'icon' => '📱', 'jobs_count' => 22, 'order' => 3],
            ['name' => 'DevOps', 'icon' => '☁️', 'jobs_count' => 18, 'order' => 4],
            ['name' => 'UI/UX', 'icon' => '🎨', 'jobs_count' => 25, 'order' => 5],
            ['name' => 'Data', 'icon' => '📊', 'jobs_count' => 15, 'order' => 6],
            ['name' => 'QA', 'icon' => '🔍', 'jobs_count' => 12, 'order' => 7],
            ['name' => 'Product', 'icon' => '📋', 'jobs_count' => 8, 'order' => 8],
        ];

        foreach ($specialties as $item) {
            TechSpecialty::firstOrCreate(
                ['name' => $item['name']],
                [
                    'slug' => TechSpecialty::generateUniqueSlug($item['name']),
                    'icon' => $item['icon'],
                    'jobs_count' => $item['jobs_count'],
                    'order' => $item['order'],
                    'is_active' => true,
                    'show_on_home' => true,
                ]
            );
        }
    }
}
