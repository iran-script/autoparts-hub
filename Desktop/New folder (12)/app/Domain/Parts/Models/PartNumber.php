<?php

namespace App\Domain\Parts\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartNumber extends Model
{
    protected $guarded = [];
    public function technicalPart(): BelongsTo { return $this->belongsTo(TechnicalPart::class); }
    public function brand(): BelongsTo { return $this->belongsTo(Brand::class); }
}
