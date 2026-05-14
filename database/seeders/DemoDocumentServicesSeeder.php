<?php

namespace Database\Seeders;

use App\Models\Municipality;
use App\Models\Office;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Status;
use Illuminate\Database\Seeder;

class DemoDocumentServicesSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Pending Payment', 'Pending', 'Paid', 'Completed'] as $status) {
            Status::firstOrCreate(['name' => $status]);
        }

        $municipality = Municipality::firstOrCreate(
            ['name' => 'Beirut Municipality'],
            [
                'region' => 'Beirut',
                'address' => 'Beirut, Lebanon',
                'google_maps_location' => '33.8938,35.5018',
                'latitude' => 33.8938,
                'longitude' => 35.5018,
                'working_hours' => 'Monday-Friday, 8:00 AM - 3:00 PM',
                'contact_info' => '+961 1 000 000',
            ]
        );

        $office = Office::firstOrCreate(
            ['name' => 'Civil Registry Office'],
            [
                'address' => 'Beirut, Lebanon',
                'google_maps_location' => $municipality->google_maps_location,
                'latitude' => $municipality->latitude,
                'longitude' => $municipality->longitude,
                'working_hours' => 'Monday-Friday, 8:00 AM - 3:00 PM',
                'contact_info' => '+961 1 000 100',
                'municipality_id' => $municipality->id,
            ]
        );

        $category = ServiceCategory::firstOrCreate(['name' => 'Civil Documents']);

        foreach ([
            ['name' => 'Birth Certificate', 'description' => 'Official birth certificate request.', 'price' => 12.00, 'duration' => 20],
            ['name' => 'Residence Certificate', 'description' => 'Municipal residence certificate request.', 'price' => 18.50, 'duration' => 30],
            ['name' => 'Family Record Extract', 'description' => 'Official family record extract request.', 'price' => 24.00, 'duration' => 45],
        ] as $service) {
            Service::firstOrCreate(
                ['name' => $service['name'], 'office_id' => $office->id],
                [
                    ...$service,
                    'office_id' => $office->id,
                    'service_category_id' => $category->id,
                ]
            );
        }
    }
}
