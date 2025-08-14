<?php

namespace App\Http\Middleware\Institution;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Role;

class IsAdmin
{
    /**
     * Handle an incoming request to check if user has institution admin access.
     * 
     * Allows access for users with institution admin roles:
     * - Institution_ADMIN (institution admin)
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

        // Institution admin roles - higher level access within institution
        $institutionAdminRoles = [
            Role::INSTITUTION_ADMIN,
            Role::INSTITUTION_USER
        ];

        if (!$user->hasAnyRole($institutionAdminRoles)) {
            return redirect()->route('institution.dashboard')
                ->withErrors(['error' => 'Access denied. Administrative privileges required.']);
        }

        return $next($request);
    }
}
