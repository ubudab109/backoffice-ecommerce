<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PermissionCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        if (session('session_id.type') == '1') {
            if (canAccess($permission)) {
                return $next($request);
            } else {
                abort(401);
            }
        }else{
            return $next($request);
        }
    }
}
