<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  ...$roles
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['status' => 0, 'message' => __('messages.login_first')], 401);
        }

        $hasRole = false;
        foreach ($roles as $role) {
            if ($user->role && $user->role->slug === $role) {
                $hasRole = true;
                break;
            }
        }

        if (!$hasRole) {
            return response()->json(['status' => 0, 'message' => __('messages.unauthorized')], 403);
        }

        return $next($request);
    }
}
