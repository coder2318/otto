<?php

namespace App\Http\Middleware;

use App\Data\User\Details;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserHaveAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $redirect, bool $inverse = false): Response
    {
        $configured = $request->user()?->details?->configured;
        $plan = $request->user()->can('free-access') || true; // TODO: check if user subscribed

        if (($configured && $plan) xor $inverse) {
            return $next($request);
        }

        return redirect()->route($redirect);
    }
}
