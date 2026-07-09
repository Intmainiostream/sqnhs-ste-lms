<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::whereNull('parent_subject_id')
            ->with(['children' => fn ($q) => $q->orderBy('sort_order')])
            ->orderBy('grade_level')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('grade_level');

        return view('admin.subjects', compact('subjects'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'grade_level' => 'required|integer|min:7|max:10',
            'name' => 'required|string|max:100',
            'parent_subject_id' => 'nullable|exists:subjects,id',
            'is_gradable' => 'boolean',
        ]);

        $data['sort_order'] = Subject::where('grade_level', $data['grade_level'])
            ->where('parent_subject_id', $data['parent_subject_id'] ?? null)
            ->max('sort_order') + 1;

        Subject::create($data);

        return back()->with('success', 'Subject added.');
    }

    public function destroy(Subject $subject)
    {
        $subject->children()->delete();
        $subject->delete();

        return back()->with('success', 'Subject removed.');
    }
}