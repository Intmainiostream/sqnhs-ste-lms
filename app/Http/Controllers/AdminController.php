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
     $users = User::with('student')->orderBy('role')->orderBy('username')->get()->map(fn ($u) => [
    'id'                => $u->id,
    'username'          => $u->username,
    'email'             => $u->email,
    'role'              => $u->role,
    'status'            => $u->status,
    'created_at'        => $u->created_at->format('F j, Y'),
    'first_name'        => $u->student->first_name ?? null,
    'last_name'         => $u->student->last_name ?? null,
    'grade_level'       => $u->student->grade_level ?? null,
    'birthdate'         => optional($u->student?->birthdate)->format('Y-m-d'),
    'current_address'   => $u->student->current_address ?? null,
    'permanent_address' => $u->student->permanent_address ?? null,
    'father_name'       => $u->student->father_name ?? null,
    'father_contact'    => $u->student->father_contact ?? null,
    'mother_name'       => $u->student->mother_name ?? null,
    'mother_contact'    => $u->student->mother_contact ?? null,
    'guardian_name'     => $u->student->guardian_name ?? null,
    'guardian_contact'  => $u->student->guardian_contact ?? null,
]);

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

    public function records(Request $request)
    {
        $gradeFilter = $request->query('grade');

        $students = Student::with('user')
            ->where('enrollment_status', 'approved')
            ->when($gradeFilter, fn ($q) => $q->where('grade_level', $gradeFilter))
            ->orderBy('grade_level')
            ->orderBy('last_name')
            ->get();

        return view('admin.records', compact('students', 'gradeFilter'));
    }

    public function approve(Student $student)
    {
        $student->update(['enrollment_status' => 'approved']);
        $student->user->update(['status' => 'active']);

        return back()->with('success', 'Student approved successfully.');
    }

    public function reject(Student $student)
    {
        $student->update(['enrollment_status' => 'rejected']);
        $student->user->update(['status' => 'inactive']);

        return back()->with('success', 'Student rejected.');
    }

    public function updateUser(Request $request, \App\Models\User $user)
{
    $validated = $request->validate([
        'role'              => 'required|in:admin,teacher,parent,student',
        'status'            => 'required|in:active,pending,inactive',
        'first_name'        => 'nullable|string|max:100',
        'last_name'         => 'nullable|string|max:100',
        'birthdate'         => 'nullable|date',
        'current_address'   => 'nullable|string|max:255',
        'permanent_address' => 'nullable|string|max:255',
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
                'first_name', 'last_name', 'birthdate',
                'current_address', 'permanent_address',
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