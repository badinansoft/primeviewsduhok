<?php

namespace Database\Factories;

use App\Models\Apartment;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class GasFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gazConsumption = $this->faker->randomFloat(2, 0, 1000);
        $unitPrice = $this->faker->randomFloat(2, 0, 1000);
        $apartment = $customer?->apartments[0] ?? Apartment::query()->inRandomOrder()->first();
        return [
            'apartment_id' => $apartment->id,
            'consumption' => $gazConsumption,
            'unit_price' => $unitPrice,
            'attachment' => $this->faker->text,
            'date' => $this->faker->date(),
            'paid_at' => $this->faker->numberBetween(0,1) === 0 ? null : $this->faker->dateTimeBetween('-1 year', 'now'),
            'notes' => $this->faker->text,
            'current_unit' => $apartment->gas_unit + $gazConsumption,
            'created_by' => 1,
        ];
    }
}
