<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Talent;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        $password = config('demo-accounts.password', '123456789');

        $companyRole = Role::firstOrCreate(['name' => 'company']);
        $talentRole = Role::firstOrCreate(['name' => 'talent']);

        $companyUser = User::updateOrCreate(
            ['email' => 'company@demo.com'],
            [
                'name' => 'شركة تجريبية',
                'password' => Hash::make($password),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );
        $companyUser->syncRoles([$companyRole]);

        $company = Company::query()->where('name', 'SyriaDev Studio')->first();
        if ($company) {
            $company->update(['user_id' => $companyUser->id]);
        }

        $talentUser = User::updateOrCreate(
            ['email' => 'talent@demo.com'],
            [
                'name' => 'أحمد الخطيب',
                'password' => Hash::make($password),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );
        $talentUser->syncRoles([$talentRole]);

        $talent = Talent::query()->where('name', 'أحمد الخطيب')->first();
        if ($talent) {
            $talent->update(['user_id' => $talentUser->id]);
        }

        $generalUser = User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'مستخدم تجريبي',
                'password' => Hash::make(config('demo-accounts.accounts.user.password', 'password')),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );
        $generalUser->syncRoles([$talentRole]);
    }
}
