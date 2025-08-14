<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PreventCrossIdpAccess
{
    /**
     * Handle an incoming request to prevent cross-IDP access.
     * 
     * This middleware ensures users can only access routes appropriate for their IDP type:
     * - IDIR users: /ministry/* routes
     * - BCSC users: /student/* routes  
     * - BCeID users: /institution/* routes
     * - Admin IDIR users: /admin/* routes (only if they have admin roles)
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $requiredIdpType  The IDP type required for this route (idir, bcsc, bceid, admin)
     */
    public function handle(Request $request, Closure $next, string $requiredIdpType): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Determine user's IDP type based on their GUID fields
        $userIdpType = null;
        if (!empty($user->idir_user_guid)) {
            $userIdpType = 'idir';
        } elseif (!empty($user->bcsc_user_guid)) {
            $userIdpType = 'bcsc';
        } elseif (!empty($user->bceid_user_guid)) {
            $userIdpType = 'bceid';
        }

        // Special handling for admin routes
        if ($requiredIdpType === 'admin') {
            // Must be IDIR user with admin roles
            if ($userIdpType !== 'idir') {
                return redirect()->route('login')
                    ->withErrors(['error' => 'Admin access is only available to government users.']);
            }
            
            $adminRoles = ['Super Admin', 'Application Manager', 'Security Officer', 'Privacy Officer'];
            if (!$user->hasAnyRole($adminRoles)) {
                return redirect()->route('ministry.dashboard')
                    ->withErrors(['error' => 'You do not have administrative privileges.']);
            }
            
            return $next($request);
        }

        // Check if user's IDP type matches required type
        if ($userIdpType !== $requiredIdpType) {
            $redirectRoutes = [
                'idir' => 'ministry.dashboard',
                'bcsc' => 'student.dashboard', 
                'bceid' => 'institution.dashboard',
            ];
            
            $redirectRoute = $redirectRoutes[$userIdpType] ?? 'dashboard';
            
            return redirect()->route($redirectRoute)
                ->withErrors(['error' => 'Access denied. You can only access areas appropriate for your account type.']);
        }

        return $next($request);
    }
}
