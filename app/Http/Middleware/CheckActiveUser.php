<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckActiveUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        
  if (!(Auth::user()->status ==="active")) {
            return response()->json([
                'message' => 'your account is inactive . you cant do this action !',
                'data' => false
            ], 401);
        }
        return $next($request);    }
}
