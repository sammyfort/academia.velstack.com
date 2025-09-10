<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class FileSizeRule implements ValidationRule
{
    public function __construct(protected int $maxFileSize = 50)
    {
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value->getSize() / 1024 > $this->maxFileSize) {
            $fail("File size cannot be larger than $this->maxFileSize kb.");
        }
    }
}
