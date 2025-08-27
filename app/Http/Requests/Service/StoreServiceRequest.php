<?php

namespace App\Http\Requests\Service;

use App\Rules\GPSRule;
use App\Rules\MobileNumber;
use Illuminate\Foundation\Http\FormRequest;
use Mews\Purifier\Facades\Purifier;

class StoreServiceRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:255'],
            'first_mobile' => ['required', new MobileNumber()],
            'second_mobile' => ['nullable', new MobileNumber()],
            'whatsapp_mobile' => ['nullable', new MobileNumber()],
            'business_name' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email'],
            'address' => ['nullable', 'string', 'max:50'],
            'region_id' => ['required', 'exists:regions,id'],
            'town' => ['required', 'string', 'max:50'],
            'gps' => ['nullable', new GPSRule()],
            'category_id' => ['required'],
            'featured' => ['required', 'image', 'max:2048'],
            'gallery' => ['array'],
            'gallery.*' => ['image', 'max:2048'],
            'years_experience' => ['required', 'integer'],
            'video_link'=> ['nullable', 'url']
        ];
    }

    public function prepareForValidation(): void
    {
        if ($this->filled('description')) {
            $this->merge([
                'description' => Purifier::clean($this->input('description')),
            ]);
        }
    }
}
