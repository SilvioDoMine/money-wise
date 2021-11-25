<?php

namespace App\Jobs;

use App\Models\TransactionStatus;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public TransactionStatus $transactionStatus;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(TransactionStatus $transactionStatus)
    {
        $this->transactionStatus = $transactionStatus;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(NotificationService $service)
    {
        $service->notify($this->transactionStatus);
    }
}
