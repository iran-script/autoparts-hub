<?php

namespace App\Livewire\Vehicles;

use App\Domain\Vehicles\Models\VehicleMake;
use App\Livewire\Vehicles\Concerns\AuthorizesVehicleCatalog;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class VehicleMakeIndex extends Component
{
    use AuthorizesVehicleCatalog, WithPagination;
    public string $search = ''; public string $name = ''; public string $slug = ''; public string $countryCode = ''; public ?int $editingId = null; public bool $isActive = true;
    public function mount(): void { $this->authorizeVehicleCatalog('vehicles.view'); }
    public function save(): void
    {
        $this->authorizeVehicleCatalog();
        $data = $this->validate(['name' => ['required', 'string', 'max:120'], 'slug' => ['required', 'alpha_dash', 'max:120', Rule::unique('vehicle_makes', 'slug')->ignore($this->editingId)], 'countryCode' => ['nullable', 'size:2'], 'isActive' => ['boolean']]);
        VehicleMake::updateOrCreate(['id' => $this->editingId], ['name' => $data['name'], 'slug' => $data['slug'], 'country_code' => $data['countryCode'] ?: null, 'is_active' => $data['isActive']]);
        $this->resetForm(); session()->flash('success', 'سازنده خودرو ذخیره شد.');
    }
    public function edit(int $id): void { $item = VehicleMake::findOrFail($id); $this->editingId = $id; $this->name = $item->name; $this->slug = $item->slug; $this->countryCode = $item->country_code ?? ''; $this->isActive = $item->is_active; }
    public function toggle(int $id): void { $this->authorizeVehicleCatalog(); $item = VehicleMake::findOrFail($id); $item->update(['is_active' => ! $item->is_active]); }
    public function resetForm(): void { $this->reset(['name', 'slug', 'countryCode', 'editingId']); $this->isActive = true; }
    public function render() { return view('livewire.vehicles.make-index', ['items' => VehicleMake::query()->when($this->search, fn ($q) => $q->where('name', 'ilike', '%'.$this->search.'%'))->orderBy('name')->paginate(15)]); }
}
