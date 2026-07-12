<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use App\Models\StudentGrade;
use App\Models\Subject;

class StudentController extends Controller
{
    public function dashboard()
    {
        $student = auth()->user()->student;

        return view('student.dashboard', compact('student'));
    }

    public function grades()
    {
        $student = auth()->user()->student;

        $schoolYear = SchoolYear::where('is_active', true)->firstOrFail();

        $subjects = Subject::whereNull('parent_subject_id')
            ->whereIn('grade_level', [7, 8, 9, 10])
            ->with(['children' => fn ($q) => $q->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get();

        $grades = StudentGrade::where('student_id', $student->id)
            ->where('school_year_id', $schoolYear->id)
            ->get()
            ->keyBy('subject_id');

        return view('student.grades', compact('student', 'subjects', 'grades', 'schoolYear'));
    }
}