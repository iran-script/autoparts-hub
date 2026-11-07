<?php

namespace Database\Seeders;

use App\Domain\Tenancy\Models\Organization;
use App\Models\User;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $permissions = ['dashboard.view', 'vehicles.view', 'vehicles.manage', 'parts.view', 'parts.manage', 'fitments.review', 'inventory.view', 'inventory.manage', 'sales.create', 'reports.view', 'users.manage'];
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        foreach ($permissions as $permission) Permission::findOrCreate($permission, 'web');
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        $rolePermissions = [
            'Super Admin' => $permissions,
            'Store Owner' => ['dashboard.view', 'vehicles.view', 'vehicles.manage', 'parts.view', 'parts.manage', 'inventory.view', 'inventory.manage', 'sales.create', 'reports.view'],
            'Store Manager' => ['dashboard.view', 'vehicles.view', 'parts.view', 'inventory.view', 'inventory.manage', 'sales.create', 'reports.view'],
            'Inventory Manager' => ['dashboard.view', 'parts.view', 'inventory.view', 'inventory.manage'],
            'Purchasing Agent' => ['dashboard.view', 'parts.view', 'inventory.view'],
            'Salesperson' => ['dashboard.view', 'parts.view', 'sales.create'],
            'Accountant' => ['dashboard.view', 'reports.view'],
            'Catalog Editor' => ['dashboard.view', 'vehicles.view', 'vehicles.manage', 'parts.view', 'parts.manage'],
            'Catalog Reviewer' => ['dashboard.view', 'vehicles.view', 'parts.view', 'fitments.review'],
            'Viewer' => ['dashboard.view', 'vehicles.view', 'parts.view'],
        ];
        foreach ($rolePermissions as $roleName => $rolePermissionNames) {
            $role = Role::findOrCreate($roleName, 'web');
            $role->syncPermissions($rolePermissionNames);
        }
        $organization = Organization::firstOrCreate(['slug' => 'default-store'], ['public_id' => (string) Str::uuid(), 'name' => 'فروشگاه پیش‌فرض']);
        $store = $organization->stores()->firstOrCreate(['code' => 'MAIN'], ['public_id' => (string) Str::uuid(), 'name' => 'فروشگاه اصلی']);
        if ($email = env('AUTOPARTS_ADMIN_EMAIL')) {
            $user = User::firstOrCreate(['email' => $email], ['name' => env('AUTOPARTS_ADMIN_NAME', 'مدیر سیستم'), 'password' => env('AUTOPARTS_ADMIN_PASSWORD', 'change-me')]);
            $user->forceFill(['organization_id' => $organization->id, 'store_id' => $store->id])->save();
            $user->assignRole('Super Admin');
        }
    }
}
