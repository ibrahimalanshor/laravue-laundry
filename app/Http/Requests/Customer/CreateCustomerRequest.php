<?php

namespace App\Http\Requests\Customer;

use App\Http\Requests\JsonFormRequest;

class CreateCustomerRequest extends JsonFormRequest
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
            'name' => 'required|string|unique:customers',
            'phone' => 'required|numeric',
            'address' => 'required|string'
        ];
    }
}
