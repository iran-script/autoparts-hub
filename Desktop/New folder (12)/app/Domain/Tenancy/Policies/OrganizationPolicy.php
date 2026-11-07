<?php

namespace App\Domain\Tenancy\Policies;

use App\Domain\Tenancy\Models\Organization;
use App\Models\User;

class OrganizationPolicy
{
    public function view(User $user, Organization $organization): bool { return $user->hasRole('Super Admin') || $user->organization_id === $organization->id; }
    public function update(User $user, Organization $organization): bool { return $user->hasRole('Super Admin') || ($user->organization_id === $organization->id && $user->can('users.manage')); }
}
