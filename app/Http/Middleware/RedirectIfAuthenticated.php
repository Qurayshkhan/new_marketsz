<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guard): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                switch ($request->user()->type) {
                    case User::USER_TYPE_ADMIN:
                        return redirect(RouteServiceProvider::ADMIN_HOME);
                    case User::USER_TYPE_CUSTOMER:
                        return redirect(RouteServiceProvider::CUSTOMER_HOME);
                }
            }
        }
        return $next($request);
    }
}
