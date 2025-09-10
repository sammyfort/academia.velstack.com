<?php

use App\Models\Staff;
use App\Models\Student;
use App\Models\Term;
use Carbon\Carbon;

if (! function_exists('defaultIcon')) {
    function defaultIcon($color = 'dark'): string
    {
        return $color === 'dark' ? asset('/images/openportal-dark.png') : asset('/images/openportal-white.png');
    }
}

if (! function_exists('favicon')) {
    function favicon( ): string
    {
        return   asset('/images/favicon.png');
    }
}

if (! function_exists('convertColumns')) {
    function convertColumns($columns): array
    {
        return collect($columns)
            ->mapWithKeys(function ($value, $key) {
                $humanReadableKey = ucfirst(str_replace('_', ' ', $key));
                return [$humanReadableKey => $value];
            })
            ->toArray();
    }
}

if (! function_exists('recordAttendance')) {
    function recordAttendance(Staff|Student $attendable, $date, Term $term, bool $present = true): void
    {
        $date = Carbon::parse($date);
        $attendable->attendances()->updateOrCreate(
            [
                'term_id' => $term->id,
                'date' => $date->format('Y-m-d'),
            ],
            [
                'school_id' => $attendable->school->id,
                'present' => $present,
            ]
        );
    }
}

if (!function_exists('modelPath'))
{
    function modelPath($object): string
    {
        return "App\\Models\\$object";
    }
}
