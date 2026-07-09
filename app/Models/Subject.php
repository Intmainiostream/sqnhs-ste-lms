<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['grade_level', 'name', 'parent_subject_id', 'is_gradable', 'sort_order'];

    public function parent()
    {
        return $this->belongsTo(Subject::class, 'parent_subject_id');
    }

    public function children()
    {
        return $this->hasMany(Subject::class, 'parent_subject_id')->orderBy('sort_order');
    }

    public function grades()
    {
        return $this->hasMany(StudentGrade::class);
    }
}