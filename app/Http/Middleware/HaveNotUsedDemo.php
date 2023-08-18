<?php

namespace App\Http\Middleware;

use App\Data\Story\Status;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HaveNotUsedDemo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$status): Response
    {
        if ($request->user()->stories()->whereIn('status', $status)->exists()) {
            return redirect()->route(in_array('published', $status) ? 'demo.finish' : 'demo.record');
        }

        return $next($request);
    }
}
