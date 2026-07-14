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
            ->whereIn('grade_level', [7, 8, 9, 10])
            ->with(['children' => fn ($q) => $q->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get();

    
        $existingGrades = StudentGrade::where('student_id', $student->id)
            ->orderByDesc('school_year_id')
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
        ]);

        $subjects = Subject::whereNull('parent_subject_id')
            ->whereIn('grade_level', [7, 8, 9, 10])
            ->where('grade_level', '<=', $student->grade_level)
            ->with(['children' => fn ($q) => $q->orderBy('sort_order')])
            ->get();

        $savedChildren = [];

        // Step 1: save leaf subjects first (standalone subjects + MAPEH-style children)
        foreach ($subjects as $subject) {
            if ($subject->children->isEmpty()) {
                $this->saveLeafGrade($student, $schoolYear, $subject->id, $data['grades'][$subject->id] ?? null);
            } else {
                foreach ($subject->children as $child) {
                    $savedChildren[$child->id] = $this->saveLeafGrade($student, $schoolYear, $child->id, $data['grades'][$child->id] ?? null);
                }
            }
        }

        // Step 2: save parent subjects, computed from their children's just-saved finals
        foreach ($subjects as $subject) {
            if ($subject->children->isEmpty()) {
                continue;
            }

            $entry = $data['grades'][$subject->id] ?? null;
            $isOverride = $entry['is_override'] ?? false;

            $grade = StudentGrade::firstOrNew([
                'student_id' => $student->id,
                'subject_id' => $subject->id,
            ]);

            $grade->school_year_id = $grade->school_year_id ?? $schoolYear->id;
            $grade->is_override = $isOverride;

            if ($isOverride) {
                $grade->final_grade = $entry['final_grade'] ?? null;
            } else {
                $childFinals = $subject->children
                    ->map(fn ($child) => $savedChildren[$child->id]->final_grade ?? null)
                    ->filter(fn ($f) => $f !== null);

                $grade->final_grade = $childFinals->isNotEmpty()
                    ? round($childFinals->avg(), 2)
                    : null;
            }

            $grade->remarks = $this->remarksForGrade($grade->final_grade);
            $grade->save();
        }

        return back()->with('success', 'Grades saved.');
    }

    private function saveLeafGrade(Student $student, SchoolYear $schoolYear, int $subjectId, ?array $entry): StudentGrade
    {
        $grade = StudentGrade::firstOrNew([
            'student_id' => $student->id,
            'subject_id' => $subjectId,
        ]);

        $grade->school_year_id = $grade->school_year_id ?? $schoolYear->id;
        $grade->term1 = $entry['term1'] ?? null;
        $grade->term2 = $entry['term2'] ?? null;
        $grade->term3 = $entry['term3'] ?? null;
        $grade->is_override = $entry['is_override'] ?? false;

        if ($grade->is_override) {
            $grade->final_grade = $entry['final_grade'] ?? null;
        } else {
            $grade->recalculateFinal();
        }

        $grade->remarks = $this->remarksForGrade($grade->final_grade);
        $grade->save();

        return $grade;
    }

    private function remarksForGrade($grade): ?string
    {
        if ($grade === null || $grade === '') {
            return null;
        }

        $grade = (float) $grade;

        return match (true) {
            $grade >= 90 => 'Outstanding',
            $grade >= 85 => 'Very Satisfactory',
            $grade >= 80 => 'Satisfactory',
            $grade >= 75 => 'Fairly Satisfactory',
            default => 'Failed',
        };
    }
}