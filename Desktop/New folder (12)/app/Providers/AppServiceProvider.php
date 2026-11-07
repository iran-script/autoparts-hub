<?php

namespace App\Providers;

use App\Domain\Vehicles\Models\VehicleMake;
use App\Domain\Vehicles\Models\VehicleVariant;
use App\Domain\Vehicles\Policies\VehicleMakePolicy;
use App\Domain\Vehicles\Policies\VehicleVariantPolicy;
use App\Domain\Tenancy\Models\Organization;
use App\Domain\Tenancy\Models\Store;
use App\Domain\Tenancy\Policies\OrganizationPolicy;
use App\Domain\Tenancy\Policies\StorePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(VehicleMake::class, VehicleMakePolicy::class);
        Gate::policy(VehicleVariant::class, VehicleVariantPolicy::class);
        Gate::policy(Organization::class, OrganizationPolicy::class);
        Gate::policy(Store::class, StorePolicy::class);
    }
}
