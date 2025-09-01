<?php

namespace App\Observers;


use App\Models\Signboard;
use App\Services\GhanaPostService;

class SignboardObserver
{
    public function creating(Signboard $signboard): void
    {
        // get lat and lon from gps(Ghana post)
//        if ($signboard->gps && app()->environment() == 'production') {
//            $location = GhanaPostService::getLocationByGPS($signboard->gps);
//            $signboard->gps_lat = $location->centerLongitude;
//            $signboard->gps_lon = $location->centerLatitude;
//        }
        $signboard->created_by_id = auth()->id();
    }

    public function created(Signboard $signboard): void
    {
        //
    }

    public function updated(Signboard $signboard): void
    {
//        if ($signboard->isDirty('gps') && $signboard->gps && app()->environment() == 'production') {
//            $location = GhanaPostService::getLocationByGPS($signboard->gps);
//            $signboard->gps_lat = $location->centerLongitude;
//            $signboard->gps_lon = $location->centerLatitude;
//            $signboard->save();
//        }
    }

    public function deleted(Signboard $signboard): void
    {
        //
    }

    public function restored(Signboard $signboard): void
    {
        //
    }

    public function forceDeleted(Signboard $signboard): void
    {
        //
    }
}
