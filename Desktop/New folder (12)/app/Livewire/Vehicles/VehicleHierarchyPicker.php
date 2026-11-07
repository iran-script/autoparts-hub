<?php

namespace App\Livewire\Vehicles;

use App\Domain\Vehicles\Models\Engine;
use App\Domain\Vehicles\Models\Transmission;
use App\Domain\Vehicles\Models\VehicleGeneration;
use App\Domain\Vehicles\Models\VehicleMake;
use App\Domain\Vehicles\Models\VehicleModel;
use Livewire\Component;

class VehicleHierarchyPicker extends Component
{
    public ?int $makeId = null;
    public ?int $modelId = null;
    public ?int $generationId = null;
    public ?int $engineId = null;
    public ?int $transmissionId = null;

    public function updatedMakeId(): void { $this->modelId = $this->generationId = null; }
    public function updatedModelId(): void { $this->generationId = null; }
    public function updatedGenerationId(): void { $this->dispatch('vehicle-generation-selected', generationId: $this->generationId); }

    public function render()
    {
        return view('livewire.vehicles.hierarchy-picker', [
            'makes' => VehicleMake::query()->where('is_active', true)->orderBy('name')->get(),
            'models' => $this->makeId ? VehicleModel::where('vehicle_make_id', $this->makeId)->where('is_active', true)->orderBy('name')->get() : collect(),
            'generations' => $this->modelId ? VehicleGeneration::where('vehicle_model_id', $this->modelId)->where('is_active', true)->orderBy('name')->get() : collect(),
            'engines' => Engine::query()->where('is_active', true)->orderBy('name')->get(),
            'transmissions' => Transmission::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }
}
