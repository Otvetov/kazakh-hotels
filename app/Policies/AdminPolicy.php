<?php

namespace App\Policies;

use App\Models\User;

class AdminPolicy
{
    /**
     * Determine if user is admin
     */
    public function access(User $user): bool
    {
        return $user->isAdmin();
    }
}

