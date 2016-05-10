<?php

namespace App\Http\Middleware;

use Closure;

class APIOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /*
         * TODO ::: Make sure API requests are valid. Right now we're just making sure
         * they are xhr requests. Maybe we'll need to do some token verification
         * here.
         */
        
        if(! $request->ajax()) return response("Wrong way, go back!", 500);
        return $next($request);
    }
}
