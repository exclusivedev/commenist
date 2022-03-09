<?php

namespace ExclusiveDev\Commenist\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetRequest extends FormRequest
{
    public function rules(): array
    {
        return [
			'encrypted_key' => 'required|string',
            'order_by' => 'string|min:1|max:11'
        ];
    }
}
