<?php

namespace App\Http\Requests\Student;

use App\Enums\Gender;
use App\Enums\Region;
use App\Enums\Religion;
use App\Rules\FileSizeRule;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class updateStudentRequest extends FormRequest
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
        $studentId = $this->route('student'); // may be Model or ID
        if ($studentId instanceof \App\Models\Student) {
            $studentId = $studentId->id;
        }
        return [
            'image' => ['nullable', 'image', new FileSizeRule()],
            'first_name' => ['required', 'max:255'],
            'last_name' => ['required','max:255'],
            'middle_name' => ['nullable', 'max:255'],
            'index_number' => ['nullable',  Rule::unique('', 'index_number')],

            'email' => ['nullable', 'email',
                Rule::unique('students', 'email')
                ->ignore($studentId), 'max:255'],

            'phone' => ['nullable', 'string',
                Rule::unique('students', 'phone')
                ->ignore($studentId),
                'max:255'],

            'dob' => ['required', 'date', 'max:255'],
            'bio' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],

            'gender' => ['required', Rule::in(Gender::cases())],
            'religion' => ['required', Rule::in(Religion::cases())],
            'region' => ['required', Rule::in(Region::cases())],
            'parents' => ['required', 'array', 'max:2'],
            'class_id' => ['required', Rule::exists('classrooms', 'id')->where('school_id', school()->id)],

        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'dob' => $this->dob ? Carbon::parse($this->dob)->toDateString() : null,
        ]);
    }
}
