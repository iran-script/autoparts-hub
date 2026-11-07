<?php

namespace App\Livewire\Vehicles;

use App\Domain\Vehicles\Models\VehicleMake;
use App\Domain\Vehicles\Models\VehicleModel;
use App\Livewire\Vehicles\Concerns\AuthorizesVehicleCatalog;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class VehicleModelIndex extends Component
{
    use AuthorizesVehicleCatalog, WithPagination;
    public string $search = ''; public ?int $makeId = null; public string $name = ''; public string $slug = ''; public ?int $editingId = null; public bool $isActive = true;
    public function mount(): void { $this->authorizeVehicleCatalog('vehicles.view'); }
    public function save(): void
    {
        $this->authorizeVehicleCatalog();
        $data = $this->validate(['makeId' => ['required', 'exists:vehicle_makes,id'], 'name' => ['required', 'string', 'max:120'], 'slug' => ['required', 'alpha_dash', 'max:120'], 'isActive' => ['boolean']]);
        $query = VehicleModel::where('vehicle_make_id', $data['makeId'])->where('slug', $data['slug']); if ($this->editingId) $query->where('id', '<>', $this->editingId); abort_if($query->exists(), 422, 'این مدل برای سازنده انتخاب‌شده تکراری است.');
        VehicleModel::updateOrCreate(['id' => $this->editingId], ['vehicle_make_id' => $data['makeId'], 'name' => $data['name'], 'slug' => $data['slug'], 'is_active' => $data['isActive']]); $this->resetForm(); session()->flash('success', 'مدل خودرو ذخیره شد.');
    }
    public function edit(int $id): void { $item = VehicleModel::findOrFail($id); $this->editingId = $id; $this->makeId = $item->vehicle_make_id; $this->name = $item->name; $this->slug = $item->slug; $this->isActive = $item->is_active; }
    public function toggle(int $id): void { $this->authorizeVehicleCatalog(); $item = VehicleModel::findOrFail($id); $item->update(['is_active' => ! $item->is_active]); }
    public function resetForm(): void { $this->reset(['makeId', 'name', 'slug', 'editingId']); $this->isActive = true; }
    public function render() { return view('livewire.vehicles.model-index', ['makes' => VehicleMake::where('is_active', true)->orderBy('name')->get(), 'items' => VehicleModel::with('make')->when($this->search, fn ($q) => $q->where('name', 'ilike', '%'.$this->search.'%'))->orderBy('name')->paginate(15)]); }
}
