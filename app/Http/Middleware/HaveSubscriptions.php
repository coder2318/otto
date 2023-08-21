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
        if ($subscribed === $request->user()->subscribed()) {
            return $next($request);
        }

        return redirect()->intended(route($subscribed ? 'demo.index' : 'stories.index'));
    }
}
