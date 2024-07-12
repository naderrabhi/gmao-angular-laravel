<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory)  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken(); // Extract token from bearer header

        if (!$token || !auth()->guard('api')->check()) { // Use appropriate guard
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}