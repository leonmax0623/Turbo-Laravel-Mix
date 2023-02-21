<?php

namespace Database\Factories;

use App\Models\Finance;
use Illuminate\Database\Eloquent\Factories\Factory;

class FinanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => ucfirst($this->faker->unique()->word),
            'operation_type' => $this->faker->randomElement([
                Finance::OPERATION_SELL,
                Finance::OPERATION_SELL_RETURN,
                Finance::OPERATION_BUY,
                Finance::OPERATION_BUY_RETURN,
            ]),
            'sum' => 100 * $this->faker->numberBetween(1000, 10000),
            'finance_group_id' => 1,
            'payment_type' => $this->faker->randomElement([
                Finance::PAYMENT_CASH,
                Finance::PAYMENT_ELECTRONICALLY,
            ])
        ];
    }
}
