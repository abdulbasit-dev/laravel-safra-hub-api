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
        Response::macro('validation', function ($status_code, $errors) {
            return response()->json(
                  [
                      'status' => $status_code,
                      'message' => $errors,
                  ]
                , $status_code);
        });

        Response::macro('success', function ($status_code, $message, $data = null) {
            $response = [
                'status' => $status_code,
                'message' => $message
            ];
            //add $reason to response if it's not null
            $data ? $response['data'] = $data : null;

            return response()->json(
                $response
                ,
                $status_code
            );
        });

        Response::macro('error', function ($status_code, $message, $reason = null) {
            $response = [
                'status' => $status_code,
                'message' => $message
            ];
            //add $reason to response if it's not null
            $reason ? $response['reason'] = $reason : null;

            return response()->json(
                $response
                ,
                $status_code
            );
        });

        Response::macro('created', function ($status_code, $message, $data) {
            return response()->json(
                  [
                      'status' => $status_code,
                      'message' => $message,
                      'data' => $data
                  ]
                , $status_code);
        });

        Response::macro('data', function ($status_code, $message, $data) {
            return response()->json(
                  [
                      'status' => $status_code,
                      'message' => $message,
                      'total' => count($data),
                      'data' => $data
                  ]
                , $status_code);
        });
    }
}
