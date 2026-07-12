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

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mt-4">
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Enrollment Status</h2>
        <div class="flex items-center gap-3 bg-green-50 border border-green-200 rounded-lg px-4 py-3">
            <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>
            <p class="text-sm text-green-800">You are enrolled for {{ $student->schoolYear->year_label ?? 'this school year' }}.</p>
        </div>
    </div>

</div>
@endsection