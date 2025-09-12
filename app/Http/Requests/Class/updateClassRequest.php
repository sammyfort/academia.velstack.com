<?php

namespace App\Http\Requests\Class;

use App\Enums\ClassGroup;
use App\Enums\ClassLevel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class updateClassRequest extends FormRequest
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
            'name' => ['required', Rule::unique('classrooms', 'name')
                ->where('school_id', school()->id)
                ->ignore($this->route('class'))
            ],
            'level' => ['required', Rule::in(ClassLevel::toArray())],
            'group' => ['required', Rule::in(ClassGroup::toArray())],
        ];
    }
}
