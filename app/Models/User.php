<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Nome do tipo de documento CPF.
     *
     * @var int
     */
    const CPF_NAME = 'customer';

    /**
     * Nome do tipo de documento CNPJ.
     *
     * @var int
     */
    const CNPJ_NAME = 'store';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'document_type_id',
        'document_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Retorna a relação com suas informações de saldo.
     *
     * @return HasOne
     */
    public function balance(): HasOne
    {
        return $this->hasOne(Balance::class);
    }

    /**
     * Retorna a relação com o tipo de documento do usuário.
     *
     * @return BelongsTo
     */
    public function documentType(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class);
    }

    /**
     * Retorna a relação de todas as transações realizadas pelo usuário.
     *
     * @return HasMany
     */
    public function transactionsMade(): HasMany
    {
        return $this->hasMany(Transaction::class, 'from');
    }

    /**
     * Retorna a relação de todas as transações recebidas pelo usuário.
     *
     * @return HasMany
     */
    public function transactionsReceived(): HasMany
    {
        return $this->hasMany(Transaction::class, 'to');
    }
}
