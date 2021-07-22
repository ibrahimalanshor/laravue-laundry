<?php

namespace App\Http\Requests\Packet;

use App\Http\Requests\JsonFormRequest;

class UpdatePacketRequest extends JsonFormRequest
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
            'name' => 'required|string|unique:packets,name,'.$this->packet->id,
            'unit' => 'required|alpha|max:2',
            'time' => 'required|integer|max:100',
            'cost' => 'required|numeric|between:1,999999'
        ];
    }
}
