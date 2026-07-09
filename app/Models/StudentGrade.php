<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentGrade extends Model
{
    protected $fillable = [
        'student_id', 'subject_id', 'school_year_id',
        'term1', 'term2', 'term3', 'final_grade', 'is_override', 'remarks',
    ];

    protected $casts = [
        'is_override' => 'boolean',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function recalculateFinal(): void
    {
        if ($this->is_override) {
            return;
        }

        $terms = array_filter([$this->term1, $this->term2, $this->term3], fn ($t) => $t !== null);

        $this->final_grade = count($terms) > 0
            ? round(array_sum($terms) / count($terms), 2)
            : null;
    }
}