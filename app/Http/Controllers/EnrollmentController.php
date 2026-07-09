<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    public function create()
    {
        $pending = session('pending_registration');

        if (!$pending) {
            return redirect()->route('register')->withErrors([
                'username' => 'Please register first.',
            ]);
        }

        return view('enrollment.create', ['gradeLevel' => $pending['grade_level']]);
    }

    public function store(Request $request)
    {
        $pending = session('pending_registration');

        if (!$pending) {
            return redirect()->route('register')->withErrors([
                'username' => 'Please register first.',
            ]);
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

            'current_house_no'   => ['required', 'string', 'max:100'],
            'current_street'     => ['required', 'string', 'max:100'],
            'current_barangay'   => ['required', 'string', 'max:100'],
            'current_city'       => ['required', 'string', 'max:100'],
            'current_province'   => ['required', 'string', 'max:100'],
            'current_zip'        => ['required', 'string', 'max:10'],

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
        ], [], [
            'current_house_no' => 'house no.',
            'current_street'   => 'street',
            'current_barangay' => 'barangay',
            'current_city'     => 'municipality/city',
            'current_province' => 'province',
            'current_zip'      => 'zip code',
        ]);

        // Require at least one of: father, mother, guardian
        $hasFather = $request->filled('father_name');
        $hasMother = $request->filled('mother_name');
        $hasGuardian = $request->filled('guardian_name');

        if (!$hasFather && !$hasMother && !$hasGuardian) {
            return back()
                ->withErrors(['parent_info' => 'Please fill in at least one: Father, Mother, or Guardian information.'])
                ->withInput();
        }

        $validated['is_ip'] = $request->boolean('is_ip');
        $validated['is_4ps'] = $request->boolean('is_4ps');
        $validated['has_disability'] = $request->boolean('has_disability');
        $validated['same_as_current'] = $request->boolean('same_as_current');

        // If same as current, copy current address into permanent fields
        if ($validated['same_as_current']) {
            $validated['permanent_house_no'] = $validated['current_house_no'];
            $validated['permanent_street'] = $validated['current_street'];
            $validated['permanent_barangay'] = $validated['current_barangay'];
            $validated['permanent_city'] = $validated['current_city'];
            $validated['permanent_province'] = $validated['current_province'];
            $validated['permanent_zip'] = $validated['current_zip'];
        }

        // Re-check in case someone else grabbed the username/email while this form was open
        if (User::where('username', $pending['username'])->exists()
            || User::where('email', $pending['email'])->exists()) {
            session()->forget('pending_registration');

            return redirect()->route('register')->withErrors([
                'username' => 'That username or email was just taken. Please register again.',
            ]);
        }

        $activeSchoolYear = SchoolYear::where('is_active', true)->first();

        $user = User::create([
            'username' => $pending['username'],
            'email'    => $pending['email'],
            'password' => $pending['password'],
            'role'     => 'parent',
            'status'   => 'pending',
        ]);

        $validated['user_id']           = $user->id;
        $validated['school_year_id']    = $activeSchoolYear?->id;
        $validated['grade_level']       = $pending['grade_level'];
        $validated['enrollment_status'] = 'pending';

        Student::create($validated);

        session()->forget('pending_registration');

        Auth::login($user);

        return redirect()->route('enroll.pending');
    }

    public function pending()
    {
        return view('enrollment.pending');
    }
}