<?php

namespace App\Gateways;

use App\Interfaces\GatewayInterface;
use App\Models\Transaction;
use App\Models\TransactionStatus;
use App\Models\User;
use App\Services\BalanceService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class Wise implements GatewayInterface
{
    /**
     * Mensagem de autorizado da payload.
     * 
     * @var string
     */
    const AUTHORIZED_MESSAGE = 'Autorizado';

    public BalanceService $balanceService;
    public string $endpoint;

    public function __construct(BalanceService $balanceService)
    {
        $this->balanceService = $balanceService;
        $this->endpoint = config('integrations.gateway_wise_url');
    }

    public function charge(Transaction $transaction, array $money): array
    {
        if (! $this->doPay($transaction->payer, $money['charged'])) {
            return [
                'status' => TransactionStatus::STATUS_CANCELLED,
            ];
        }

        return [
            'status' => TransactionStatus::STATUS_APPROVED,
        ];
    }

    public function doPay(User $payer, int $money): bool
    {
        if (! $this->balanceService->haveFunds($payer, $money)) {
            return false;
        }

        if (! $this->authorizePayment($payer)) {
            return false;
        }

        return $this->balanceService->charge($payer, $money);
    }

    public function authorizePayment(User $payer): bool
    {
        $response = Http::acceptJson()
            ->retry(3, 100, function ($exception) {
                return $exception instanceof ConnectionException;
            })
            ->get($this->endpoint, [
                'user_id' => $payer->id,
            ]);

        if (! $response->successful()) {
            return false;
        }

        if (! isset($response->json()['message'])) {
            return false;
        }

        if ($response->json()['message'] != self::AUTHORIZED_MESSAGE) {
            return false;
        }

        return true;
    }
}