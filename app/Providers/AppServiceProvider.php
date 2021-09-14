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

        Response::macro('success', function ($status_code, $message, $data = null, $total=false) {
            $response = [
                'success'=>true,
                'status' => $status_code,
                'message' => $message
            ];

            if($data != null){
                $total && $response['total'] = count($data);
                $response['data'] = $data;
            }

            return response()->json(
                $response,
                $status_code
            );
        });

        Response::macro('error', function ($status_code, $errors, $reason = null) {
            $response = [
                'success'=>false,
                'status' => $status_code,
                'message' => $errors
            ];

            $reason && $response['reason'] = $reason;

            return response()->json(
                $response,
                $status_code
            );
        });



    }
}
