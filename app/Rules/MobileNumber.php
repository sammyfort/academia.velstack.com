<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class MobileNumber implements InvokableRule
{
    public function __invoke(string $attribute, mixed $value, \Closure $fail)
    {
        if ($value && !preg_match('/^(\+|0)[0-9]{9,}$/', $value)) {
            $fail('The :attribute must be a valid mobile number.');
        }
    }
}
