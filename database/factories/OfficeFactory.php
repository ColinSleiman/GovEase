<?php

namespace Database\Factories;

use App\Models\Municipality;
use App\Models\Office;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Office>
 */
class OfficeFactory extends Factory
{
    protected $model = Office::class;

    public function definition(): array
    {
        $latitude = fake()->latitude(33.05, 34.70);
        $longitude = fake()->longitude(35.10, 36.65);

        return [
            'name' => fake()->company().' Municipal Office',
            'address' => fake()->streetAddress().', Lebanon',
            'google_maps_location' => $latitude.','.$longitude,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'working_hours' => '8:00 AM - 3:00 PM',
            'contact_info' => '+961 '.fake()->numberBetween(1, 9).' '.fake()->numerify('### ###'),
            'municipality_id' => Municipality::factory(),
        ];
    }
}
