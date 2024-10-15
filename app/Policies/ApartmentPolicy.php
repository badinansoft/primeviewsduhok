<?php

namespace App\Policies;

use App\Enums\UserRoles;
use App\Models\User;

class ApartmentPolicy extends AbstractPolicy
{

    public function viewAny(User $user): bool
    {
        if($user->role === UserRoles::NORMAL_USER){
            return true;
        }

        return parent::viewAny($user);
    }

    public function view(User $user, mixed $model): bool
    {
        if($user->role === UserRoles::NORMAL_USER){
            return true;
        }

        return parent::view($user, $model);
    }
}

