<?php

namespace App\Http\Requests\Class;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubjectToClassRequest extends FormRequest
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
            'class_id' => ['required', Rule::exists('classrooms', 'id')->where('school_id', school()->id)],
            'subjects' => ['array'],
            'subjects.*' => ['int', Rule::exists('subjects', 'id')->where('school_id', school()->id)]
        ];
    }
}
