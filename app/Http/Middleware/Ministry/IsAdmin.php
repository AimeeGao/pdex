<?php

namespace App\Http\Middleware\Ministry;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Role;
class IsAdmin
{
    /**
     * Handle an incoming request to check if user has ministry admin access.
     * 
     * Allows access for users with ministry admin roles:
     * - Ministry_Admin (ministry admin)
     * - Ministry_Admin (ministry admin)
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

        // Ministry admin roles - higher level access within ministry
        $ministryAdminRoles = [
            Role::MINISTRY_ADMIN,
            Role::MINISTRY_USER,
            // Admin roles that can access ministry admin functions
            Role::SUPER_ADMIN,
            Role::ADMIN_MANAGER,
            Role::APPLICATION_MANAGER,
        ];

        if (!$user->hasAnyRole($ministryAdminRoles)) {
            return redirect()->route('admin.login')
                ->withErrors(['error' => 'Access denied. Administrative privileges required.']);
        }

        return $next($request);
    }
}
