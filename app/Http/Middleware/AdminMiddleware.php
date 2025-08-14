<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Role;

class AdminMiddleware
{
    /**
     * Handle an incoming request for admin module access.
     * 
     * This middleware ensures that users accessing admin routes:
     * 1. Are authenticated 
     * 2. Have appropriate admin roles
     * 3. Are redirected to admin-specific login if not authenticated
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            // Store intended URL for after admin login
            session(['admin_intended_url' => $request->url()]);
            
            return redirect()->route('admin.login')
                ->with('message', 'Please sign in to access the administrative area.');
        }

        $user = Auth::user();

        // Check if user account is active
        if (!$user->is_active) {
            // Log out the user and redirect to login
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('admin.login')
                ->withErrors(['error' => 'Your account is inactive. Please contact your administrator to activate your account.']);
        }

        // Admin access roles - specific admin hierarchy only
        $adminRoles = [
            Role::SUPER_ADMIN,
            Role::APPLICATION_MANAGER,
            Role::ADMIN_MANAGER,
            Role::SECURITY_OFFICER,
            Role::PRIVACY_OFFICER,
            Role::ADMIN_GUEST
        ];

        if (!$user->hasAnyRole($adminRoles)) {
            // User is authenticated but doesn't have admin privileges
            // Log them out and redirect to login with error message
            Auth::logout();
            
            // Invalidate the session
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('login')
                ->withErrors(['error' => 'Access denied. You do not have administrative privileges to access this area.']);
        }

        return $next($request);
    }
}
