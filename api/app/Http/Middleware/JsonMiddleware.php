<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class JsonMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->isJson() && !$request->wantsJson()) {
            return response()->json([
                'error' => 'Content-Type must be application/json'
            ], 415);
        }

        return $next($request);
    }
} 