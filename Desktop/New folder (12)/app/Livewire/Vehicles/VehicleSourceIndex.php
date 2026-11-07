<?php

namespace App\Livewire\Vehicles;

use App\Domain\Vehicles\Models\VehicleDataSource;
use App\Livewire\Vehicles\Concerns\AuthorizesVehicleCatalog;
use Livewire\Component;
use Livewire\WithPagination;

class VehicleSourceIndex extends Component
{
    use AuthorizesVehicleCatalog, WithPagination;
    public string $name = ''; public string $sourceType = 'official'; public string $uri = ''; public string $license = ''; public string $verificationStatus = 'UNVERIFIED'; public string $notes = ''; public ?int $editingId = null;
    public function mount(): void { $this->authorizeVehicleCatalog('vehicles.view'); }
    public function save(): void { $this->authorizeVehicleCatalog(); $data = $this->validate(['name' => ['required', 'string', 'max:160'], 'sourceType' => ['required', 'string', 'max:40'], 'uri' => ['nullable', 'url', 'max:500'], 'license' => ['nullable', 'string', 'max:160'], 'verificationStatus' => ['required', 'in:VERIFIED,UNVERIFIED,CONFLICTED'], 'notes' => ['nullable', 'string']]); VehicleDataSource::updateOrCreate(['id' => $this->editingId], ['name' => $data['name'], 'source_type' => $data['sourceType'], 'uri' => $data['uri'] ?: null, 'license' => $data['license'] ?: null, 'verification_status' => $data['verificationStatus'], 'notes' => $data['notes'] ?: null, 'last_reviewed_at' => $data['verificationStatus'] === 'VERIFIED' ? now()->toDateString() : null]); $this->resetForm(); session()->flash('success', 'منبع اطلاعات ذخیره شد.'); }
    public function edit(int $id): void { $item = VehicleDataSource::findOrFail($id); $this->editingId = $id; $this->name = $item->name; $this->sourceType = $item->source_type; $this->uri = $item->uri ?? ''; $this->license = $item->license ?? ''; $this->verificationStatus = $item->verification_status; $this->notes = $item->notes ?? ''; }
    public function resetForm(): void { $this->reset(['name', 'sourceType', 'uri', 'license', 'verificationStatus', 'notes', 'editingId']); $this->sourceType = 'official'; $this->verificationStatus = 'UNVERIFIED'; }
    public function render() { return view('livewire.vehicles.source-index', ['items' => VehicleDataSource::orderBy('name')->paginate(15)]); }
}
