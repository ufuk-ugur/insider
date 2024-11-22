<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TeamFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'  => $this->faker->unique()->company(),
            'power' => $this->faker->numberBetween(50, 100),
        ];
    }
}
