<?php

namespace Database\Factories;

use App\Models\Tower;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tower>
 */
class TowerFactory extends Factory
{

    protected $model = Tower::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Tower_'. $this->faker->unique()->numberBetween(1, 10000),
        ];
    }
}
