<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait CacheStore
{

    public function getStudents($with = [])
    {
        $key = school()->id;
        return Cache::rememberForever("students:$key", function () use ($with) {
            return school()->students()->with($with)->get();
        });

    }

    public function getClasses()
    {
        $key = school()->id;
        return Cache::rememberForever("classes:$key", function () {
            return school()->classes()->get();
        });

    }
    public function getTerms()
    {
        $key = school()->id;
        return Cache::rememberForever("terms:$key", function () {
            return school()->terms()->get();
        });
    }

    public function getParents()
    {

        $key = school()->id;
        return Cache::rememberForever("parents:$key", function () {
            return school()->parents()->get();
        });
    }

    public function getFees()
    {
        $key = school()->id;
        return Cache::rememberForever("fees:$key", function () {
            return school()->fees()->get();
        });
    }

    public function getSubjects()
    {
        $key = school()->id;
        return Cache::rememberForever("subjects:$key", function () {
            return school()->subjects()->get();
        });
    }

    public function refreshFeesCache(int $school_id): void
    {
        Cache::forget("fees:$school_id");
        $feeVersion = "fees_version:$school_id";
        $feeDebtVersion = "fees_debt:$school_id";
        Cache::increment($feeVersion);
        Cache::increment($feeDebtVersion);
    }



}
