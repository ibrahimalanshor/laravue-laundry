<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CheckSanctumAbilities
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$abilities)
    {
        foreach ($abilities as $ability) {
            if (!$request->user()->tokenCan($ability)) {
                throw new AccessDeniedHttpException();
            }
        }

        return $next($request);
    }
}
