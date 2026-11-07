<?php

namespace App\Domain\Parts\Models;

use App\Domain\Catalog\Models\CatalogSource;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FitmentEvidence extends Model
{
    protected $guarded = [];
    protected function casts(): array { return ['observed_at' => 'date']; }
    public function fitment(): BelongsTo { return $this->belongsTo(PartFitment::class, 'part_fitment_id'); }
    public function source(): BelongsTo { return $this->belongsTo(CatalogSource::class, 'catalog_source_id'); }
    public function reviewer(): BelongsTo { return $this->belongsTo(User::class, 'reviewed_by'); }
}
