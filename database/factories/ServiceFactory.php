<?php

namespace Database\Factories;

use App\Models\Apartment;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $randomStartDate = Carbon::make($this->faker->dateTimeBetween('-1 year', 'now'))?->startOfMonth();
        return [
            'apartment_id' => Apartment::query()->inRandomOrder()->first()?->id,
            'amount' => random_int(5_000, 100_000),
            'paid_at' => $this->faker->numberBetween(0,1) === 0 ? null : $this->faker->dateTimeBetween('-1 year', 'now'),
            'notes' => $this->faker->text,
            'start_date' => $randomStartDate->toDate(),
            'end_date' => $randomStartDate->addMonth()->toDate(),
            'created_by' => User::query()->inRandomOrder()->first()?->id,
        ];
    }
}
