<?php

namespace App\Providers;

use App\Models\Transaction;
use App\Models\TransactionStatus;
use App\Observers\TransactionObserver;
use App\Observers\TransactionStatusObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        (new Transaction())->observe(TransactionObserver::class);
        (new TransactionStatus())->observe(TransactionStatusObserver::class);
    }
}
