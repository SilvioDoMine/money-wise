<?php

namespace App\Observers;

use App\Models\Transaction;
use App\Models\TransactionStatus;
use App\Services\TransactionService;

class TransactionObserver
{
    public TransactionService $service;

    public function __construct(TransactionService $service)
    {
        $this->service = $service;
    }

    /**
     * Handle the Transaction "created" event.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function created(Transaction $transaction): void
    {
        $transaction->statuses()->create([
            'status' => TransactionStatus::STATUS_PENDING,
        ]);

        $this->service->processTransaction($transaction);
    }
}
