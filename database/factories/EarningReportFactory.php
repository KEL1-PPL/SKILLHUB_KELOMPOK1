<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EarningReportFactory extends Factory
{
    public function definition(): array
    {
        return [
            'period' => $this->faker->date(),
            'amount' => $this->faker->numberBetween(50000, 300000),
            'note' => $this->faker->sentence(),
        ];
    }
}
