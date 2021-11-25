<?php

namespace App\Integrations;

use App\Interfaces\NotificationInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class NotificationPlus implements NotificationInterface
{
    public string $endpoint;
    
    public function __construct()
    {
        $this->endpoint = config('integrations.notification_plus_url');
    }

    public function send(string $email, array $message): bool
    {
        $response = $this->notify(
            $this->preparePayload($email, $message)
        );

        return $response->successful();
    }

    public function preparePayload(string $email, array $message): array
    {
        return array_merge(['email' => $email], $message);
    }

    public function notify(array $payload): Response
    {
        return Http::acceptJson()
            ->retry(3, 100, function ($exception) {
                return $exception instanceof ConnectionException;
            })
            ->post($this->endpoint, $payload);
    }
}