<?php

namespace App\Interfaces;

use Illuminate\Http\Client\Response;

interface NotificationInterface
{
    /**
     * Dispara um envio de notificação para o e-mail desejado.
     *
     * @param string $email
     * @param array $message
     * @return boolean
     */
    public function send(string $email, array $message): bool;

    /**
     * Normaliza e prepara a payload única de acordo com o serviço em questão.
     *
     * @param string $email
     * @param array $message
     * @return array
     */
    public function preparePayload(string $email, array $message): array;

    /**
     * Realiza a requisição para o provedor e devolve a resposta do envio.
     *
     * @param array $payload
     * @return Response
     */
    public function notify(array $payload): Response;
}