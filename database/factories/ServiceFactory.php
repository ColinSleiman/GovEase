<?php

namespace Database\Factories;

use App\Models\Office;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'price' => fake()->randomElement([40000, 50000, 75000, 100000, 150000, 200000]),
            'duration' => fake()->randomElement([15, 20, 30, 45, 60, 90]),
            'office_id' => Office::factory(),
            'service_category_id' => ServiceCategory::factory(),
        ];
    }
}
