<?php

namespace App\Http\Requests\Signboard;

use App\Rules\GPSRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSignboardRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'business_id' => ['required', Rule::exists('businesses', 'id')->where('user_id', request()->user()->id)],
            'region_id' => ['required', Rule::exists('regions', 'id')],
            'categories' => ['required', 'array'],
            //'categories.*' => ['int'],
            'town' => ['required', 'string'],
            'street' => ['nullable', 'string'],
            'landmark' => ['required', 'string'],
            'blk_number' => ['nullable', 'string'],
            'gps' => ['required', 'string', new GPSRule()],
            'featured_image' => ['nullable', 'image', 'max:2048'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['image', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return  [
          'categories.required' => 'Please select at least one category'
        ];
    }
}
