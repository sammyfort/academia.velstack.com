<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueValues implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function __construct(protected $key)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $attributeBase = explode('.', $attribute)[0]; // Get 'parents' from 'parents.*.email'
        $requestData = request()->input($attributeBase, []); // Fetch 'parents' data from the request

        // Check if the extracted data is an array
        if (!is_array($requestData)) {
            $fail("The {$attributeBase} data is not valid.");
            return;
        }

        // Collect all values for the specified field ($this->key)
        $values = array_column($requestData, $this->key);

        // Check for duplicate values
        if (count($values) !== count(array_unique($values))) {
            $fail("The {$this->key} field has duplicate values.");
        }
    }
}
