<?php

namespace App\Domain\Vehicles\Models;

use Illuminate\Database\Eloquent\Model;

class Transmission extends Model { protected $guarded = []; protected function casts(): array { return ['specifications' => 'array', 'is_active' => 'boolean']; } }
