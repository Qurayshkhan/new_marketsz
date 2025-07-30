<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Customer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (empty($user)) {
            return redirect()->route('login');
        }
        if ($user->type != User::USER_TYPE_CUSTOMER) {
            return redirect()->route('login');
        }
        return $next($request);
    }
}
