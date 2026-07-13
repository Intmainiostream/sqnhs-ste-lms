@extends('student.layouts.app')

@section('title', 'Student Dashboard')

@section('content')
<div class="max-w-4xl mx-auto px-6 sm:px-10 lg:px-8 py-4 sm:py-6 lg:py-8">

    <div class="bg-gradient-to-br from-green-600 to-green-800 rounded-2xl shadow-lg px-4 sm:px-8 py-8 relative overflow-hidden">
        <svg class="absolute -right-8 -top-8 w-48 h-48 text-white/10 pointer-events-none" viewBox="0 0 64 64" fill="none">
            <circle cx="32" cy="32" r="3" fill="currentColor"/>
            <ellipse cx="32" cy="32" rx="28" ry="11" stroke="currentColor" stroke-width="2"/>
            <ellipse cx="32" cy="32" rx="28" ry="11" stroke="currentColor" stroke-width="2" transform="rotate(60 32 32)"/>
            <ellipse cx="32" cy="32" rx="28" ry="11" stroke="currentColor" stroke-width="2" transform="rotate(120 32 32)"/>
        </svg>
        <div class="relative">
            <h1 class="text-2xl sm:text-3xl font-bold text-white">
                Welcome, {{ $student->first_name ?? auth()->user()->username }}
            </h1>
            <p class="text-green-100 text-sm mt-1">Grade {{ $student->grade_level ?? '-' }} @if($student->section) &middot; Section {{ $student->section }} @endif</p>
        </div>
    </div>

    @if($noActiveSchoolYear)
        <div class="mt-4 bg-amber-50 border border-amber-200 text-amber-700 text-sm rounded-lg px-4 py-3">
            No active school year has been set yet. Grades and averages will appear once one is activated.
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Subjects Enrolled</p>
            <p class="text-2xl font-bold text-gray-800 mt-2 h-8 flex items-center">{{ $gradableSubjects->count() }}</p>
            <p class="text-xs mt-1" style="visibility: hidden;" aria-hidden="true">placeholder</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">General Average</p>
            <p class="text-2xl font-bold text-gray-800 mt-2 h-8 flex items-center">{{ $generalAverage ?? '—' }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $gradedCount }} of {{ $gradableSubjects->count() }} graded</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">School Year</p>
            <p class="text-2xl font-bold text-gray-800 mt-2 h-8 flex items-center">{{ $schoolYear->year_label ?? '—' }}</p>
            <p class="text-xs mt-1" style="visibility: hidden;" aria-hidden="true">placeholder</p>
        </div>
    </div>

    @php
        $graded = collect($performance)->filter(fn ($p) => $p['final_grade'] !== null);
        $best = $graded->sortByDesc('final_grade')->first();
        $worst = $graded->sortBy('final_grade')->first();
        $maxTerm = collect($termAverages)->filter()->max() ?: 100;
    @endphp

    @if($best && $worst && $best['name'] !== $worst['name'])
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Top Subject</p>
            <p class="text-lg font-bold text-gray-800 mt-2">{{ $best['name'] }}</p>
            <p class="text-sm text-green-700 font-medium mt-0.5">{{ $best['final_grade'] }} &middot; {{ $best['remarks'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Needs Improvement</p>
            <p class="text-lg font-bold text-gray-800 mt-2">{{ $worst['name'] }}</p>
            <p class="text-sm {{ $worst['final_grade'] >= 75 ? 'text-green-700' : 'text-red-600' }} font-medium mt-0.5">{{ $worst['final_grade'] }} &middot; {{ $worst['remarks'] }}</p>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mt-4">
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Subject Performance</h2>
        @forelse($performance as $subject)
            <div class="mb-4 last:mb-0">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-sm font-medium text-gray-700">{{ $subject['name'] }}</span>
                    <span class="text-sm font-semibold {{ $subject['final_grade'] !== null && $subject['final_grade'] >= 75 ? 'text-green-700' : ($subject['final_grade'] !== null ? 'text-red-600' : 'text-gray-400') }}">
                        {{ $subject['final_grade'] ?? '—' }}
                    </span>
                </div>
                <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full rounded-full {{ $subject['final_grade'] !== null && $subject['final_grade'] >= 75 ? 'bg-green-600' : 'bg-red-400' }}"
                         style="width: {{ $subject['final_grade'] !== null ? min(100, $subject['final_grade']) : 0 }}%;"></div>
                </div>
            </div>
        @empty
            <p class="text-sm text-gray-400">No subjects to show yet.</p>
        @endforelse
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mt-4">
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Term Progress</h2>
        <div class="grid grid-cols-3 gap-4">
            @foreach(['term1' => 'Term 1', 'term2' => 'Term 2', 'term3' => 'Term 3'] as $key => $label)
                <div class="text-center">
                    <div class="h-24 flex items-end justify-center">
                        <div class="w-10 rounded-t-lg {{ $termAverages[$key] !== null && $termAverages[$key] >= 75 ? 'bg-green-600' : 'bg-gray-200' }}"
                             style="height: {{ $termAverages[$key] !== null ? max(8, ($termAverages[$key] / $maxTerm) * 96) : 8 }}px;"></div>
                    </div>
                    <p class="text-sm font-bold text-gray-800 mt-2">{{ $termAverages[$key] ?? '—' }}</p>
                    <p class="text-xs text-gray-400">{{ $label }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <div class="mt-4 flex justify-end">
        <a href="{{ route('student.grades') }}"
           class="inline-flex items-center gap-2 bg-green-700 hover:bg-green-800 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition">
            View My Grades
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    </div>

</div>
@endsection