<?php

namespace Database\Seeders;

use App\Models\Municipality;
use App\Models\Office;
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
            StatusSeeder::class,
        ]);

        $municipality = Municipality::updateOrCreate(
            ['name' => 'Default Municipality', 'region' => 'NCR'],
            [
                'address' => 'City Hall, Main Road',
                'google_maps_location' => '14.5995000,120.9842000',
                'latitude' => 14.5995000,
                'longitude' => 120.9842000,
                'working_hours' => '8:00 AM - 5:00 PM',
                'contact_info' => '+63 2 0000 0000',
            ]
        );

        $office = Office::updateOrCreate(
            ['name' => 'Default Government Office', 'municipality_id' => $municipality->id],
            [
                'address' => '2nd Floor, City Hall',
                'google_maps_location' => $municipality->google_maps_location,
                'latitude' => $municipality->latitude,
                'longitude' => $municipality->longitude,
                'working_hours' => '8:00 AM - 5:00 PM',
                'contact_info' => '+63 2 1111 1111',
            ]
        );

        $administratorRole = Role::where('name', 'Administrator')->firstOrFail();
        $officeStaffRole = Role::where('name', 'OfficeStaff')->firstOrFail();
        $citizenRole = Role::where('name', 'Citizen')->firstOrFail();

        User::updateOrCreate(
            ['email' => 'admin@goverse.local'],
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

        User::updateOrCreate(
            ['email' => 'officestaff@goverse.local'],
            [
                'firstName' => 'Office',
                'lastName' => 'Staff',
                'office_id' => $office->id,
                'role_id' => $officeStaffRole->id,
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

        User::updateOrCreate(
            ['email' => 'citizen@goverse.local'],
            [
                'firstName' => 'Default',
                'lastName' => 'Citizen',
                'office_id' => null,
                'role_id' => $citizenRole->id,
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
