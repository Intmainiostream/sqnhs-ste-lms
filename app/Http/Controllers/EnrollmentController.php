<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student || $student->grade_level != 7) {
            return redirect()->route('enroll.pending');
        }

        return view('enrollment.create', compact('student'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student || $student->grade_level != 7) {
            return redirect()->route('enroll.pending');
        }

        $validated = $request->validate([
            'first_name'          => ['required', 'string', 'max:100'],
            'last_name'           => ['required', 'string', 'max:100'],
            'middle_name'         => ['nullable', 'string', 'max:100'],
            'birthdate'           => ['required', 'date'],
            'address'             => ['required', 'string', 'max:255'],
            'parent_name'         => ['required', 'string', 'max:150'],
            'parent_contact'      => ['required', 'string', 'max:20'],
            'parent_relationship' => ['required', 'string', 'max:50'],
        ]);

        $student->update($validated);

        return redirect()->route('enroll.pending');
    }

    public function pending()
    {
        return view('enrollment.pending');
    }
}