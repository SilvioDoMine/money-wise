<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $typeList = [
            Transaction::TYPE_TRANSFER,
            Transaction::TYPE_CREDIT,
            Transaction::TYPE_DEBIT,
        ];

        return [
            'type' => $this->faker->randomElement($typeList),
            'from' => fn () => User::factory()->withCpf()->create(),
            'to' => fn () => User::factory()->create(),
            'requested_amount' => $this->faker->randomFloat(2, 5, 100),
        ];
    }
}
