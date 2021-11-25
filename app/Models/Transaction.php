<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
     * Quantidade da taxa cobrada a cada transação do sistema.
     * Valor fixo em reais.
     *
     * @var float
     */
    const FEE_TRANSACTION = 1.00;

    /**
     * Quantidade da porcentagem cobrada através de uma transação por cartão de crédito.
     * Valor percentual.
     * 
     * @var float
     */
    const FEE_CREDIT = 3.00;

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
     * Retorna a relação da transação com o usuário que está recebendo-a.
     *
     * @return BelongsTo
     */
    public function payee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to', 'id');
    }

    /**
     * Retorna a relação da transação com o usuário que está realizando-a.
     *
     * @return BelongsTo
     */
    public function payer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from', 'id');
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
