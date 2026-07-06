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
            'lrn'               => ['nullable', 'string', 'max:20'],
            'psa_birth_cert_no' => ['nullable', 'string', 'max:50'],
            'first_name'        => ['required', 'string', 'max:100'],
            'last_name'         => ['required', 'string', 'max:100'],
            'middle_name'       => ['nullable', 'string', 'max:100'],
            'birthdate'         => ['required', 'date'],
            'sex'               => ['required', 'in:Male,Female'],
            'place_of_birth'    => ['nullable', 'string', 'max:150'],
            'mother_tongue'     => ['nullable', 'string', 'max:100'],

            'is_ip'             => ['nullable', 'boolean'],
            'ip_specify'        => ['nullable', 'string', 'max:150'],
            'is_4ps'            => ['nullable', 'boolean'],
            'household_id'      => ['nullable', 'string', 'max:50'],
            'has_disability'    => ['nullable', 'boolean'],
            'disability_type'   => ['nullable', 'string', 'max:150'],

            'current_house_no'   => ['nullable', 'string', 'max:100'],
            'current_street'     => ['nullable', 'string', 'max:100'],
            'current_barangay'   => ['nullable', 'string', 'max:100'],
            'current_city'       => ['nullable', 'string', 'max:100'],
            'current_province'   => ['nullable', 'string', 'max:100'],
            'current_zip'        => ['nullable', 'string', 'max:10'],

            'same_as_current'    => ['nullable', 'boolean'],
            'permanent_house_no' => ['nullable', 'string', 'max:100'],
            'permanent_street'   => ['nullable', 'string', 'max:100'],
            'permanent_barangay' => ['nullable', 'string', 'max:100'],
            'permanent_city'     => ['nullable', 'string', 'max:100'],
            'permanent_province' => ['nullable', 'string', 'max:100'],
            'permanent_zip'      => ['nullable', 'string', 'max:10'],

            'father_name'      => ['nullable', 'string', 'max:150'],
            'father_contact'   => ['nullable', 'string', 'max:20'],
            'mother_name'      => ['nullable', 'string', 'max:150'],
            'mother_contact'   => ['nullable', 'string', 'max:20'],
            'guardian_name'    => ['nullable', 'string', 'max:150'],
            'guardian_contact' => ['nullable', 'string', 'max:20'],
        ]);

        $validated['is_ip'] = $request->boolean('is_ip');
        $validated['is_4ps'] = $request->boolean('is_4ps');
        $validated['has_disability'] = $request->boolean('has_disability');
        $validated['same_as_current'] = $request->boolean('same_as_current');

        // If same as current, copy current address into permanent fields
        if ($validated['same_as_current']) {
            $validated['permanent_house_no'] = $validated['current_house_no'] ?? null;
            $validated['permanent_street'] = $validated['current_street'] ?? null;
            $validated['permanent_barangay'] = $validated['current_barangay'] ?? null;
            $validated['permanent_city'] = $validated['current_city'] ?? null;
            $validated['permanent_province'] = $validated['current_province'] ?? null;
            $validated['permanent_zip'] = $validated['current_zip'] ?? null;
        }

        $student->update($validated);

        return redirect()->route('enroll.pending');
    }

    public function pending()
    {
        return view('enrollment.pending');
    }
}