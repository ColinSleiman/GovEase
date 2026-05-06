<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::query()
            ->where('name', 'Office Staff')
            ->update(['name' => 'OfficeStaff']);

        $roles = [
            ['name' => 'Citizen'],
            ['name' => 'OfficeStaff'],
            ['name' => 'Administrator'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate($role);
        }
    }
}
