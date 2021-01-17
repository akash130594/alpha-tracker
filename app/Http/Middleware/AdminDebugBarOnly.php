<?php

namespace App\Http\Middleware;

use Closure;

class AdminDebugBarOnly
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
        if (!auth()->check() || !auth()->user()->can('view backend') ) {
            \Debugbar::disable();
        }
        return $next($request);
    }
}
