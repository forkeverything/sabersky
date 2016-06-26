<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class BillingSubscription
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
        // Billing status
        $user = Auth::user();
        if (! $user->company->subscribed('main')) {

            if($request->ajax()) return response("402", "Unauthorized due to inactive payment subscription.");

            if ($user->can('settings_change')) {
                flash()->info('Please set payment subscription');
                return redirect('/settings/billing');
            }
            abort(402, "Company subscription is inactive");
        }
        return $next($request);
    }
}
