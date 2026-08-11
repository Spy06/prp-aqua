<?php

namespace App\Policies;

use App\Models\User;

class TemuanPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, \App\Models\Temuan $temuan): bool
    {
        if ($user->role === 'superadmin') {
            return false;
        }

        return $user->role === 'qa' || 
               $user->isPicUser() ||
               $user->id === $temuan->pelapor_id || 
               $user->id === $temuan->pic_id;
    }
}
