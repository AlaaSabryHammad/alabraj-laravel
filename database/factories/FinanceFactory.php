<?php

namespace Database\Factories;

use App\Models\Finance;
use Illuminate\Database\Eloquent\Factories\Factory;

class FinanceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Finance::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'type' => $this->faker->randomElement([Finance::TYPE_INCOME, Finance::TYPE_EXPENSE]),
            'status' => $this->faker->randomElement([Finance::STATUS_COMPLETED, Finance::STATUS_PENDING]),
            'transaction_date' => $this->faker->dateTimeThisMonth(),
            'amount' => $this->faker->numberBetween(100, 10000),
            'description' => $this->faker->sentence,
            'category' => 'default_category',
            'payment_method' => 'cash',
        ];
    }
}
