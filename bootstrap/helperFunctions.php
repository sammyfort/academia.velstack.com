<?php

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

if (!function_exists("successRes")) {
    function successRes(string $message, array $data = []): array{
        return [
            'success' => true,
            'message' => $message,
            'data' => $data
        ];
    }
}

if (!function_exists("errorRes")) {
    function errorRes(string $message="Something went wrong"): array{
        return [
            'success' => false,
            'message' => $message
        ];
    }
}

if (!function_exists('dateFormat')){
    function dateFormat($time = false): string
    {
        if ($time) return 'D, jS M Y h:i A';
        return 'D, jS M Y';
    }
}

if (!function_exists('uploadToGallery')){
    function uploadToGallery(Model $model, $image, $collection): Media
    {
        if (!in_array(InteractsWithMedia::class, class_uses_recursive($model))){
            throw new Exception("this model type is not a mediable class");
        }
        return $model->addMedia($image)
            ->toMediaCollection($collection);
    }
}

if (!function_exists('ratingFormat')){
    function ratingFormat(float|int|null $value, int $decimals=1): float
    {
        if (is_null($value)) {
            return 0;
        }
        $tarCeil = ceil($value * 100) / 100;
        return (float) number_format($tarCeil, $decimals);
    }
}

if (!function_exists('arrayAndSubToObject')){
    function arrayAndSubToObject($array): mixed
    {
        if (!is_array($array)) {
            return $array;
        }
        return (object) array_map(__FUNCTION__, $array);
    }
}

if (!function_exists('cediSign')){
    function cediSign(): string
    {
        return "₵";
    }
}

if (! function_exists('toLabelValue')) {
    function toOption(iterable $values, string $labelKey = null, string $valueKey = null, $format = true): array
    {
        return collect($values)->map(function ($item) use ($labelKey, $valueKey, $format) {
            if ($labelKey && $valueKey) {
                return [
                    'label' => $format ? str(data_get($item, $labelKey))->headline() : data_get($item, $labelKey),
                    'value' => data_get($item, $valueKey),
                ];
            }
            return [
                'label' => $format ? str($item)->headline() : $item,
                'value' => $item,
            ];
        })->values()->all();
    }
}

