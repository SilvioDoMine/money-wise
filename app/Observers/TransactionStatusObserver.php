<?php

namespace App\Observers;

use App\Jobs\NotificationJob;
use App\Models\TransactionStatus;

class TransactionStatusObserver
{
    /**
     * Dispara uma notificação para o usuário sempre que a transação muda de estado.
     *
     * @param  \App\Models\TransactionStatus  $transactionStatus
     * @return void
     */
    public function created(TransactionStatus $transactionStatus)
    {
        NotificationJob::dispatch($transactionStatus)
            ->onQueue('default');
    }
}
