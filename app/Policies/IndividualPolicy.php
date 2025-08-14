<?php

namespace App\Policies;

use App\Models\Individual;
use App\Models\User;
use App\Models\Role;
use Illuminate\Auth\Access\Response;

class IndividualPolicy
{
    /**
     * Determine whether the user can view any models.
     * Only admins can view all individuals.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(Role::APPLICATION_MANAGER) || $user->hasRole(Role::SUPER_ADMIN);
    }

    /**
     * Determine whether the user can view the model.
     * Users can view their own individual record, admins can view any.
     */
    public function view(User $user, Individual $individual): bool
    {
        // Users can view their own profile
        if ($individual->user_guid === $user->guid) {
            return true;
        }

        // Admins can view any profile
        return $user->hasRole(Role::APPLICATION_MANAGER) || $user->hasRole(Role::SUPER_ADMIN);
    }

    /**
     * Determine whether the user can create models.
     * Authenticated users can create their own individual record.
     */
    public function create(User $user): bool
    {
        // Users can only create one individual record
        $existingIndividual = Individual::where('user_guid', $user->guid)->first();
        
        // If they already have an individual record, they cannot create another
        if ($existingIndividual) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can update the model.
     * Users can update their own individual record, admins can update any.
     */
    public function update(User $user, Individual $individual): bool
    {
        // Users can update their own profile
        if ($individual->user_guid === $user->guid) {
            return true;
        }

        // Admins can update any profile
        return $user->hasRole(Role::APPLICATION_MANAGER) || $user->hasRole(Role::SUPER_ADMIN);
    }

    /**
     * Determine whether the user can delete the model.
     * Only admins can delete individual records.
     */
    public function delete(User $user, Individual $individual): bool
    {
        // Only admins can delete individual records
        return $user->hasRole(Role::APPLICATION_MANAGER) || $user->hasRole(Role::SUPER_ADMIN);
    }

    /**
     * Determine whether the user can restore the model.
     * Only admins can restore individual records.
     */
    public function restore(User $user, Individual $individual): bool
    {
        return $user->hasRole(Role::APPLICATION_MANAGER) || $user->hasRole(Role::SUPER_ADMIN);
    }

    /**
     * Determine whether the user can permanently delete the model.
     * Only super-admins can permanently delete individual records.
     */
    public function forceDelete(User $user, Individual $individual): bool
    {
        return $user->hasRole(Role::SUPER_ADMIN);
    }

    /**
     * Determine whether the user can manage verification status.
     * Only admins can change verification status.
     */
    public function manageVerification(User $user, Individual $individual): bool
    {
        return $user->hasRole(Role::APPLICATION_MANAGER) || $user->hasRole(Role::SUPER_ADMIN);
    }

    /**
     * Determine whether the user can manage status (active/inactive/suspended).
     * Only admins can change status.
     */
    public function manageStatus(User $user, Individual $individual): bool
    {
        return $user->hasRole(Role::APPLICATION_MANAGER) || $user->hasRole(Role::SUPER_ADMIN);
    }
}
