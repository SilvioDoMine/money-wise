<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionFee extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * Atributos que podem receber atribuções em massa.
     *
     * @var array
     */
    public $fillable = [
        'transaction_id',
        'user_id',
        'amount',
    ];
}
