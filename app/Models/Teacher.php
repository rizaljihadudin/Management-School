<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{
    protected $guarded = [];

    public function classroom(): HasMany
    {
        return $this->hasMany(HomeRoom::class, 'teachers_id', 'id');
    }
}
