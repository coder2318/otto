<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HaveSubscriptions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, bool $subscribed = true): Response
    {
        if ($subscribed === ($request->user()?->subscribed() || (bool) $request->user()?->plan_id || (bool) $request->user()?->can('free-access'))) {
            return $next($request);
        }

        return redirect()->intended(route($subscribed ? 'dashboard.demo.index' : 'dashboard.dashboard.index'));
    }
}
