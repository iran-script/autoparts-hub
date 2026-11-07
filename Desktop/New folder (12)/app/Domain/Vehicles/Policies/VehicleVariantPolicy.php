<?php

namespace App\Domain\Vehicles\Policies;

use App\Domain\Vehicles\Models\VehicleVariant;
use App\Models\User;

class VehicleVariantPolicy
{
    public function viewAny(User $user): bool { return $user->can('vehicles.view'); }
    public function view(User $user, VehicleVariant $variant): bool { return $user->can('vehicles.view'); }
    public function create(User $user): bool { return $user->can('vehicles.manage'); }
    public function update(User $user, VehicleVariant $variant): bool { return $user->can('vehicles.manage'); }
    public function delete(User $user, VehicleVariant $variant): bool { return $user->can('vehicles.manage'); }
}
