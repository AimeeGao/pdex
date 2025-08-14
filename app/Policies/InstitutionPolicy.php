<?php

namespace App\Policies;

use App\Models\Institution;
use App\Models\User;
use App\Models\Role;

class InstitutionPolicy
{
    /**
     * Determine whether the user can view any institutions.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated admin users can view institutions
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the institution.
     */
    public function view(User $user, Institution $institution): bool
    {
        // All authenticated admin users can view individual institutions
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can create institutions.
     */
    public function create(User $user): bool
    {
        // Only Super Admin and Admin Manager can create institutions
        return $user->canManageInstitutions();
    }

    /**
     * Determine whether the user can update the institution.
     */
    public function update(User $user, Institution $institution): bool
    {
        // Only Super Admin and Admin Manager can update institutions
        return $user->canManageInstitutions();
    }

    /**
     * Determine whether the user can delete the institution.
     */
    public function delete(User $user, Institution $institution): bool
    {
        // Only Super Admin and Admin Manager can delete institutions
        return $user->canManageInstitutions();
    }

    /**
     * Determine whether the user can restore the institution.
     */
    public function restore(User $user, Institution $institution): bool
    {
        // Only Super Admin and Admin Manager can restore institutions
        return $user->canManageInstitutions();
    }

    /**
     * Determine whether the user can permanently delete the institution.
     */
    public function forceDelete(User $user, Institution $institution): bool
    {
        // Only Super Admin can permanently delete institutions
        return $user->hasRole(Role::SUPER_ADMIN);
    }

    /**
     * Determine whether the user can view institution statistics.
     */
    public function viewStats(User $user): bool
    {
        // All authenticated admin users can view stats
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can search institutions.
     */
    public function search(User $user): bool
    {
        // All authenticated admin users can search
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can access institution portal.
     */
    public function accessPortal(User $user): bool
    {
        // Users with Institution roles can access the portal
        return $user->hasAnyRole([Role::INSTITUTION_ADMIN, Role::INSTITUTION_USER]);
    }

    /**
     * Determine whether the user can access institution admin features.
     */
    public function accessAdmin(User $user): bool
    {
        // Only Institution Admin can access admin features
        return $user->hasRole(Role::INSTITUTION_ADMIN);
    }

    /**
     * Determine whether the user can view their own institution's settings.
     */
    public function viewOwnSettings(User $user): bool
    {
        // Institution Admin users can view their own institution's settings
        return $user->hasRole(Role::INSTITUTION_ADMIN) && $user->institution() !== null;
    }

    /**
     * Determine whether the user can update their own institution's settings.
     */
    public function updateOwn(User $user, Institution $institution): bool
    {
        // Institution Admin users can only update their own institution
        if (!$user->hasRole(Role::INSTITUTION_ADMIN)) {
            return false;
        }

        $userInstitution = $user->institution();
        return $userInstitution && $userInstitution->id === $institution->id;
    }
}
