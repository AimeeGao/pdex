<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SuperAdmin
{
    /**
     * Handle an incoming request to check if user has super admin access.
     * 
     * Allows access only for users with super admin role:
     * - Super Admin (highest level access)
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('intended', $request->url());
        }

        $user = Auth::user();

        if (!$user->hasRole(\App\Models\Role::SUPER_ADMIN)) {
            abort(403, 'Access denied. Super Admin privileges required.');
        }

        return $next($request);
    }
}
