<?php

namespace App\Domain\Vehicles\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehicleModel extends Model
{
    protected $guarded = [];
    protected function casts(): array { return ['is_active' => 'boolean']; }
    public function make(): BelongsTo { return $this->belongsTo(VehicleMake::class, 'vehicle_make_id'); }
    public function generations(): HasMany { return $this->hasMany(VehicleGeneration::class); }
}
