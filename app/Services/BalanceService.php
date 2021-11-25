<?php

namespace App\Services;

use App\Models\Balance;
use App\Models\User;

class BalanceService
{
    /**
     * Realiza uma cobrança no saldo de um usuário.
     *
     * @return bool
     */
    public function charge(User $user, float $amount): bool
    {
        $balance = Balance::firstOrCreate([
            'user_id' => $user->id,
        ]);

        $balance->amount -= $amount;

        return $balance->save();
    }

    /**
     * Realiza uma adição de fundos no saldo de um usuário.
     *
     * @param User $user
     * @param float $amount
     * @return bool
     */
    public function addFunds(User $user, float $amount): bool
    {
        $balance = Balance::firstOrCreate([
            'user_id' => $user->id,
        ]);

        $balance->amount += $amount;

        return $balance->save();
    }

    /**
     * Verifica se o usuário tem fundos suficiente para realizar a operação.
     *
     * @param User $user
     * @param float $amount
     * @return boolean
     */
    public function haveFunds(User $user, float $amount): bool
    {
        return isset($user->balance) && $user->balance->amount >= $amount;
    }
}
