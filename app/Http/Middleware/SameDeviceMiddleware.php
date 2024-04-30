<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SameDeviceMiddleware
{
    /**
     * Handle an incoming request.
     *
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if ($user && $user->first_login != 1) {
            $deviceIdentifier = $user->device_id;
            $cookieIdentifier = $request->cookie('device_id');

            if ($deviceIdentifier !== $cookieIdentifier) {
                auth()->logout();
                return redirect()->route('login')->with('error', 'You can only login from one device at a time.');
            }
        }
        return $next($request);
    }
}
