<?php

namespace App\Domain\Vehicles\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleVariant extends Model
{
    protected $guarded = [];
    protected function casts(): array { return ['identifiers' => 'array', 'specifications' => 'array', 'is_active' => 'boolean']; }
    public function generation(): BelongsTo { return $this->belongsTo(VehicleGeneration::class, 'vehicle_generation_id'); }
    public function engine(): BelongsTo { return $this->belongsTo(Engine::class); }
    public function transmission(): BelongsTo { return $this->belongsTo(Transmission::class); }
    public function sources() { return $this->belongsToMany(VehicleDataSource::class, 'vehicle_variant_sources')->withPivot(['evidence_type', 'verification_status', 'last_reviewed_at', 'reviewed_by', 'notes'])->withTimestamps(); }
}
