<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProcessTaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => ucfirst($this->faker->word),
            'description' => $this->faker->sentence,
            'time' => $this->faker->numberBetween(10, 600),
            'role_id' => 3,
            'position' => 1,
            'order_stage_id' => 2
        ];
    }
}
