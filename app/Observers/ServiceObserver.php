<?php

namespace App\Observers;

use App\Models\Service;
use App\Services\GhanaPostService;

class ServiceObserver
{
    public function creating(Service $service): void
    {
        // get lat and lon from gps(Ghana post)
//        if ($service->gps && app()->environment() == 'production') {
//            $location = GhanaPostService::getLocationByGPS($service->gps);
//            $service->gps_lat = $location->centerLongitude;
//            $service->gps_lon = $location->centerLatitude;
//        }
    }

    public function created(Service $service): void
    {
        //
    }

    public function updated(Service $service): void
    {
//        if ($service->isDirty('gps') && $service->gps && app()->environment() == 'production') {
//            $location = GhanaPostService::getLocationByGPS($service->gps);
//            $service->gps_lat = $location->centerLongitude;
//            $service->gps_lon = $location->centerLatitude;
//            $service->save();
//        }
    }

    public function deleted(Service $service): void
    {
        //
    }

    public function restored(Service $service): void
    {
        //
    }

    public function forceDeleted(Service $service): void
    {
        //
    }
}
