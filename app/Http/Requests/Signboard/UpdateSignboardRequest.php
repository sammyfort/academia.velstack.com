<?php

namespace App\Http\Requests\Signboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSignboardRequest extends FormRequest
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
            'business_id' => ['required', Rule::exists('businesses', 'id')->where('user_id', request()->user()->id)],
            'region_id' => ['required', Rule::exists('regions', 'id')],
            'town' => ['required', 'string'],
            'street' => ['nullable', 'string'],
            'landmark' => ['required', 'string'],
            'blk_number' => ['nullable', 'string'],
            'gps' => ['required', 'string'],
            'featured_image' => ['nullable', 'image', 'max:2048'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['image', 'max:2048'],
            'removed_gallery_urls' => ['array'],
            'removed_gallery_urls.*' =>['string'],
        ];
    }
}
