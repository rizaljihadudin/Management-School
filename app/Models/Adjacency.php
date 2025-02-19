<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adjacency extends Model
{
    protected $guarded = [];

    protected $casts = [
        'subjects' => 'array',
    ];
}
