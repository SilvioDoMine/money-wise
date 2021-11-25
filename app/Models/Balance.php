<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    use HasFactory;

    /**
     * Atributos que podem receber atribuções em massa.
     *
     * @var array
     */
    public $fillable = [
        'user_id',
        'amount'
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'amount' => 0.00,
    ];
}
