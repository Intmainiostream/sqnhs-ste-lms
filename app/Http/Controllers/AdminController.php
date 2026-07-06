<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $pendingStudents = Student::with('user')
            ->where('enrollment_status', 'pending')
            ->orderBy('created_at')
            ->get();

        return view('admin.dashboard', compact('pendingStudents'));
    }

    public function approve(Student $student)
    {
        $student->update([
            'enrollment_status' => 'approved',
        ]);

        $student->user->update([
            'status' => 'active',
        ]);

        return back()->with('success', 'Student approved successfully.');
    }

    public function reject(Student $student)
    {
        $student->update([
            'enrollment_status' => 'rejected',
        ]);

        $student->user->update([
            'status' => 'inactive',
        ]);

        return back()->with('success', 'Student rejected.');
    }
}