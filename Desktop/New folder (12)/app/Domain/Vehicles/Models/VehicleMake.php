<?php

namespace App\Domain\Vehicles\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehicleMake extends Model
{
    protected $guarded = [];
    protected function casts(): array { return ['is_active' => 'boolean']; }
    public function models(): HasMany { return $this->hasMany(VehicleModel::class); }
}
