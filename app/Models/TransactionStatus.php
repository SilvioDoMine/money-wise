<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionStatus extends Model
{
    use HasFactory;

    /**
     * ID do status de transação pendente.
     *
     * @var int
     */
    const STATUS_PENDING = 1;

    /**
     * ID do status de transação cancelada.
     *
     * @var int
     */
    const STATUS_CANCELLED = 2;

    /**
     * ID do status de transação reembolsada.
     *
     * @var int
     */
    const STATUS_REFUNDED = 3;

    /**
     * ID do status de transação em disputa.
     *
     * @var int
     */
    const STATUS_DISPUTE = 4;

    /**
     * ID do status de transação aprovada.
     *
     * @var int
     */
    const STATUS_APPROVED = 5;

    /**
     * Atributos que podem receber atribuções em massa.
     *
     * @var array
     */
    public $fillable = [
        'transaction_id',
        'status',
    ];
}
