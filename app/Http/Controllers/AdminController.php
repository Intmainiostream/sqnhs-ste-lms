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
        $users = User::orderBy('role')->orderBy('username')->get();

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
}