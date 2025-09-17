<?php

namespace App\Http\Requests;

use App\Enums\Gender;
use App\Enums\MaritalStatus;
use App\Enums\Region;
use App\Enums\Religion;
use App\Enums\StaffExperience;
use App\Enums\StaffQualification;
use App\Enums\StaffStatus;
use App\Enums\StaffType;
use App\Enums\Title;
use App\Rules\FileSizeRule;
use App\Rules\ValidGhanaCard;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class storeStaffRequest extends FormRequest
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
            'status' => ['required', Rule::in(StaffStatus::toArray())],
            'roles' => ['nullable', 'array'],
            'roles.*' => [Rule::exists('roles', 'id')->where('school_id', school()->id)],
            'title' => ['required', Rule::in(Title::cases())],
            'licence_no' => ['nullable', Rule::unique('staff', 'licence_no')],
            'staff_id' => ['nullable', Rule::unique('staff', 'staff_id')],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('staff', 'email')],
            'phone' => ['required', 'numeric', Rule::unique('staff', 'phone')],
            'national_id' => ['required', new ValidGhanaCard()],
            'basic_salary' => ['required', 'numeric'],

            'gender' => ['required', Rule::in(Gender::cases())],
            'dob' => ['required', 'date'],
            'region' => ['required', Rule::in(Region::cases())],
            'city' => ['required', 'string', 'max:255'],

            'religion' => ['required', Rule::in(Religion::cases())],
            'marital_status' => ['required', Rule::in(MaritalStatus::cases())],
            'experience' => ['required', Rule::in(StaffExperience::cases())],
            'qualification' => ['required', Rule::in(StaffQualification::cases())],

//            'bank.name' => ['required'],
//            'bank.branch' => ['required'],
//            'bank.account_number' => ['required'],
        ];
    }
}
