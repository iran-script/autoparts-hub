<?php

namespace App\Livewire\Vehicles;

use App\Domain\Vehicles\Models\Engine;
use App\Domain\Vehicles\Models\Transmission;
use App\Domain\Vehicles\Models\VehicleGeneration;
use App\Domain\Vehicles\Models\VehicleMake;
use App\Domain\Vehicles\Models\VehicleModel;
use App\Domain\Vehicles\Models\VehicleVariant;
use App\Livewire\Vehicles\Concerns\AuthorizesVehicleCatalog;
use Livewire\Component;
use Livewire\WithPagination;

class VehicleVariantIndex extends Component
{
    use AuthorizesVehicleCatalog, WithPagination;
    public string $search = ''; public ?int $makeId = null; public ?int $modelId = null; public ?int $generationId = null; public ?int $engineId = null; public ?int $transmissionId = null; public string $name = ''; public string $market = ''; public ?int $startYear = null; public ?int $endYear = null; public string $technicalNotes = ''; public ?int $editingId = null; public bool $isActive = true;
    public function mount(): void { $this->authorizeVehicleCatalog('vehicles.view'); }
    public function updatedMakeId(): void { $this->modelId = $this->generationId = null; }
    public function updatedModelId(): void { $this->generationId = null; }
    public function save(): void
    {
        $this->authorizeVehicleCatalog(); $data = $this->validate(['generationId' => ['required', 'exists:vehicle_generations,id'], 'engineId' => ['nullable', 'exists:engines,id'], 'transmissionId' => ['nullable', 'exists:transmissions,id'], 'name' => ['required', 'string', 'max:120'], 'market' => ['nullable', 'string', 'max:80'], 'startYear' => ['nullable', 'integer', 'min:1300', 'max:2200'], 'endYear' => ['nullable', 'integer', 'gte:startYear', 'max:2200'], 'technicalNotes' => ['nullable', 'string'], 'isActive' => ['boolean']]); $query = VehicleVariant::where('vehicle_generation_id', $data['generationId'])->where('name', $data['name'])->where('engine_id', $data['engineId'])->where('transmission_id', $data['transmissionId']); if ($this->editingId) $query->where('id', '<>', $this->editingId); abort_if($query->exists(), 422, 'این تیپ با همین ترکیب فنی قبلاً ثبت شده است.'); $item = VehicleVariant::updateOrCreate(['id' => $this->editingId], ['vehicle_generation_id' => $data['generationId'], 'engine_id' => $data['engineId'] ?: null, 'transmission_id' => $data['transmissionId'] ?: null, 'name' => $data['name'], 'market' => $data['market'] ?: null, 'production_start_year' => $data['startYear'] ?: null, 'production_end_year' => $data['endYear'] ?: null, 'technical_notes' => $data['technicalNotes'] ?: null, 'is_active' => $data['isActive']]); $this->resetForm(); session()->flash('success', 'تیپ خودرو ذخیره شد.'); $this->redirectRoute('vehicles.variants.show', ['variant' => $item->id]);
    }
    public function edit(int $id): void { $item = VehicleVariant::with('generation.model')->findOrFail($id); $this->editingId = $id; $this->generationId = $item->vehicle_generation_id; $this->modelId = $item->generation->vehicle_model_id; $this->makeId = $item->generation->model->vehicle_make_id; $this->engineId = $item->engine_id; $this->transmissionId = $item->transmission_id; $this->name = $item->name; $this->market = $item->market ?? ''; $this->startYear = $item->production_start_year; $this->endYear = $item->production_end_year; $this->technicalNotes = $item->technical_notes ?? ''; $this->isActive = $item->is_active; }
    public function toggle(int $id): void { $this->authorizeVehicleCatalog(); $item = VehicleVariant::findOrFail($id); $item->update(['is_active' => ! $item->is_active]); }
    public function resetForm(): void { $this->reset(['makeId', 'modelId', 'generationId', 'engineId', 'transmissionId', 'name', 'market', 'startYear', 'endYear', 'technicalNotes', 'editingId']); $this->isActive = true; }
    public function render() { return view('livewire.vehicles.variant-index', ['makes' => VehicleMake::where('is_active', true)->orderBy('name')->get(), 'models' => $this->makeId ? VehicleModel::where('vehicle_make_id', $this->makeId)->where('is_active', true)->orderBy('name')->get() : collect(), 'generations' => $this->modelId ? VehicleGeneration::where('vehicle_model_id', $this->modelId)->where('is_active', true)->orderBy('name')->get() : collect(), 'engines' => Engine::where('is_active', true)->orderBy('name')->get(), 'transmissions' => Transmission::where('is_active', true)->orderBy('name')->get(), 'items' => VehicleVariant::with('generation.model.make', 'engine', 'transmission')->when($this->search, fn ($q) => $q->where('name', 'ilike', '%'.$this->search.'%'))->orderBy('name')->paginate(15)]); }
}
