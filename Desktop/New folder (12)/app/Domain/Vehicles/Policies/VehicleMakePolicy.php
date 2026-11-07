<?php

namespace App\Domain\Vehicles\Policies;

use App\Domain\Vehicles\Models\VehicleMake;
use App\Models\User;

class VehicleMakePolicy
{
    public function viewAny(User $user): bool { return $user->can('vehicles.view'); }
    public function view(User $user, VehicleMake $make): bool { return $user->can('vehicles.view'); }
    public function create(User $user): bool { return $user->can('vehicles.manage'); }
    public function update(User $user, VehicleMake $make): bool { return $user->can('vehicles.manage'); }
    public function delete(User $user, VehicleMake $make): bool { return $user->can('vehicles.manage'); }
}
