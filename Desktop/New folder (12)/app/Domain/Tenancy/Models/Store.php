<?php

namespace App\Domain\Tenancy\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

class Store extends Model
{
    protected $guarded = [];
    public function organization(): BelongsTo { return $this->belongsTo(Organization::class); }
    public function branches(): HasMany { return $this->hasMany(Branch::class); }
    public function users(): HasMany { return $this->hasMany(User::class); }
}
