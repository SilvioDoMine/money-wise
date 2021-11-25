<?php

namespace App\Services;

use App\Integrations\NotificationPlus;
use App\Models\Transaction;
use App\Models\TransactionStatus;
use Exception;

class NotificationService
{
    public NotificationPlus $notifier;

    public function __construct(NotificationPlus $notifier)
    {
        $this->notifier = $notifier;
    }

    /**
     * Realiza o envio de uma notificação baseado nos status existentes,
     * utilizando o serviço de notificação padrão definido no construtor.
     *
     * @param TransactionStatus $status
     * @return void
     */
    public function notify(TransactionStatus $status): void
    {
        switch ($status->status) {
            case TransactionStatus::STATUS_PENDING:
                $this->handlePending($status->transaction);
                break;
            case TransactionStatus::STATUS_CANCELLED:
                $this->handleCancelled($status->transaction);
                break;
            case TransactionStatus::STATUS_REFUNDED:
                $this->handleRefunded($status->transaction);
                break;
            case TransactionStatus::STATUS_DISPUTE:
                $this->handleDispute($status->transaction);
                break;
            case TransactionStatus::STATUS_APPROVED:
                $this->handleApprove($status->transaction);
                break;
            default:
                throw new Exception("Não foi possível encontrar nenhuma mensagem de notificação para o status {$status->status}");
        }
    }

    /**
     * Lida com a lógica de enviar notificações para quem está com a transação pendente.
     * Notifica quem realizou a transação.
     *
     * @param Transaction $transaction
     * @return void
     */
    public function handlePending(Transaction $transaction): void
    {
        $this->send(
            $transaction->payer->email,
            "Você iniciou uma transferência no valor de R$ {$transaction->requested_amount}. Te notificaremos atualizações.",
            "Não foi possível enviar a notificação de pagamento pendente."
        );
    }

    /**
     * Lida com a lógica de enviar notificações para quem teve a transação cancelada.
     * Notifica quem realizou a transação.
     *
     * @param Transaction $transaction
     * @return void
     */
    public function handleCancelled(Transaction $transaction): void
    {
        $this->send(
            $transaction->payer->email,
            "Sua transferência no valor de {$transaction->requested_amount} foi cancelada.",
            "Não foi possível enviar a notificação de pagamento cancelado."
        );
    }

    /**
     * Lida com a lógica de enviar notificações para quem teve a transação reembolsada.
     * Notifica quem realizou e recebeu a transação.
     *
     * @param Transaction $transaction
     * @return void
     */
    public function handleRefunded(Transaction $transaction): void
    {
        $this->send(
            $transaction->payer->email,
            "Sua transação no valor de R$ {$transaction->requested_amount} foi reembolsada.",
            "Não foi possível enviar a notificação de pagamento reembolsado."
        );

        $this->send(
            $transaction->payee->email,
            "Uma transação que você recebeu no valor de R$ {$transaction->requested_amount} entrou em disputa.",
            "Não foi possível enviar a notificação de pagamento reembolsado."
        );
    }

    /**
     * Lida com a lógica de enviar notificações para quem está com a transação em disputa.
     * Notifica quem realizou a transação.
     *
     * @param Transaction $transaction
     * @return void
     */
    public function handleDispute(Transaction $transaction): void
    {
        $this->send(
            $transaction->payer->email,
            "Acabamos de abrir disputa na sua transação no valor de {$transaction->requested_amount}.",
            "Não foi possível enviar a notificação de pagamento em disputa."
        );
    }

    /**
     * Lida com a lógica de enviar notificações para quem teve a transação aprovada.
     * Notifica quem enviou, e quem recebeu a transação.
     *
     * @param Transaction $transaction
     * @return void
     */
    public function handleApprove(Transaction $transaction): void
    {
        $this->send(
            $transaction->payer->email,
            "Você acabou de realizar uma transferência no valor de R$ {$transaction->requested_amount}.",
            "Não foi possível enviar a notificação de pagamento realizado."
        );

        $this->send(
            $transaction->payee->email,
            "Você acabou de receber um saldo no valor de R$ {$transaction->requested_amount}.",
            "Não foi possível enviar a notificação de pagamento recebido."
        );
    }

    /**
     * Abstração que executa o envio de notificações da integração padrão (definida no construtor).
     *
     * @param string $email
     * @param string $message
     * @param string $exceptionMessage
     * @return void
     */
    private function send(string $email, string $message, string $exceptionMessage): void
    {
        $response = $this->notifier->send($email, ['title' => $message]);
 
         if (! $response) {
             throw new Exception($exceptionMessage);
         }
    }
}
