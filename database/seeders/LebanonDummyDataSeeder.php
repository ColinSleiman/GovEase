<?php

namespace Database\Seeders;

use App\Models\Municipality;
use App\Models\Office;
use App\Models\Role;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use Database\Seeders\Support\OfficeStaffAccount;
use Illuminate\Database\Seeder;

class LebanonDummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $data = require database_path('data/lebanon.php');

        $citizenRole = Role::where('name', 'Citizen')->firstOrFail();
        $officeStaffRole = Role::where('name', 'OfficeStaff')->firstOrFail();

        foreach ($data['municipalities'] as $municipalityData) {
            $municipality = Municipality::updateOrCreate(
                ['name' => $municipalityData['name'], 'region' => $municipalityData['region']],
                [
                    'address' => $municipalityData['address'],
                    'google_maps_location' => $municipalityData['latitude'].','.$municipalityData['longitude'],
                    'latitude' => $municipalityData['latitude'],
                    'longitude' => $municipalityData['longitude'],
                    'working_hours' => $municipalityData['working_hours'],
                    'contact_info' => $municipalityData['contact_info'],
                ]
            );

            $officeCount = count($municipalityData['offices']);

            foreach ($municipalityData['offices'] as $officeData) {
                $office = Office::updateOrCreate(
                    ['name' => $officeData['name'], 'municipality_id' => $municipality->id],
                    [
                        'address' => $officeData['address'],
                        'google_maps_location' => $officeData['latitude'].','.$officeData['longitude'],
                        'latitude' => $officeData['latitude'],
                        'longitude' => $officeData['longitude'],
                        'working_hours' => $officeData['working_hours'],
                        'contact_info' => $officeData['contact_info'],
                    ]
                );

                $this->seedServiceCatalog($office, $data['service_catalog']);
                $this->seedOfficeStaffForOffice(
                    $office,
                    $municipalityData['name'],
                    $officeData['name'],
                    $officeCount,
                    $officeStaffRole,
                );
            }
        }

        $this->seedBaabdaCatalog($data['service_catalog']);
        $this->seedCitizens($data['citizens'], $citizenRole);
    }

    private function seedBaabdaCatalog(array $serviceCatalog): void
    {
        $baabdaOffice = Office::where('name', 'Baabda Municipality Office')->first();

        if ($baabdaOffice) {
            $this->seedServiceCatalog($baabdaOffice, $serviceCatalog);
        }
    }

    private function seedServiceCatalog(Office $office, array $serviceCatalog): void
    {
        foreach ($serviceCatalog as $categoryData) {
            $category = ServiceCategory::updateOrCreate(
                ['name' => $categoryData['category'], 'office_id' => $office->id],
                ['office_id' => $office->id]
            );

            foreach ($categoryData['services'] as $serviceData) {
                Service::updateOrCreate(
                    [
                        'name' => $serviceData['name'],
                        'office_id' => $office->id,
                        'service_category_id' => $category->id,
                    ],
                    [
                        'description' => $serviceData['description'],
                        'price' => $serviceData['price'],
                        'duration' => $serviceData['duration'],
                    ]
                );
            }
        }
    }

    private function seedCitizens(array $citizens, Role $citizenRole): void
    {
        foreach ($citizens as $citizen) {
            User::updateOrCreate(
                ['email' => $citizen['email']],
                User::factory()->citizen($citizenRole->id)->make([
                    'firstName' => $citizen['firstName'],
                    'lastName' => $citizen['lastName'],
                    'email' => $citizen['email'],
                ])->getAttributes()
            );
        }
    }

    private function seedOfficeStaffForOffice(
        Office $office,
        string $municipalityName,
        string $officeName,
        int $officeCount,
        Role $officeStaffRole,
    ): void {
        $email = OfficeStaffAccount::email($municipalityName, $officeName, $officeCount);
        $names = OfficeStaffAccount::names($municipalityName, $officeName, $officeCount);

        User::updateOrCreate(
            ['email' => $email],
            User::factory()->officeStaff($officeStaffRole->id, $office->id)->make([
                'firstName' => $names['firstName'],
                'lastName' => $names['lastName'],
                'email' => $email,
            ])->getAttributes()
        );
    }
}
