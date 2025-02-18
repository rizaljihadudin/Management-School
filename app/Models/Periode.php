<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Periode extends Model
{
    protected $guarded = [];

    public function team(): BelongsToMany
    {
        return $this->belongsToMany(Team::class);
    }
}
