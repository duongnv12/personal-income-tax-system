<?php

namespace App\Policies;

use App\Models\Dependent;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DependentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Mọi user đều có thể xem danh sách người phụ thuộc của mình
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Dependent $dependent): bool
    {
        return $user->id === $dependent->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Mọi user có thể tạo người phụ thuộc
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Dependent $dependent): bool
    {
        return $user->id === $dependent->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Dependent $dependent): bool
    {
        return $user->id === $dependent->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Dependent $dependent): bool
    {
        return $user->id === $dependent->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Dependent $dependent): bool
    {
        return $user->id === $dependent->user_id;
    }
}