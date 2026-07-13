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

        $schoolYear = SchoolYear::where('is_active', true)->first();

        $subjectGroups = Subject::whereNull('parent_subject_id')
            ->where('grade_level', $student->grade_level)
            ->with(['children' => fn ($q) => $q->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get();

        $gradableSubjects = $subjectGroups->filter(
            fn ($subject) => $subject->children->isNotEmpty() || $subject->is_gradable
        );

        $grades = $schoolYear
            ? StudentGrade::where('student_id', $student->id)
                ->where('school_year_id', $schoolYear->id)
                ->whereIn('subject_id', $gradableSubjects->pluck('id'))
                ->get()
                ->keyBy('subject_id')
            : collect();

        $gradedCount = $grades->whereNotNull('final_grade')->count();
        $generalAverage = $gradedCount > 0
            ? round($grades->whereNotNull('final_grade')->avg('final_grade'), 2)
            : null;

        $performance = $gradableSubjects->map(function ($subject) use ($grades) {
            $grade = $grades->get($subject->id);
            return [
                'name' => $subject->name,
                'final_grade' => $grade->final_grade ?? null,
                'remarks' => $grade->remarks ?? null,
            ];
        })->values();

        $leafSubjectIds = $subjectGroups->flatMap(function ($subject) {
            return $subject->children->isNotEmpty()
                ? $subject->children->pluck('id')
                : ($subject->is_gradable ? collect([$subject->id]) : collect());
        });

        $leafGrades = $schoolYear
            ? StudentGrade::where('student_id', $student->id)
                ->where('school_year_id', $schoolYear->id)
                ->whereIn('subject_id', $leafSubjectIds)
                ->get()
            : collect();

        $termAverages = [
            'term1' => $leafGrades->whereNotNull('term1')->avg('term1'),
            'term2' => $leafGrades->whereNotNull('term2')->avg('term2'),
            'term3' => $leafGrades->whereNotNull('term3')->avg('term3'),
        ];
        $termAverages = array_map(fn ($v) => $v !== null ? round($v, 2) : null, $termAverages);

        return view('student.dashboard', compact(
            'student', 'schoolYear', 'gradableSubjects', 'gradedCount', 'generalAverage',
            'performance', 'termAverages'
        ));
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