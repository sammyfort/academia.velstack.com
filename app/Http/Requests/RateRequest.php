<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RateRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'overall' => ['required', 'numeric', 'min:1', 'max:5'],
            'customer_service' => ['required', 'numeric', 'min:1',  'max:5'],
            'quality' => ['required', 'numeric', 'min:1', 'max:5'],
            'price' => ['required', 'numeric', 'min:1', 'max:5'],
            'communication' => ['required', 'numeric', 'min:1', 'max:5'],
            'speed' => ['required', 'numeric', 'min:1', 'max:5'],
            'review' => ['required', 'string', 'max:400'],
            'ratable_id' => ['required', 'numeric'],
            'ratable_type' => ['required', 'string', Rule::in(['signboard', 'product', 'service'])],
        ];
    }

    public function messages(): array{
        return [
            'overall.min' => 'the :attribute rating must be filled',
            'customer_service.min' => 'the :attribute rating must be filled',
            'quality.min' => 'the :attribute rating must be filled',
            'price.min' => 'the :attribute rating must be filled',
            'communication.min' => 'the :attribute rating must be filled',
            'speed.min' => 'the :attribute rating must be filled',

            'ratable_id.required' => 'something went wrongssss rid1',
            'ratable_id.numeric' => 'something went wrongssss rid2',

            'ratable_type.required' => 'something went wrongsssss r1',
            'ratable_type.string' => 'something went wrongsssss r2',
        ];
    }
}
