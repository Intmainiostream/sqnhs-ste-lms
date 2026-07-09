<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\StudentGrade;
use App\Models\Subject;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function edit(Student $student)
    {
        $schoolYear = SchoolYear::where('is_active', true)->firstOrFail();

        $subjects = Subject::whereNull('parent_subject_id')
            ->where('grade_level', $student->grade_level)
            ->with(['children' => fn ($q) => $q->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get();

        $existingGrades = StudentGrade::where('student_id', $student->id)
            ->where('school_year_id', $schoolYear->id)
            ->get()
            ->keyBy('subject_id');

        return view('admin.grades.edit', compact('student', 'subjects', 'existingGrades', 'schoolYear'));
    }

    public function update(Request $request, Student $student)
    {
        $schoolYear = SchoolYear::where('is_active', true)->firstOrFail();

        $data = $request->validate([
            'grades' => 'required|array',
            'grades.*.term1' => 'nullable|numeric|min:60|max:100',
            'grades.*.term2' => 'nullable|numeric|min:60|max:100',
            'grades.*.term3' => 'nullable|numeric|min:60|max:100',
            'grades.*.final_grade' => 'nullable|numeric|min:60|max:100',
            'grades.*.is_override' => 'nullable|boolean',
            'grades.*.remarks' => 'nullable|string|max:255',
        ]);

        foreach ($data['grades'] as $subjectId => $entry) {
            $grade = StudentGrade::firstOrNew([
                'student_id' => $student->id,
                'subject_id' => $subjectId,
                'school_year_id' => $schoolYear->id,
            ]);

            $grade->term1 = $entry['term1'] ?? null;
            $grade->term2 = $entry['term2'] ?? null;
            $grade->term3 = $entry['term3'] ?? null;
            $grade->is_override = $entry['is_override'] ?? false;
            $grade->remarks = $entry['remarks'] ?? null;

            if ($grade->is_override) {
                $grade->final_grade = $entry['final_grade'] ?? null;
            } else {
                $grade->recalculateFinal();
            }

            $grade->save();
        }

        return back()->with('success', 'Grades saved.');
    }
}