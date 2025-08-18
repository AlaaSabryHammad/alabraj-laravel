<?php

namespace Database\Factories;

use App\Models\RevenueVoucher;
use Illuminate\Database\Eloquent\Factories\Factory;

class RevenueVoucherFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RevenueVoucher::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'status' => $this->faker->randomElement(['approved', 'pending', 'rejected']),
            'voucher_date' => $this->faker->dateTimeThisMonth(),
            'amount' => $this->faker->numberBetween(100, 10000),
            'description' => $this->faker->sentence,
            'created_by' => 1,
            'approved_by' => 1,
        ];
    }
}
