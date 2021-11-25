<?php

namespace App\Interfaces;

use App\Models\Transaction;

interface GatewayInterface
{
    public function charge(Transaction $transaction, array $money): array;
}
