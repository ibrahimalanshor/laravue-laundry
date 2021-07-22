<?php

namespace App\Http\Requests\Transaction;

use App\Http\Requests\JsonFormRequest;

class CreateTransactionRequest extends JsonFormRequest
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
        return [
            'note' => 'required|numeric|unique:transactions',
            'weight' => 'required|integer|between:0,99',
            'create_date' => 'required|date|after_or_equal:today',
            'finish_date' => 'required|date|after:create_date',
            'tax' => 'nullable|numeric|between:0,999999',
            'discount' => 'nullable|numeric|between:0,999999',
            'total' => 'required|numeric|between:0,999999',
            'packet_id' => 'required|exists:packets,id',
            'customer_id' => 'required|exists:customers,id',
            'user_id' => 'required|exists:users,id'
        ];
    }
}
