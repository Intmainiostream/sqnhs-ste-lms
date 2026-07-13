<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'pending'  => Student::where('enrollment_status', 'pending')->count(),
            'approved' => Student::where('enrollment_status', 'approved')->count(),
            'rejected' => Student::where('enrollment_status', 'rejected')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function users()
    {
     $users = User::with('student')->where('status', '!=', 'pending')->orderBy('role')->orderBy('username')->get()->map(function ($u) {
        $s = $u->student;

        $currentAddress = $s
            ? collect([$s->current_house_no, $s->current_street, $s->current_barangay, $s->current_city, $s->current_province, $s->current_zip])
                ->filter()->implode(', ')
            : null;

        $permanentAddress = $s
            ? ($s->same_as_current
                ? $currentAddress
                : collect([$s->permanent_house_no, $s->permanent_street, $s->permanent_barangay, $s->permanent_city, $s->permanent_province, $s->permanent_zip])
                    ->filter()->implode(', '))
            : null;

        return [
            'id'                => $u->id,
            'username'          => $u->username,
            'email'             => $u->email,
            'role'              => $u->role,
            'status'            => $u->status,
            'created_at'        => $u->created_at->format('F j, Y'),
            'first_name'        => $s->first_name ?? null,
            'last_name'         => $s->last_name ?? null,
            'grade_level'       => $s->grade_level ?? null,
            'birthdate'         => optional($s?->birthdate)->format('Y-m-d'),
            'current_address'   => $currentAddress ?: null,
            'permanent_address' => $permanentAddress ?: null,
            'father_name'       => $s->father_name ?? null,
            'father_contact'    => $s->father_contact ?? null,
            'mother_name'       => $s->mother_name ?? null,
            'mother_contact'    => $s->mother_contact ?? null,
            'guardian_name'     => $s->guardian_name ?? null,
            'guardian_contact'  => $s->guardian_contact ?? null,
        ];
    });

        return view('admin.users', compact('users'));
    }

    public function requests()
    {
        $pendingStudents = Student::with('user')
            ->where('enrollment_status', 'pending')
            ->orderBy('created_at')
            ->get();

        return view('admin.requests', compact('pendingStudents'));
    }

    public function records()
    {
        $students = Student::with('user')
            ->where('enrollment_status', 'approved')
            ->orderBy('grade_level')
            ->orderBy('last_name')
            ->get();

        return view('admin.records', compact('students'));
    }

    public function approve(Student $student)
    {
        $student->update(['enrollment_status' => 'approved']);
        $student->user->update(['status' => 'active']);

        return back()->with('success', 'Student approved successfully.');
    }

    public function reject(Student $student)
    {
        $user = $student->user;

        $student->forceDelete();
        $user->forceDelete();

        return back()->with('success', 'Registration rejected and removed.');
    }

    public function updateUser(Request $request, \App\Models\User $user)
{
    $validated = $request->validate([
        'role'              => 'required|in:admin,teacher,parent,student',
        'status'            => 'required|in:active,pending,inactive',
        'first_name'        => 'nullable|string|max:100',
        'last_name'         => 'nullable|string|max:100',
        'birthdate'         => 'nullable|date',
        'grade_level'       => 'nullable|integer|min:7|max:10',
        'father_name'       => 'nullable|string|max:150',
        'father_contact'    => 'nullable|string|max:50',
        'mother_name'       => 'nullable|string|max:150',
        'mother_contact'    => 'nullable|string|max:50',
        'guardian_name'     => 'nullable|string|max:150',
        'guardian_contact'  => 'nullable|string|max:50',
    ]);

    $user->update([
        'role'   => $validated['role'],
        'status' => $validated['status'],
    ]);

    if ($user->student) {
        $user->student->update(collect($validated)
            ->only([
                'first_name', 'last_name', 'birthdate', 'grade_level',
                'father_name', 'father_contact',
                'mother_name', 'mother_contact',
                'guardian_name', 'guardian_contact',
            ])
            ->toArray());
    }

    return response()->json(['success' => true]);
}

public function deleteUser(\App\Models\User $user)
{
    $user->delete();

    return response()->json(['success' => true]);
}

}