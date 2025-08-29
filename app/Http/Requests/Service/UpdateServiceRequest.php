<?php

namespace App\Http\Requests\Service;

use App\Rules\GPSRule;
use App\Rules\MobileNumber;
use App\Rules\RichEditorRule;
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
        logger()->info('Incoming keys', array_keys(request()->all()));

        return [
            'country_id' => ['required', 'exists:countries,id'],
            'title' => ['required'],
            'description' => ['required', new RichEditorRule()],
            'first_mobile' => ['required'],
            'business_name' => ['nullable'],
            'second_mobile' => ['nullable'],
            'whatsapp_mobile' => ['nullable', new MobileNumber()],
            'email' => ['nullable'],
            'address' => ['nullable'],
            'region_id' => ['required', 'exists:regions,id'],
            'town' => ['required'],
            'gps' => ['nullable', new GPSRule()],
            'category_id' => ['required'],
            'years_experience' => ['required', 'integer', 'min:0', 'max:70'],
            'video_link'=> ['nullable', 'url'],
            'featured' => ['nullable', 'image', 'max:2048'],

            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['image', 'max:2048'],
            'removed_gallery_urls' => ['nullable', 'array'],
            'removed_gallery_urls.*' => ['string'],

        ];
    }
}
