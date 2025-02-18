<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Student extends Model
{
    protected $guarded = [];


    public static function scopeFilterByStudentHasClasses($query)
    {
        return $query->whereNotExists(function ($subQuery) {
            $subQuery->select(DB::raw(1))
                ->from('student_has_classes')
                ->whereColumn('student_has_classes.students_id', 'students.id');
        });
    }
}
