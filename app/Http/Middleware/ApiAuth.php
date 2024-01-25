<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // if authorize header not exist, return to main
        if (!$request->hasHeader('Authorization')) {
            return response()->json([
                "status" => 401,
                "data" => [],
                "message" => "Please login first"
            ]);
        }

        try {
            $key = env('JWT_SECRETS');
            $decode = JWT::decode($request->bearerToken(), new Key($key, 'HS256'));
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 401,
                "data" => [],
                "message" => "Token invalid, please re-login"
            ]);
        }

        return $next($request);
    }
}
