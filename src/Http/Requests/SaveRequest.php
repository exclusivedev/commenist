<?php

namespace ExclusiveDev\Commenist\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveRequest extends FormRequest
{
    public function rules(): array
    {
        return [
			'encrypted_key' => 'required|string',
            'comment' => 'required_without:comments|string',
            'comments.*' => 'required_without:comment|string',
        ];
    }
}
