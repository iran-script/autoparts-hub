<?php

namespace App\Domain\Tenancy\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Branch extends Model
{
    protected $guarded = [];
    public function store(): BelongsTo { return $this->belongsTo(Store::class); }
}
