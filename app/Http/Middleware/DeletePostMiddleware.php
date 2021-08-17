<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DeletePostMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next,$perm)
    {
      dd(1);
        return $next($request);
    }
}
