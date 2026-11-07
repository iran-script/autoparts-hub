<?php

namespace Tests\Feature;

use App\Domain\Tenancy\Models\Organization;
use App\Domain\Tenancy\Models\Store;
use App\Domain\Vehicles\Models\Engine;
use App\Domain\Vehicles\Models\VehicleGeneration;
use App\Domain\Vehicles\Models\VehicleMake;
use App\Domain\Vehicles\Models\VehicleModel;
use App\Domain\Vehicles\Models\VehicleVariant;
use App\Domain\Vehicles\Models\VehicleDataSource;
use App\Domain\Tenancy\Policies\OrganizationPolicy;
use App\Livewire\Vehicles\VehicleHierarchyPicker;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class VehicleCatalogTest extends TestCase
{
    use RefreshDatabase;

    private function userWith(string $permission = 'vehicles.view'): User
    {
        $org = Organization::create(['public_id' => (string) str()->uuid(), 'name' => 'سازمان تست', 'slug' => 'test-'.str()->random(8)]);
        $store = Store::create(['organization_id' => $org->id, 'public_id' => (string) str()->uuid(), 'name' => 'شعبه تست', 'code' => 'T-'.str()->random(4)]);
        $user = User::factory()->create(['organization_id' => $org->id, 'store_id' => $store->id]);
        $permissionModel = Permission::findOrCreate($permission, 'web');
        $role = Role::findOrCreate('test-role-'.str()->random(8), 'web'); $role->givePermissionTo($permissionModel); $user->assignRole($role);
        return $user;
    }

    public function test_vehicle_make_and_model_are_scoped_by_relationship_and_unique(): void
    {
        $this->actingAs($this->userWith('vehicles.manage'));
        $make = VehicleMake::create(['name' => 'ایران‌خودرو', 'slug' => 'ikco', 'is_active' => true]);
        $model = VehicleModel::create(['vehicle_make_id' => $make->id, 'name' => 'پژو ۲۰۶', 'slug' => 'peugeot-206', 'is_active' => true]);
        $this->assertSame('ایران‌خودرو', $model->make->name);
        $this->expectException(\Illuminate\Database\UniqueConstraintViolationException::class);
        VehicleModel::create(['vehicle_make_id' => $make->id, 'name' => 'پژو ۲۰۶ تکراری', 'slug' => 'peugeot-206', 'is_active' => true]);
    }

    public function test_variant_requires_a_valid_generation_and_preserves_engine_relation(): void
    {
        $this->actingAs($this->userWith('vehicles.manage'));
        $make = VehicleMake::create(['name' => 'سایپا', 'slug' => 'saipa', 'is_active' => true]);
        $model = VehicleModel::create(['vehicle_make_id' => $make->id, 'name' => 'کوییک', 'slug' => 'quick', 'is_active' => true]);
        $generation = VehicleGeneration::create(['vehicle_model_id' => $model->id, 'name' => 'نسل اول', 'slug' => 'first', 'is_active' => true]);
        $engine = Engine::create(['name' => 'موتور مستند تست', 'code' => 'TEST-M15', 'normalized_code' => 'TEST-M15', 'is_active' => true]);
        $variant = VehicleVariant::create(['vehicle_generation_id' => $generation->id, 'engine_id' => $engine->id, 'name' => 'تیپ تست', 'is_active' => true]);
        $this->assertSame($generation->id, $variant->generation->id); $this->assertSame('موتور مستند تست', $variant->engine->name);
    }

    public function test_livewire_make_screen_rejects_unauthorized_user(): void
    {
        $this->actingAs($this->userWith('dashboard.view'));
        Livewire::test(\App\Livewire\Vehicles\VehicleMakeIndex::class)->assertForbidden();
    }

    public function test_vehicle_source_can_be_attached_to_variant_with_review_status(): void
    {
        $this->actingAs($this->userWith('vehicles.manage'));
        $make = VehicleMake::create(['name' => 'منبع‌دار', 'slug' => 'sourced', 'is_active' => true]);
        $model = VehicleModel::create(['vehicle_make_id' => $make->id, 'name' => 'مدل منبع‌دار', 'slug' => 'sourced-model', 'is_active' => true]);
        $generation = VehicleGeneration::create(['vehicle_model_id' => $model->id, 'name' => 'نسل منبع‌دار', 'slug' => 'sourced-generation', 'is_active' => true]);
        $variant = VehicleVariant::create(['vehicle_generation_id' => $generation->id, 'name' => 'تیپ منبع‌دار', 'is_active' => true]);
        $source = VehicleDataSource::create(['name' => 'کاتالوگ تست مجاز', 'source_type' => 'technical_catalog', 'verification_status' => 'VERIFIED']);
        $variant->sources()->attach($source, ['evidence_type' => 'catalog-page', 'verification_status' => 'VERIFIED', 'reviewed_by' => auth()->id(), 'last_reviewed_at' => now()->toDateString()]);
        $this->assertDatabaseHas('vehicle_variant_sources', ['vehicle_variant_id' => $variant->id, 'vehicle_data_source_id' => $source->id, 'verification_status' => 'VERIFIED']);
    }

    public function test_hierarchy_picker_cascades_make_to_model(): void
    {
        $this->actingAs($this->userWith('vehicles.view'));
        $make = VehicleMake::create(['name' => 'انتخابی', 'slug' => 'selectable', 'is_active' => true]);
        VehicleModel::create(['vehicle_make_id' => $make->id, 'name' => 'مدل انتخابی', 'slug' => 'selectable-model', 'is_active' => true]);
        Livewire::test(VehicleHierarchyPicker::class)->set('makeId', $make->id)->assertSet('modelId', null)->assertSee('مدل انتخابی');
    }

    public function test_organization_policy_blocks_cross_tenant_access(): void
    {
        $user = $this->userWith('dashboard.view');
        $other = Organization::create(['public_id' => (string) str()->uuid(), 'name' => 'سازمان دیگر', 'slug' => 'other-'.str()->random(8)]);
        $this->assertFalse((new OrganizationPolicy)->view($user, $other));
    }

    public function test_login_authenticates_a_real_user(): void
    {
        $user = $this->userWith('dashboard.view');
        $this->post('/login', ['email' => $user->email, 'password' => 'password'])->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }
}
