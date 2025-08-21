<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can manage admin users.
     */
    public function manageUsers(User $user): bool
    {
        return $user->hasAnyRole([Role::SUPER_ADMIN, Role::ADMIN_MANAGER, Role::SECURITY_OFFICER]);
    }

    /**
     * Determine whether the user can view admin users.
     */
    public function viewUsers(User $user): bool
    {
        return $user->hasAnyRole([
            Role::SUPER_ADMIN,
            Role::ADMIN_MANAGER,
            Role::APPLICATION_MANAGER,
            Role::SECURITY_OFFICER,
            Role::PRIVACY_OFFICER
        ]);
    }
}
