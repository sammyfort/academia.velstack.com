<?php

namespace App\Services;

use App\Models\Application;
use App\Models\School;
use App\Models\Staff;
use App\Notifications\ApplicationStatusNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

class SchoolService
{
    public static function decide($status, Application $app): void
    {
         match ($status){
          'accepted' => self::accept($app),
          'under_review', 'rejected' => self::default($app),
          default => NULL,
        };
    }

    protected static function accept(Application $app)
    {
        $school =  School::create([
            'uuid' => rand(11111111, 99999999),
            'name' => $app->sch_name,
            'email' => $app->sch_email,
            'phone' => $app->sch_tel,
            'region' => $app->sch_region,
            'district' => $app->sch_district,
            'town' => $app->sch_town,
            'gps' => $app->sch_gps,
            'prop_name' => $app->prop_name,
            'prop_email' => $app->prop_email,
            'prop_tel' => $app->prop_tel
        ]);



        $staff = $school->staff()->create([
            'email' => $app->sch_email,
            'first_name' => $school->name,
            'middle_name' => $school->name,
            'last_name' => $school->name,
            'staff_id' => "STAFF_" . rand(111111, 999999),
            'password' => Hash::make($app->sch_email),
            'role' => Staff::ADMINSTRATOR,
            'gender' => 'male',
        ]);

        Notification::route('mail', [$school->email =>$school->name])
            ->notify(new ApplicationStatusNotification($app, $app->sch_email));

       // $app->delete();
        return $staff;
    }
    protected static function default(Application $app): void
    {
        Notification::route('mail', [
            $app->sch_email => $app->sch_name
        ])->notify(new ApplicationStatusNotification($app));
    }
}
