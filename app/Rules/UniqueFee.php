<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueFee implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */

    public function __construct(protected $name, $term_id)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (school()->fees()->)
    }
}
