<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'school_year_id',
        'first_name',
        'last_name',
        'middle_name',
        'birthdate',
        'address',
        'grade_level',
        'section',
        'parent_name',
        'parent_contact',
        'parent_relationship',
        'enrollment_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }
}