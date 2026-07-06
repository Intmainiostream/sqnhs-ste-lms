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
        'grade_level',
        'section',
        'enrollment_status',

        'lrn',
        'psa_birth_cert_no',
        'first_name',
        'last_name',
        'middle_name',
        'birthdate',
        'sex',
        'place_of_birth',
        'mother_tongue',
        'is_ip',
        'ip_specify',
        'is_4ps',
        'household_id',
        'has_disability',
        'disability_type',

        'current_house_no',
        'current_street',
        'current_barangay',
        'current_city',
        'current_province',
        'current_zip',

        'same_as_current',
        'permanent_house_no',
        'permanent_street',
        'permanent_barangay',
        'permanent_city',
        'permanent_province',
        'permanent_zip',

        'father_name',
        'father_contact',
        'mother_name',
        'mother_contact',
        'guardian_name',
        'guardian_contact',

        'is_returning',
        'last_grade_completed',
        'last_sy_completed',
        'last_school_attended',
        'last_school_id',
    ];

    protected $casts = [
        'is_ip'           => 'boolean',
        'is_4ps'          => 'boolean',
        'has_disability'  => 'boolean',
        'same_as_current' => 'boolean',
        'is_returning'    => 'boolean',
        'birthdate'       => 'date',
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