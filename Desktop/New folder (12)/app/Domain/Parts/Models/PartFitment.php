<?php

namespace App\Domain\Parts\Models;

use App\Domain\Vehicles\Models\VehicleVariant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PartFitment extends Model
{
    protected $guarded = [];
    protected function casts(): array { return ['constraints' => 'array']; }
    public function technicalPart(): BelongsTo { return $this->belongsTo(TechnicalPart::class); }
    public function vehicleVariant(): BelongsTo { return $this->belongsTo(VehicleVariant::class); }
    public function evidence(): HasMany { return $this->hasMany(FitmentEvidence::class); }
}
