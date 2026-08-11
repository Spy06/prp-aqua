<?php

namespace App\Policies;

use App\Models\BosqTemuan;
use App\Models\User;

class BosqTemuanPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, BosqTemuan $bosqTemuan): bool
    {
        if ($user->role === 'superadmin') {
            return false;
        }

        return $user->role === 'qa' || 
               $user->isBosqPicUser() ||
               $user->id === $bosqTemuan->pelapor_id || 
               $user->id === $bosqTemuan->auditee_id;
    }
}
