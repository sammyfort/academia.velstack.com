<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;
class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
          $Helpers = glob(app_path('Helpers').'/*.php');
        foreach ($Helpers as $key => $helper){
            require_once $helper;
        }
        
        Response::macro('success', function (array $data = [], string $message = 'Success', int $code = 200) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $data,
            ], $code);
        });

        Response::macro('error', function (string $message = 'An error occurred', array $errors = [], int $code = 400) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'errors' => $errors,
            ], $code);
        });
    }


}
