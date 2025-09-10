<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $staff = staff(['school']);

        if ($staff->school->subscription_is_past) {
            if ($staff->hasPermissionTo('create.Subscription')) {
                return redirect(route('subscription.index'));
            }else{
                abort(402, 'Your school subscription has expired, Please contact your school administrator.');
            }
        }
        return $next($request);
    }
}
