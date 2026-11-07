<?php

namespace App\Domain\Parts\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TechnicalPart extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    protected function casts(): array { return ['public_id' => 'string', 'specifications' => 'array']; }
    public function category(): BelongsTo { return $this->belongsTo(PartCategory::class, 'part_category_id'); }
    public function numbers(): HasMany { return $this->hasMany(PartNumber::class); }
    public function products(): HasMany { return $this->hasMany(Product::class); }
    public function fitments(): HasMany { return $this->hasMany(PartFitment::class); }
}
