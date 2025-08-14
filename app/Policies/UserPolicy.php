<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can manage admin users.
     */
    public function manageUsers(User $user): bool
    {
        return $user->hasAnyRole([
            'Super Admin'
        ]);
    }

    /**
     * Determine whether the user can view admin users.
     */
    public function viewUsers(User $user): bool
    {
        return $user->hasAnyRole([
            'Super Admin',
            'Application Manager',
            'Security Officer',
            'Privacy Officer'
        ]);
    }
}
