<?php

namespace App\Http\Requests\Semester;

use App\Enums\AcademicTerm;
use App\Enums\TermStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class storeSemesterRequest extends FormRequest
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
            'name' => ['required',
                Rule::unique('terms', 'name')->where('school_id', school()->id),
                Rule::in(AcademicTerm::toArray())
            ],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'status' => ['required', Rule::in(TermStatus::cases())],
            'days' => ['required', 'numeric', 'max:99'],
            'next_term_begins' => ['required', 'date'],
        ];
    }
}
