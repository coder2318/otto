<?php

namespace App\Features;

use App\Models\User;

class BetaAccess
{
    /**
     * Resolve the feature's initial value.
     */
    public function resolve(User $user): mixed
    {
        return match (true) {
            $user->hasRole('super-admin') => true,
            default => false,
        };
    }
}
