<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountSuspension
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $guards = array_keys(config('auth.guards'));
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                if ($user->suspended) {
                    abort(403, $user->suspension_reason ??
                        'Your account is suspended. Please contact your school administrator.');
                }

                if (method_exists($user, 'school') && $user->school->suspended) {
                    abort(403, $user->school->suspended_reason ?? "Your school is suspended.
                     Please contact " .config('app.name'). " on ". config('app.email'));
                }

                break;
            }
        }

        return $next($request);

    }
}
