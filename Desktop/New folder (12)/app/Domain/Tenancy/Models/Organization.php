<?php

namespace App\Domain\Tenancy\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

class Organization extends Model
{
    protected $guarded = [];
    protected function casts(): array { return ['public_id' => 'string']; }
    public function stores(): HasMany { return $this->hasMany(Store::class); }
    public function users(): HasMany { return $this->hasMany(User::class); }
}
