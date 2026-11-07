<?php

namespace App\Domain\Vehicles\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehicleGeneration extends Model
{
    protected $guarded = [];
    protected function casts(): array { return ['is_active' => 'boolean']; }
    public function model(): BelongsTo { return $this->belongsTo(VehicleModel::class, 'vehicle_model_id'); }
    public function variants(): HasMany { return $this->hasMany(VehicleVariant::class); }
}
