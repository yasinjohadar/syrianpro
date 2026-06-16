<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            AdminUserSeeder::class,
            TechSpecialtySeeder::class,
            JobSeeder::class,
            TalentSeeder::class,
            CompanySeeder::class,
            BlogCategorySeeder::class,
            BlogTagSeeder::class,
            BlogPostSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'user',
            'email' => 'user@example.com',
        ]);
    }
}
