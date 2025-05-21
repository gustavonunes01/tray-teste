<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class EnsureJwtAuthenticated extends BaseMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return $next($request);
    }
}

