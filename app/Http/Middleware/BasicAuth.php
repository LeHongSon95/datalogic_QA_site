<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BasicAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        header('Cache-Control: no-cache, must-revalidate, max-age=0');
        $hasSuppliedCredentials = empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']);
        $isNotAuthenticated = (
            $hasSuppliedCredentials ||
            $_SERVER['PHP_AUTH_USER'] != env('APP_AUTH_USER', 'admin') ||
            $_SERVER['PHP_AUTH_PW']   != env('APP_AUTH_PASSWORD', '123456@a')
        );
        if ($isNotAuthenticated) {
            
            header('HTTP/1.1 401 Authorization Required');
            header('WWW-Authenticate: Basic realm="Access denied"');
            exit;
        }
        
        return $next($request);
    }
}
