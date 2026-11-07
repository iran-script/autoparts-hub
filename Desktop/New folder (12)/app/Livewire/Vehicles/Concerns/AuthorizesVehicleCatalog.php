<?php

namespace App\Livewire\Vehicles\Concerns;

trait AuthorizesVehicleCatalog
{
    protected function authorizeVehicleCatalog(string $permission = 'vehicles.manage'): void
    {
        abort_unless(auth()->check() && auth()->user()->can($permission), 403);
    }
}
