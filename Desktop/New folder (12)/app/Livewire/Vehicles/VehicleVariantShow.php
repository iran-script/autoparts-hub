<?php

namespace App\Livewire\Vehicles;

use App\Domain\Vehicles\Models\VehicleVariant;
use App\Domain\Vehicles\Models\VehicleDataSource;
use App\Livewire\Vehicles\Concerns\AuthorizesVehicleCatalog;
use Livewire\Component;

class VehicleVariantShow extends Component
{
    use AuthorizesVehicleCatalog;
    public VehicleVariant $variant;
    public ?int $sourceId = null; public string $evidenceType = ''; public string $verificationStatus = 'UNVERIFIED'; public string $sourceNotes = '';
    public function mount(VehicleVariant $variant): void { $this->authorizeVehicleCatalog('vehicles.view'); $this->variant = $variant->load('generation.model.make', 'engine', 'transmission', 'sources'); }
    public function attachSource(): void
    {
        $this->authorizeVehicleCatalog(); $data = $this->validate(['sourceId' => ['required', 'exists:vehicle_data_sources,id'], 'evidenceType' => ['required', 'string', 'max:80'], 'verificationStatus' => ['required', 'in:VERIFIED,UNVERIFIED,CONFLICTED'], 'sourceNotes' => ['nullable', 'string']]);
        $this->variant->sources()->syncWithoutDetaching([$data['sourceId'] => ['evidence_type' => $data['evidenceType'], 'verification_status' => $data['verificationStatus'], 'last_reviewed_at' => $data['verificationStatus'] === 'VERIFIED' ? now()->toDateString() : null, 'reviewed_by' => auth()->id(), 'notes' => $data['sourceNotes'] ?: null]]);
        $this->variant->load('sources'); $this->reset(['sourceId', 'evidenceType', 'sourceNotes']); $this->verificationStatus = 'UNVERIFIED'; session()->flash('success', 'منبع به نسخه خودرو متصل شد.');
    }
    public function render() { return view('livewire.vehicles.variant-show', ['sources' => VehicleDataSource::orderBy('name')->get()]); }
}
