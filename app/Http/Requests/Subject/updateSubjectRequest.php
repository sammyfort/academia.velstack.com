<?php

namespace App\Http\Requests\Subject;

use App\Enums\Subjects;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class updateSubjectRequest extends FormRequest
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
        $subjectId = $this->route('subjects');
        return [
            'name' =>  [
                'required',
                Rule::in(Subjects::cases()),
                Rule::unique('subjects', 'name')
                    ->where('school_id', school()->id)
                    ->ignore($subjectId),
            ],


            'code' => ['nullable', 'string', 'max:100',
                Rule::unique('subjects', 'code')
                ->where('school_id', school()->id)
                    ->ignore($subjectId),
            ],
        ];
    }
}
