<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\TransactionStatus;
use App\Models\User;

class TransactionService
{
    public UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Dá início a uma nova transação.
     *
     * @param User $user
     * @param array $data
     * @return bool
     */
    public function store(User $user, array $data): bool
    {
        if (! $this->canPerformTransaction($user)) {
            return false;
        }

        $transaction = new Transaction();
        $transaction->type = $data['type'];
        $transaction->from = $user->id;
        $transaction->to = $data['payee'];
        $transaction->requested_amount = $data['amount'];
        $transaction->save();

        return true;
    }

    /**
     * Verifica se o usuário pode performar uma transação.
     *
     * @param User $user
     * @return boolean
     */
    private function canPerformTransaction(User $user): bool
    {
        if ($this->userService->getRole($user) == User::CPF_NAME) {
            return true;
        }

        return false;
    }

    /**
     * Calcula se há alguma taxa/tarifa na transação, e consegue os valores finais da operação.
     * 
     * * Toda transação é cobrada a taxa de 1 R$. Essa taxa é paga por quem realiza, exceto no caso de loja.
     * * Nas transações via cartão de crédito, quem envia o dinheiro também paga uma taxa de 3% do valor da transação.
     *
     * @param Transaction $transaction
     * @return void
     */
    public function processTransaction(Transaction $transaction)
    {
        $feePayerId = $this->userService->getRole($transaction->payee) == User::CNPJ_NAME ?
            $transaction->payee->id :
            $transaction->payer->id;

        $transaction->fee()->create([
            'user_id' => $feePayerId,
            'amount' => Transaction::FEE_TRANSACTION,
        ]);

        $fee = $this->calculateCreditFee($transaction);

        if ($fee > 0.00) {
            $transaction->fee()->create([
                'user_id' => $transaction->payer->id,
                'amount' => $fee,
            ]);
        }
    }

    /**
     * Calcula a quantidade de taxa que deverá ser cobrada da transação em transações
     * de cartão de crédito e débito.
     *
     * @param Transaction $transaction
     * @return float
     */
    private function calculateCreditFee(Transaction $transaction): float
    {
        if ($transaction->type != Transaction::TYPE_CREDIT && $transaction->type != Transaction::TYPE_DEBIT) {
            return 0.00;
        }

        return $transaction->requested_amount * ( Transaction::FEE_CREDIT / 100 );
    }
}
