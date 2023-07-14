<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Session\Store;
use Illuminate\Http\Request;

class CountView
{
    private $session;

    public function __construct(Store $session)
    {
        $this->session = $session;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $posts = $this->getViewedQa();
        if (!is_null($posts)) {
            $posts = $this->cleanExpiredViews($posts);
            $this->storeQa($posts);
        }

        return $next($request);
    }

    /** 
     * Store qa sesstion
     * 
     * @param mixed $qa
     * @return void
     */ 
    private function storeQa($qa)
    {
        $this->session->put(config('constants.nameSessionViewCount'), $qa);
    }

    /** 
     * Remove exoired qa  timeout
     * 
     * @param mixed $qa, $throttleTime
     * @return array
     */ 
    private function cleanExpiredViews($qa, $throttleTime = 0)
    {
        $time = time();
        return array_filter($qa, function ($timestamp) use ($time, $throttleTime) {
            return ($timestamp + $throttleTime) > $time;
        });
    }

    /** 
     * View qa sesstion
     * 
     * @return void
     */ 
    private function getViewedQa()
    {
        return $this->session->get(config('constants.nameSessionViewCount'), null);
    }
}
