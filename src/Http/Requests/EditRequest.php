<?php

namespace ExclusiveDev\Commenist\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'comment' => 'required|string'
        ];
    }
}
