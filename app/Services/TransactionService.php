<?php

namespace App\Services;

use App\Gateways\Credit;
use App\Gateways\Wise;
use App\Jobs\TransactionJob;
use App\Models\Transaction;
use App\Models\TransactionStatus;
use App\Models\User;
use Exception;

class TransactionService
{
    public BalanceService $balanceService;
    public UserService $userService;
    public Wise $gatewayWise;
    public Credit $gatewayCredit;

    public function __construct(
        BalanceService $balanceService,
        UserService $userService,
        Credit $gatewayCredit,
        Wise $gatewayWise
    ) {
        $this->balanceService = $balanceService;
        $this->userService = $userService;
        $this->gatewayCredit = $gatewayCredit;
        $this->gatewayWise = $gatewayWise;
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

        TransactionJob::dispatch($transaction)
            ->onQueue('default');

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

        $transaction->fees()->create([
            'user_id' => $feePayerId,
            'amount' => Transaction::FEE_TRANSACTION,
        ]);

        $fee = $this->calculateCreditFee($transaction);

        if ($fee > 0.00) {
            $transaction->fees()->create([
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

    public function transfer(Transaction $transaction): void
    {
        switch ($transaction->type) {
            case Transaction::TYPE_TRANSFER:
                $this->handleTransfer($transaction);
                break;
            case Transaction::TYPE_CREDIT:
                $this->handleCredit();
                break;
            case Transaction::TYPE_DEBIT:
                $this->handleDebit();
                break;
            default:
                throw new Exception("Um tipo de transação não implementado foi usado. TYPE {$transaction->type}");
        }
    }

    /**
     * * Um usuário não pode transferir dinheiro da conta dele, pra ele mesmo.
     *
     * @param Transaction $transaction
     * @return void
     */
    public function handleTransfer(Transaction $transaction): void
    {
        if ($transaction->payer === $transaction->payee) {
            $this->setStatus($transaction, TransactionStatus::STATUS_CANCELLED);
            return;
        }
        // Se o usuário que estiver enviando for ele mesmo, cancela a operação.
        $money = [
            'charged' => $transaction->requested_amount,
            'sent' => $transaction->requested_amount,
        ];

        // Calcula valor da taxa &&
        $transaction->fees->each(function ($item) use ($transaction, &$money) {
            if ($item->user_id == $transaction->payer->id) {
                $money['charged'] += $item->amount;
            }
            
            if ($item->user_id == $transaction->payee->id) {
                $money['sent'] -= $item->amount;
            }
        });

        $response = $this->gatewayWise->charge($transaction, $money);

        $this->setStatus($transaction, $response['status']);

        if ($response['status'] != TransactionStatus::STATUS_APPROVED) {
            return;
        }

        $this->balanceService->addFunds($transaction->payee, $money['sent']);
    }

    public function handleCredit()
    {

    }

    public function handleDebit(Transaction $transaction)
    {
        $this->setStatus($transaction, TransactionStatus::STATUS_CANCELLED);
    }

    /**
     * Muda o status da transação.
     *
     * @param Transaction $transaction
     * @param integer $status
     * @return void
     */
    public function setStatus(Transaction $transaction, int $status): void
    {
        $transaction->statuses()->create([
            'status' => $status,
        ]);
    }
}
