<?php

namespace ExclusiveDev\Commenist\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VoteRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'vote' => 'required|boolean'
        ];
    }
}