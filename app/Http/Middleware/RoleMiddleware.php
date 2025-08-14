<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  One or more role names required for access
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('intended', $request->url());
        }

        $user = Auth::user();

        // Convert variadic parameters to array and handle comma-separated values
        $rolesToCheck = [];
        foreach ($roles as $role) {
            // Handle comma-separated roles in a single parameter
            if (str_contains($role, ',')) {
                $rolesToCheck = array_merge($rolesToCheck, array_map('trim', explode(',', $role)));
            } else {
                $rolesToCheck[] = trim($role);
            }
        }

        // Check if user has any of the required roles
        if (!$user->hasAnyRole($rolesToCheck)) {
            abort(403, 'Access denied. Insufficient permissions.');
        }

        return $next($request);
    }
}
