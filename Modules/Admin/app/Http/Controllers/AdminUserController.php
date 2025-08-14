<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AdminUserController extends Controller
{
    /**
     * Display a listing of admin users.
     */
    public function index(): Response
    {
        // Get all admin roles
        $adminRoles = [Role::SUPER_ADMIN, Role::APPLICATION_MANAGER, Role::SECURITY_OFFICER, Role::PRIVACY_OFFICER, Role::ADMIN_GUEST];

        // Get users who have admin roles and are IDIR users, excluding ministry users
        $adminUsers = User::whereHas('roles', function($query) use ($adminRoles) {
                $query->whereIn('name', $adminRoles);
            })
            ->where('identity_provider', 'idir')
            ->whereDoesntHave('roles', function($query) {
                $query->whereIn('name', [Role::MINISTRY_ADMIN, Role::MINISTRY_USER]);
            })
            ->with(['roles:id,name,display_name'])
            ->withTrashed()
            ->orderBy('name')
            ->get();

        // Get available admin roles for assignment
        $availableRoles = Role::whereIn('name', $adminRoles)
            ->get(['id', 'name', 'display_name']);

        $userCanManage = Auth::user()->hasAnyRole([Role::SUPER_ADMIN]);

        return Inertia::render('Admin::Users', [
            'users' => $adminUsers,
            'availableRoles' => $availableRoles,
            'userCanManage' => $userCanManage,
        ]);
    }

    /**
     * Update user roles.
     */
    public function updateRoles(Request $request, User $user)
    {
        $this->authorize('manageUsers');

        $data = $request->validate([
            'role_ids' => 'required|array',
            'role_ids.*' => 'exists:roles,id',
        ]);

        // Get the role names to ensure they're admin roles
        $roles = Role::whereIn('id', $data['role_ids'])->get();
        $adminRoles = [Role::SUPER_ADMIN, Role::APPLICATION_MANAGER, Role::SECURITY_OFFICER, Role::PRIVACY_OFFICER, Role::ADMIN_GUEST];

        foreach ($roles as $role) {
            if (!in_array($role->name, $adminRoles)) {
                return back()->withErrors(['role_ids' => 'Invalid role selected. Only admin roles are allowed.']);
            }
        }

        // Sync the roles
        $user->roles()->sync($data['role_ids']);

        return redirect()->route('admin.users.index')
            ->with('success', 'User roles updated successfully.');
    }

    /**
     * Toggle user active status.
     */
    public function toggleStatus(Request $request, User $user)
    {
        $this->authorize('manageUsers');

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';
        
        return redirect()->route('admin.users.index')
            ->with('success', "User has been {$status}.");
    }

    /**
     * Soft delete a user.
     */
    public function destroy(User $user)
    {
        $this->authorize('manageUsers');

        // Prevent self-deletion
        if ($user->id === Auth::id()) {
            return back()->withErrors(['user' => 'You cannot delete your own account.']);
        }

        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User has been deleted. They can be restored if needed.');
    }

    /**
     * Restore a soft deleted user.
     */
    public function restore($id)
    {
        $this->authorize('manageUsers');

        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User has been restored.');
    }

    /**
     * Permanently delete a user.
     */
    public function forceDelete($id)
    {
        $this->authorize('manageUsers');

        $user = User::withTrashed()->findOrFail($id);
        
        // Prevent self-deletion
        if ($user->id === Auth::id()) {
            return back()->withErrors(['user' => 'You cannot permanently delete your own account.']);
        }

        $user->forceDelete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User has been permanently deleted.');
    }
}
