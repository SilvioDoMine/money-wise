<?php

namespace App\Gateways;

use App\Interfaces\GatewayInterface;
use App\Models\Transaction;

class Credit implements GatewayInterface
{
    public function charge(Transaction $transaction, array $money): array
    {
        return [];
    }
}