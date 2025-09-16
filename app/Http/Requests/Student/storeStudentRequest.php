<?php

namespace App\Http\Requests\Student;

use App\Enums\Gender;
use App\Enums\Region;
use App\Enums\Religion;
use App\Rules\FileSizeRule;
use Illuminate\Validation\Rule;

use Illuminate\Foundation\Http\FormRequest;

class storeStudentRequest extends FormRequest
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
            'image' => ['nullable', 'image', new FileSizeRule()],
            'first_name' => ['required'],
            'last_name' => ['required'],
            'middle_name' => ['nullable'],
            'index_number' => ['nullable',  Rule::unique('', 'index_number')],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'numeric'],
            'dob' => ['required', 'date'],
            'bio' => ['required', 'string'],
            'city' => ['required', 'string'],

            'gender' => ['required', Rule::in(Gender::cases())],
            'religion' => ['required', Rule::in(Religion::cases())],
            'region' => ['required', Rule::in(Region::cases())],
            'parents' => ['required', 'array', 'max:2'],
            'class_id' => ['required', Rule::exists('classrooms', 'id')->where('school_id', school()->id)],

        
        ];
    }
}
