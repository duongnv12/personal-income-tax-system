<?php

namespace App\Policies;

use App\Models\IncomeDeclaration;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class IncomeDeclarationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, IncomeDeclaration $incomeDeclaration): bool
    {
        return $user->id === $incomeDeclaration->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, IncomeDeclaration $incomeDeclaration): bool
    {
        return $user->id === $incomeDeclaration->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, IncomeDeclaration $incomeDeclaration): bool
    {
        return $user->id === $incomeDeclaration->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, IncomeDeclaration $incomeDeclaration): bool
    {
        return $user->id === $incomeDeclaration->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, IncomeDeclaration $incomeDeclaration): bool
    {
        return $user->id === $incomeDeclaration->user_id;
    }
}