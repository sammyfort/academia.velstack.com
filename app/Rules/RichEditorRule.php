<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class RichEditorRule implements ValidationRule
{


    public function __construct(protected int $max = 3000)
    {

    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $plainText = trim(strip_tags((string) $value));

        if (mb_strlen($plainText) > $this->max) {
            $fail("The $attribute may not be greater than $this->max characters.");
        }
    }
}
