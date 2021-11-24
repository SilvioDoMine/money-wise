<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\TransactionStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionStatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $statusIds = [
            TransactionStatus::STATUS_PENDING,
            TransactionStatus::STATUS_CANCELLED,
            TransactionStatus::STATUS_REFUNDED,
            TransactionStatus::STATUS_DISPUTE,
            TransactionStatus::STATUS_APPROVED,
        ];

        return [
            'transaction_id' => Transaction::factory(),
            'status' => $this->faker->randomElement($statusIds),
        ];
    }
}
