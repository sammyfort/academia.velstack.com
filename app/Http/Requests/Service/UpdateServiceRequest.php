<?php

namespace App\Http\Requests\Service;

use App\Rules\GPSRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceRequest extends FormRequest
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
            'title' => ['required'],
            'description' => ['required'],
            'first_mobile' => ['required'],
            'business_name' => ['nullable'],
            'second_mobile' => ['nullable'],
            'email' => ['nullable'],
            'address' => ['nullable'],
            'region_id' => ['required', 'exists:regions,id'],
            'town' => ['required'],
            'gps' => ['nullable', new GPSRule()],
            'categories' => ['required', 'array'],

            'featured' => ['nullable', 'image', 'max:2048'],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['image', 'max:2048'],
            'removed_gallery_urls' => ['nullable', 'array'],
            'removed_gallery_urls.*' => ['string'],
        ];
    }
}
