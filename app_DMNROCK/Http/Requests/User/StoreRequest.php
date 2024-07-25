<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        $countryData = (array)json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryCodes = implode(',', array_keys($countryData));
        $mobileCodes = implode(',', array_column($countryData, 'dial_code'));
        $countries = implode(',', array_column($countryData, 'country'));

        return [
            'firstname' => [
                'required',
                'string',
            ],
            'lastname' => [
                'required',
                'string',
            ],
            'email' => [
               'required',
                'string',
                'email',
                'unique:users'
            ],
            'mobile_code' => [
                'required',
                'in:' . $mobileCodes
            ],
            'country_code' => [
                'required',
                'in:' . $countryCodes
            ],
            'country' => [
                'required',
                'in:' . $countries
            ]
        ];
    }
}