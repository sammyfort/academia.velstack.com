<?php

namespace App\Http\Requests\Job;

use App\Enums\JobMode;
use App\Enums\JobModeOfApply;
use App\Enums\JobStatus;
use App\Enums\JobType;
use App\Rules\RichEditorRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Mews\Purifier\Facades\Purifier;

class UpdateJobRequest extends FormRequest
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
            'country_id' => ['required', 'exists:countries,id'],
            'company_name' => ['required'],
            'title' => ['required', 'string', 'max:255'],
            'job_type' => ['required', Rule::in(JobType::toArray())],
            'work_mode' => ['required', Rule::in(JobMode::toArray())],
            'status' => ['required', Rule::in(JobStatus::toArray())],
            'categories' => ['required', 'array'],
            'summary' => ['nullable', 'string'],
            'description' => ['required', new RichEditorRule()],

            'region_id' => ['required'],
            'town' => ['required'],
            'salary' => ['nullable'],


            'apply_mode' => ['required',Rule::in(JobModeOfApply::toArray())],

            'how_to_apply' => [
                'nullable',
                'required_if:apply_mode,instruction',
                'required_if:apply_mode,both',
                new RichEditorRule()
            ],

            'application_link' => [
                'nullable',
                'required_if:apply_mode,external_link',
                'required_if:apply_mode,both',
                'url',
            ],

            'deadline' => ['required', 'date', 'after:today'],
            'company_logo' => ['nullable','image', 'max:2048']

        ];
    }

    public function prepareForValidation(): void
    {
        if ($this->filled('description')) {
            $this->merge([
                'description' => Purifier::clean($this->input('description')),
            ]);
        }

        if ($this->filled('how_to_apply')) {
            $this->merge([
                'how_to_apply' => Purifier::clean($this->input('how_to_apply')),
            ]);
        }
    }
}
