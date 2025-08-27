<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Money implements Rule
{
    public function passes($attribute, $value)
    {
        return preg_match('/^(?:\d{1,3}(?:,\d{3})*|\d+)(?:\.\d{2})?$/', $value);
    }

    public function message()
    {
        return "The :attribute must be a valid amount.";
    }
}
