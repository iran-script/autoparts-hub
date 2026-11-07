<?php

namespace App\Domain\Tenancy\Policies;

use App\Domain\Tenancy\Models\Store;
use App\Models\User;

class StorePolicy
{
    public function view(User $user, Store $store): bool { return $user->hasRole('Super Admin') || $user->store_id === $store->id; }
    public function update(User $user, Store $store): bool { return $user->hasRole('Super Admin') || ($user->store_id === $store->id && $user->can('users.manage')); }
}
