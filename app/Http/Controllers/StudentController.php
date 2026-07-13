<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use App\Models\StudentGrade;
use App\Models\Subject;
use App\Models\AccountChangeRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

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
        ))->with('noActiveSchoolYear', !$schoolYear);
    }

    public function grades()
    {
        $student = auth()->user()->student;

        $schoolYear = SchoolYear::where('is_active', true)->first();

        if (!$schoolYear) {
            return back()->with('error', 'No active school year has been set yet. Please check back later or contact the admin.');
        }

        $subjects = Subject::whereNull('parent_subject_id')
            ->whereIn('grade_level', [7, 8, 9, 10])
            ->with(['children' => fn ($q) => $q->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get();

   
        $grades = StudentGrade::where('student_id', $student->id)
            ->orderByDesc('school_year_id')
            ->get()
            ->keyBy('subject_id');

        return view('student.grades', compact('student', 'subjects', 'grades', 'schoolYear'));
    }

    public function profile()
    {
        $student = auth()->user()->student;
        $user = auth()->user();

        $pendingRequest = AccountChangeRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->latest()
            ->first();

        $lastRequest = AccountChangeRequest::where('user_id', $user->id)
            ->whereIn('status', ['approved', 'rejected'])
            ->latest()
            ->first();

        return view('student.profile', compact('student', 'user', 'pendingRequest', 'lastRequest'));
    }

    public function requestCredentialsChange(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'new_username' => 'nullable|string|max:50|unique:users,username,' . $user->id,
            'new_email'    => 'nullable|email|max:150|unique:users,email,' . $user->id,
        ]);

        if (empty($data['new_username']) && empty($data['new_email'])) {
            return back()->with('error', 'Enter at least a new username or new email.');
        }

        if (!empty($data['new_username'])) {
            $user->username = $data['new_username'];
        }
        if (!empty($data['new_email'])) {
            $user->email = $data['new_email'];
        }
        $user->save();

        return back()->with('success', 'Your account details have been updated.');
    }

    public function requestPasswordChange(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'current_password' => 'required',
            'new_password'      => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($data['current_password'], $user->password)) {
            return back()->with('error', 'Current password is incorrect.');
        }

        $user->password = Hash::make($data['new_password']);
        $user->save();

        return back()->with('success', 'Your password has been updated.');
    }

    public function requestInfoUpdate(Request $request)
    {
        $user = auth()->user();

        if (AccountChangeRequest::where('user_id', $user->id)->where('status', 'pending')->exists()) {
            return back()->with('error', 'You already have a pending request. Please wait for admin review.');
        }

        $data = $request->validate([
            'lrn'                 => 'nullable|string|max:12',
            'psa_birth_cert_no'   => 'nullable|string|max:50',
            'first_name'          => 'required|string|max:100',
            'last_name'           => 'required|string|max:100',
            'middle_name'         => 'nullable|string|max:100',
            'birthdate'           => 'required|date',
            'sex'                 => 'required|in:Male,Female',
            'place_of_birth'      => 'nullable|string|max:150',
            'mother_tongue'       => 'nullable|string|max:100',
            'is_ip'               => 'nullable|boolean',
            'ip_specify'          => 'nullable|string|max:150',
            'is_4ps'              => 'nullable|boolean',
            'household_id'        => 'nullable|string|max:50',
            'has_disability'      => 'nullable|boolean',
            'disability_type'     => 'nullable|string|max:150',

            'current_house_no'    => 'required|string|max:50',
            'current_street'      => 'required|string|max:100',
            'current_barangay'    => 'required|string|max:100',
            'current_city'        => 'required|string|max:100',
            'current_province'    => 'required|string|max:100',
            'current_zip'         => 'required|string|max:10',

            'same_as_current'     => 'nullable|boolean',
            'permanent_house_no'  => 'nullable|string|max:50',
            'permanent_street'    => 'nullable|string|max:100',
            'permanent_barangay'  => 'nullable|string|max:100',
            'permanent_city'      => 'nullable|string|max:100',
            'permanent_province'  => 'nullable|string|max:100',
            'permanent_zip'       => 'nullable|string|max:10',

            'father_name'         => 'nullable|string|max:150',
            'father_contact'      => 'nullable|string|max:20',
            'mother_name'         => 'nullable|string|max:150',
            'mother_contact'      => 'nullable|string|max:20',
            'guardian_name'       => 'nullable|string|max:150',
            'guardian_contact'    => 'nullable|string|max:20',
        ]);

        $data['is_ip'] = $request->boolean('is_ip');
        $data['is_4ps'] = $request->boolean('is_4ps');
        $data['has_disability'] = $request->boolean('has_disability');
        $data['same_as_current'] = $request->boolean('same_as_current');

        AccountChangeRequest::create([
            'user_id' => $user->id,
            'changes' => $data,
        ]);

        return back()->with('success', 'Your information update has been submitted for admin approval.');
    }

    public function cancelChangeRequest(AccountChangeRequest $accountChangeRequest)
    {
        abort_unless($accountChangeRequest->user_id === auth()->id(), 403);

        if ($accountChangeRequest->status === 'pending') {
            $accountChangeRequest->delete();
        }

        return back()->with('success', 'Request cancelled.');
    }
}