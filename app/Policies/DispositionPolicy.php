<?php

namespace App\Policies;

use App\Models\User;

class DispositionPolicy
{
    public function send(User $user, User $target)
    {
        return $target->role_level > $user->role_level;
    }
}