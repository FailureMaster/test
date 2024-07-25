<?php

namespace App\Http\Requests\User\SalesStatus;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
            ],
        ];
    }
}