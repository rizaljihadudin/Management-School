<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentHasClass extends Model
{
    protected $guarded = [];
    protected $table = 'student_has_classes';

    public function students() :BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function homeroom() :BelongsTo
    {
        return $this->belongsTo(HomeRoom::class, 'homerooms_id', 'id');
    }
}
