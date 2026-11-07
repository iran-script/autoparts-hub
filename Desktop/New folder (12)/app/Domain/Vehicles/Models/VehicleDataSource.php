<?php

namespace App\Domain\Vehicles\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class VehicleDataSource extends Model
{
    protected $guarded = [];
    protected function casts(): array { return ['last_reviewed_at' => 'date']; }
    public function variants(): BelongsToMany { return $this->belongsToMany(VehicleVariant::class, 'vehicle_variant_sources')->withPivot(['evidence_type', 'verification_status', 'last_reviewed_at', 'reviewed_by', 'notes'])->withTimestamps(); }
}
