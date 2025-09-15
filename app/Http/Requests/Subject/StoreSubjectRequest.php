<?php

namespace App\Http\Requests\Subject;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Subjects;
use Illuminate\Validation\Rule;
class StoreSubjectRequest extends FormRequest
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
            'name' =>  ['required', Rule::in(Subjects::toArray()),
                 Rule::unique('subjects', 'name')
                 ->where('school_id', school()->id)],


            'code' => ['nullable', 'string', 'max:100', 'unique:subjects,code'],
        ];
    }
}
