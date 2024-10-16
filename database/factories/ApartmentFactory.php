<?php

namespace Database\Factories;

use App\Enums\ApartmentView;
use App\Models\Apartment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Apartment>
 */
class ApartmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'number' => $this->faker->numberBetween(1, 10),
            'level_id' => LevelFactory::new(),
            'customer_id' => CustomerFactory::new(),
            'tower_id' => TowerFactory::new(),
            'view' => $this->faker->randomElement(ApartmentView::toArray()),
            'is_rent' => $this->faker->boolean(),
            'rent_customer_id' => $this->faker->boolean() ? CustomerFactory::new() : null,
            'area' => $this->faker->numberBetween(100, 200),
        ];
    }
}
