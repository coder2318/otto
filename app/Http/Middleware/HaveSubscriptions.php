<?php

namespace App\Http\Middleware;

use App\Models\User;
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
    public function handle(Request $request, Closure $next, bool $subsctibed = true): Response
    {
        /** @var User */
        $user = $request->user();

        if ($subsctibed === $user->subscriptions()->count() > 0) {
            return $next($request);
        }

        return redirect()->route($subsctibed ? 'plans.index' : 'home');
    }
}
