<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CanManageInstitutions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Authentication required'], 401);
            }
            return redirect()->route('login');
        }

        $userRoles = $user->roles->pluck('name')->toArray();
        $canManage = array_intersect($userRoles, Role::getInstitutionManagerRoles());
        
        if (empty($canManage)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Insufficient permissions to manage institutions',
                    'required_roles' => Role::getInstitutionManagerRoles()
                ], 403);
            }
            
            abort(403, 'Access denied. Institution management requires Super Admin or Admin Manager role.');
        }

        return $next($request);
    }
}
