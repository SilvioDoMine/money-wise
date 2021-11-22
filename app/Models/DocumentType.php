<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    use HasFactory;

    /**
     * ID do tipo de documento CPF.
     *
     * @var int
     */
    const CPF_ID = 1;

    /**
     * ID do tipo de documento CNPJ.
     *
     * @var int
     */
    const CNPJ_ID = 2;

    /**
     * Indica se a model deve possuir timestamps.
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Atributos que podem receber atribuções em massa.
     *
     * @var array
     */
    public $fillable = [
        'id',
        'name'
    ];
}
