<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('validation',function ($status_code,$errors){
            return response()->json(
                [
                    'status' => $status_code,
                    'message' => $errors,
                ]
            );
        });

        Response::macro('created',function ($status_code,$message,$data){
            return response()->json(
                [
                    'status' => $status_code,
                    'message' => $message,
                    'data' => $data
                ]
            );
        });
    }
}
