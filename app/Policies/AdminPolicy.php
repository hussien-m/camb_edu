<?php

namespace App\Policies;

use App\Models\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any models.
     */
    public function viewAny(Admin $admin): bool
    {
        return true; // All admins can view
    }

    /**
     * Determine if the user can create models.
     */
    public function create(Admin $admin): bool
    {
        return true; // All admins can create
    }

    /**
     * Determine if the user can update the model.
     */
    public function update(Admin $admin, $model): bool
    {
        return true; // All admins can update
    }

    /**
     * Determine if the user can delete the model.
     */
    public function delete(Admin $admin, $model): bool
    {
        return true; // All admins can delete
    }
}

