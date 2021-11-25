<?php

namespace App\Http\Requests;

use App\Models\Transaction;
use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $transactionTypes = [
            Transaction::TYPE_TRANSFER,
            Transaction::TYPE_CREDIT,
            Transaction::TYPE_DEBIT,
        ];

        return [
            'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'payee' => 'required|exists:users,id',
            'type' => 'required|in:' . implode(',', $transactionTypes),
        ];
    }
}
