<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);

        $administratorRole = Role::where('name', 'Administrator')->firstOrFail();

        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'firstName' => 'Admin',
                'lastName' => 'Admin',
                'office_id' => null,
                'role_id' => $administratorRole->id,
                'password' => Hash::make('password'),
                'is_active' => true,
                'verified' => true,
                'email_verified_at' => now(),
                'two_factor_authentication' => false,
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
