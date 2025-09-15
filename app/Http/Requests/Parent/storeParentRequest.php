<?php

namespace App\Http\Requests\Parent;

use App\Rules\ValidGhanaCard;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class storeParentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'email' => ['required', Rule::unique('__parents', 'email')],
            'phone' => ['required', Rule::unique('__parents', 'phone')],
            'address' => ['required'],
            'identity_number' => ['required', new ValidGhanaCard()],
            'occupation' => ['required', 'string'],
        ];
    }
}
