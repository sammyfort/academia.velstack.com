<?php

namespace App\Http\Requests\Class;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttendanceRequest extends FormRequest
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
            'student_id' => ['required', Rule::exists('students', 'id')->where('school_id', school()->id)],
            'term_id' =>  ['required', Rule::exists('terms', 'id')->where('school_id', school()->id)],
            'date' => ['required', 'date'],
            'present' => ['required', 'boolean'],
        ];
    }
}
