<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        //get language from header
        $local = $request->header('lang')?$request->header('lang'):'en';
        //set laravel local to $local
        app()->setLocale($local);
        //continue request
        return $next($request);
    }
}
