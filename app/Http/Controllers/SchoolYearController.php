<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SchoolYearController extends Controller
{
    public function index()
    {
        $schoolYears = SchoolYear::orderByDesc('year_label')->get();

        return view('admin.school-years', compact('schoolYears'));
    }

    public function storeNext()
    {
        $latest = SchoolYear::orderByDesc('id')->first();

        $nextLabel = $this->nextYearLabel($latest?->year_label);

        if (SchoolYear::where('year_label', $nextLabel)->exists()) {
            return back()->with('error', "{$nextLabel} already exists.");
        }

        $newYear = SchoolYear::create(['year_label' => $nextLabel, 'is_active' => false]);

        DB::transaction(function () use ($newYear) {
            SchoolYear::where('id', '!=', $newYear->id)->update(['is_active' => false]);
            $newYear->update(['is_active' => true]);

            $students = Student::where('enrollment_status', 'approved')
                ->whereHas('user', fn ($q) => $q->where('status', 'active'))
                ->with('user')
                ->get();

            foreach ($students as $student) {
                if ($student->grade_level >= 10) {
                    // Grade 10 students are done — mark their account inactive
                    $student->user->update(['status' => 'inactive']);
                } else {
                    $student->update([
                        'grade_level'    => $student->grade_level + 1,
                        'school_year_id' => $newYear->id,
                    ]);
                }
            }
        });

        return back()->with('success', "{$nextLabel} is now active. Students have been promoted.");
    }

    private function nextYearLabel(?string $label): string
    {
        if (!$label || !preg_match('/^(\d{4})-(\d{4})$/', $label, $m)) {
            $year = now()->year;
            return "{$year}-" . ($year + 1);
        }

        $start = (int) $m[1] + 1;
        return "{$start}-" . ($start + 1);
    }

    public function destroy(SchoolYear $schoolYear)
    {
        if ($schoolYear->is_active) {
            return back()->with('error', 'Cannot delete the active school year.');
        }

        $schoolYear->delete();

        return back()->with('success', 'School year removed.');
    }
}