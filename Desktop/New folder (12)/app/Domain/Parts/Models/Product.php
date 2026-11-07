<?php

namespace App\Domain\Parts\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    protected function casts(): array { return ['public_id' => 'string', 'packaging' => 'array', 'is_active' => 'boolean']; }
    public function technicalPart(): BelongsTo { return $this->belongsTo(TechnicalPart::class); }
    public function brand(): BelongsTo { return $this->belongsTo(Brand::class); }
}
