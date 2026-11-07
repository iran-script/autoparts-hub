<?php

namespace App\Livewire\Vehicles;

use App\Domain\Vehicles\Models\Engine;
use App\Domain\Vehicles\Models\Transmission;
use App\Livewire\Vehicles\Concerns\AuthorizesVehicleCatalog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class ReferenceIndex extends Component
{
    use AuthorizesVehicleCatalog, WithPagination;
    public string $kind = 'engine'; public string $search = ''; public string $code = ''; public string $name = ''; public string $type = ''; public string $fuelType = ''; public ?int $gears = null; public ?int $displacementCc = null; public ?int $editingId = null; public bool $isActive = true;
    public function mount(string $kind = 'engine'): void { abort_unless(in_array($kind, ['engine', 'transmission'], true), 404); $this->kind = $kind; $this->authorizeVehicleCatalog('vehicles.view'); }
    protected function model(): string { return $this->kind === 'engine' ? Engine::class : Transmission::class; }
    public function save(): void
    {
        $this->authorizeVehicleCatalog(); $rules = ['code' => ['nullable', 'string', 'max:80'], 'name' => ['required', 'string', 'max:120'], 'isActive' => ['boolean']]; if ($this->kind === 'engine') $rules += ['fuelType' => ['nullable', 'string', 'max:40'], 'displacementCc' => ['nullable', 'integer', 'min:1', 'max:20000']]; else $rules += ['type' => ['nullable', 'string', 'max:40'], 'gears' => ['nullable', 'integer', 'min:1', 'max:20']]; $data = $this->validate($rules); $normalized = $this->code ? mb_strtoupper(trim($this->code)) : null; $query = $this->model()::where('normalized_code', $normalized); if ($this->editingId) $query->where('id', '<>', $this->editingId); if ($normalized && $query->exists()) abort(422, 'کد فنی تکراری است.'); $payload = ['code' => $this->code ?: null, 'normalized_code' => $normalized, 'name' => $data['name'], 'is_active' => $data['isActive']]; if ($this->kind === 'engine') $payload += ['fuel_type' => $data['fuelType'] ?: null, 'displacement_cc' => $data['displacementCc'] ?: null]; else $payload += ['type' => $data['type'] ?: null, 'gears' => $data['gears'] ?: null]; $this->model()::updateOrCreate(['id' => $this->editingId], $payload); $this->resetForm(); session()->flash('success', $this->kind === 'engine' ? 'موتور ذخیره شد.' : 'گیربکس ذخیره شد.');
    }
    public function edit(int $id): void { $item = $this->model()::findOrFail($id); $this->editingId = $id; $this->code = $item->code ?? ''; $this->name = $item->name; $this->isActive = $item->is_active; $this->fuelType = $item->fuel_type ?? ''; $this->displacementCc = $item->displacement_cc; $this->type = $item->type ?? ''; $this->gears = $item->gears; }
    public function toggle(int $id): void { $this->authorizeVehicleCatalog(); $item = $this->model()::findOrFail($id); $item->update(['is_active' => ! $item->is_active]); }
    public function resetForm(): void { $this->reset(['code', 'name', 'type', 'fuelType', 'gears', 'displacementCc', 'editingId']); $this->isActive = true; }
    public function render() { $items = $this->model()::query()->when($this->search, fn ($q) => $q->where(fn ($q) => $q->where('name', 'ilike', '%'.$this->search.'%')->orWhere('code', 'ilike', '%'.$this->search.'%')))->orderBy('name')->paginate(15); return view('livewire.vehicles.reference-index', compact('items')); }
}
