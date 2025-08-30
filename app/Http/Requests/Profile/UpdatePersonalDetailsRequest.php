<?php

namespace App\Http\Requests\Profile;

use App\Rules\MobileNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePersonalDetailsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'firstname' => ['required', 'string', 'max:100'],
            'lastname' => ['required', 'string', 'max:100'],
            'country_id' => ['required', 'exists:countries,id'],
            'mobile' => ['required', 'string', 'max:100', new MobileNumber(), Rule::unique('users')->ignore(request()->user())],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:100', Rule::unique('users')->ignore(request()->user())],
        ];
    }

    public function attributes(): array
    {
        return [
            'country_id' => 'Country',
        ];
    }

    public function messages(): array
    {
        return  [
            'country_id.required' => 'Please select a country',
        ];
    }
}
