<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    use HasFactory;

    /**
     * ID do tipo de transação realizada por transferência de saldo interna.
     *
     * @var int
     */
    const TYPE_TRANSFER = 1;

    /**
     * ID do tipo de transação realizada por cartão de crédito.
     *
     * @var int
     */
    const TYPE_CREDIT = 2;

    /**
     * ID do tipo de transação realizada por cartão de débito.
     *
     * @var int
     */
    const TYPE_DEBIT = 3;

    /**
     * Atributos que podem receber atribuções em massa.
     *
     * @var array
     */
    public $fillable = [
        'type',
        'from',
        'to',
        'requested_amount',
    ];

    /**
     * Retorna a relação da taxa cobrada na transação.
     *
     * @return HasOne
     */
    public function fee(): HasOne
    {
        return $this->hasOne(TransactionFee::class);
    }

    /**
     * Retorna a lista de status da transação.
     *
     * @return HasMany
     */
    public function statuses(): HasMany
    {
        return $this->hasMany(TransactionStatus::class);
    }
}
