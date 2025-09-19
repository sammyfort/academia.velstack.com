<?php

namespace App\Http\Requests\Class;

use App\Enums\ClassRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\UniqueStaffSubject;
use App\Rules\UniqueClassStaff;

class StaffClassRequest extends FormRequest
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

            'class_id' => [
                'required',
                'integer',
                Rule::exists('classrooms', 'id')->where(function ($query) {
                    $query->where('school_id', school()->id);
                }),

            ],

            'staff_id' => [
                'required',
                'integer',
                Rule::exists('staff', 'id')->where(function ($query) {
                    $query->where('school_id', school()->id);
                }),
               
                 Rule::when(
                    $this->role === ClassRole::CLASS_TEACHER->value,
                    [ new UniqueClassStaff($this->class_id)]
                ),
            ],

            'role' => ['required', 'string', Rule::in(ClassRole::toArray())],

            'subjects' => [
                'array',
              
                Rule::prohibitedIf(fn() => $this->role === ClassRole::CLASS_TEACHER->value),
            ],

            'subjects.*' => [
                'integer',
                Rule::exists('subjects', 'id')->where(
                    fn($q) =>
                    $q->where('school_id', school()->id)
                ),
                
            ],
        ];
    }
}
