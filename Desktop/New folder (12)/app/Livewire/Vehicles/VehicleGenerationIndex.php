<?php

namespace App\Livewire\Vehicles;

use App\Domain\Vehicles\Models\VehicleGeneration;
use App\Domain\Vehicles\Models\VehicleMake;
use App\Domain\Vehicles\Models\VehicleModel;
use App\Livewire\Vehicles\Concerns\AuthorizesVehicleCatalog;
use Livewire\Component;
use Livewire\WithPagination;

class VehicleGenerationIndex extends Component
{
    use AuthorizesVehicleCatalog, WithPagination;
    public string $search = ''; public ?int $makeId = null; public ?int $modelId = null; public string $name = ''; public ?int $startYear = null; public ?int $endYear = null; public string $changeNotes = ''; public ?int $editingId = null; public bool $isActive = true;
    public function mount(): void { $this->authorizeVehicleCatalog('vehicles.view'); }
    public function updatedMakeId(): void { $this->modelId = null; }
    public function save(): void
    {
        $this->authorizeVehicleCatalog(); $data = $this->validate(['modelId' => ['required', 'exists:vehicle_models,id'], 'name' => ['required', 'string', 'max:120'], 'startYear' => ['nullable', 'integer', 'min:1300', 'max:2200'], 'endYear' => ['nullable', 'integer', 'gte:startYear', 'max:2200'], 'changeNotes' => ['nullable', 'string'], 'isActive' => ['boolean']]); $query = VehicleGeneration::where('vehicle_model_id', $data['modelId'])->where('name', $data['name']); if ($this->editingId) $query->where('id', '<>', $this->editingId); abort_if($query->exists(), 422, 'این نسل برای مدل انتخاب‌شده تکراری است.'); VehicleGeneration::updateOrCreate(['id' => $this->editingId], ['vehicle_model_id' => $data['modelId'], 'name' => $data['name'], 'production_start_year' => $data['startYear'] ?: null, 'production_end_year' => $data['endYear'] ?: null, 'change_notes' => $data['changeNotes'] ?: null, 'slug' => str()->slug($data['name']), 'is_active' => $data['isActive']]); $this->resetForm(); session()->flash('success', 'نسل خودرو ذخیره شد.');
    }
    public function edit(int $id): void { $item = VehicleGeneration::findOrFail($id); $this->editingId = $id; $this->modelId = $item->vehicle_model_id; $this->makeId = VehicleModel::find($item->vehicle_model_id)?->vehicle_make_id; $this->name = $item->name; $this->startYear = $item->production_start_year; $this->endYear = $item->production_end_year; $this->changeNotes = $item->change_notes ?? ''; $this->isActive = $item->is_active; }
    public function toggle(int $id): void { $this->authorizeVehicleCatalog(); $item = VehicleGeneration::findOrFail($id); $item->update(['is_active' => ! $item->is_active]); }
    public function resetForm(): void { $this->reset(['makeId', 'modelId', 'name', 'startYear', 'endYear', 'changeNotes', 'editingId']); $this->isActive = true; }
    public function render() { return view('livewire.vehicles.generation-index', ['makes' => VehicleMake::where('is_active', true)->orderBy('name')->get(), 'models' => $this->makeId ? VehicleModel::where('vehicle_make_id', $this->makeId)->where('is_active', true)->orderBy('name')->get() : collect(), 'items' => VehicleGeneration::with('model.make')->when($this->search, fn ($q) => $q->where('name', 'ilike', '%'.$this->search.'%'))->orderBy('name')->paginate(15)]); }
}
