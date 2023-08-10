<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as BaseAuthenticate;

class Authenticate extends BaseAuthenticate
{

    public function handle($request, Closure $next, ...$guards)
    {
        try {
            $this->authenticate($request, $guards);
        } catch (AuthenticationException $exception) {
            return response()->json([
                "message" => $exception->getMessage()
            ], 401);
        }
        return $next($request);
    }
}

