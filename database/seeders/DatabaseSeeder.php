<?php

namespace Database\Seeders;

use App\Models\Municipality;
use App\Models\Office;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\Support\OfficeStaffAccount;
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

        $latitude = 33.83433726548953;
        $longitude = 35.54456520686778;

        $municipality = Municipality::updateOrCreate(
            ['name' => 'Baabda', 'region' => 'Lebanon'],
            [
                'address' => 'Baabda Municipality, Mount Lebanon Governorate, Lebanon',
                'google_maps_location' => $latitude.','.$longitude,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'working_hours' => '8:00 AM - 5:00 PM',
                'contact_info' => '+961 5 920 000',
            ]
        );

        $office = Office::updateOrCreate(
            ['name' => 'Baabda Municipality Office', 'municipality_id' => $municipality->id],
            [
                'address' => 'Baabda Municipality, Mount Lebanon Governorate, Lebanon',
                'google_maps_location' => $municipality->google_maps_location,
                'latitude' => $municipality->latitude,
                'longitude' => $municipality->longitude,
                'working_hours' => '8:00 AM - 5:00 PM',
                'contact_info' => '+961 5 920 001',
            ]
        );

        $administratorRole = Role::where('name', 'Administrator')->firstOrFail();
        $officeStaffRole = Role::where('name', 'OfficeStaff')->firstOrFail();
        $citizenRole = Role::where('name', 'Citizen')->firstOrFail();

        User::updateOrCreate(
            ['email' => 'admin@govease.com'],
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

        $baabdaStaffEmail = OfficeStaffAccount::email('Baabda', $office->name);
        $baabdaStaffNames = OfficeStaffAccount::names('Baabda', $office->name);

        User::updateOrCreate(
            ['email' => $baabdaStaffEmail],
            User::factory()->officeStaff($officeStaffRole->id, $office->id)->make([
                'firstName' => $baabdaStaffNames['firstName'],
                'lastName' => $baabdaStaffNames['lastName'],
                'email' => $baabdaStaffEmail,
            ])->getAttributes()
        );

        User::updateOrCreate(
            ['email' => 'citizen@govease.com'],
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

        $this->call(LebanonDummyDataSeeder::class);
    }
}
