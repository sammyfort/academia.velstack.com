<?php

namespace App\Http\Requests\Product;

use App\Enums\ProductStatus;
use App\Enums\YesNo;
use App\Rules\MobileNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Mews\Purifier\Facades\Purifier;

class UpdateProductRequest extends FormRequest
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
            'region_id' => ['required', 'exists:regions,id'],
            'name' => ['required', 'string'],
            'status' => ['required', Rule::in(ProductStatus::toArray())],
            'price' => ['required', 'numeric'],
            'is_negotiable' => ['required', Rule::in(YesNo::toArray())],
            'short_description' => ['nullable', 'string'],
            'first_mobile' => ['required', new MobileNumber()],
            'second_mobile' => ['nullable', new MobileNumber()],
            'whatsapp_mobile' => ['nullable', new MobileNumber()],
            'website' => ['nullable', 'string'],
            'town' => ['required', 'string'],
            'description' => ['required'],
            'categories' => ['required', 'array'],

            'featured' => ['nullable', 'image', 'max:2048'],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['image', 'max:2048'],
            'removed_gallery_urls' => ['nullable', 'array'],
            'removed_gallery_urls.*' => ['string'],
            'video_link'=> ['nullable', 'url']
        ];
    }

    public function prepareForValidation(): void
    {
        if ($this->has('description')) {
            $this->merge([
                'description' => Purifier::clean($this->input('description')),
            ]);
        }
    }
}
