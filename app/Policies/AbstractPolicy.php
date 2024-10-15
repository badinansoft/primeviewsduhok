<?php

namespace App\Policies;

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AbstractPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->role === UserRoles::ADMIN || $user->role === UserRoles::ADMIN_VIEWER;
    }

    public function view(User $user, mixed $model): bool
    {
        return $user->role === UserRoles::ADMIN || $user->role === UserRoles::ADMIN_VIEWER;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRoles::ADMIN;
    }

    public function update(User $user, mixed $model): bool
    {
        return $user->role === UserRoles::ADMIN;
    }

    public function delete(User $user, mixed $model): bool
    {
        return $user->role === UserRoles::ADMIN;
    }
}
