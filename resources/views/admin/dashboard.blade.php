@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    {{-- HERO / STATS --}}
    <section class="bg-gradient-to-br from-green-700 to-green-800 text-white px-6 py-14">
        <div class="max-w-5xl mx-auto text-center">
            <p class="text-green-200 text-sm font-semibold uppercase tracking-wide mb-2">Admin Dashboard</p>
            <h1 class="text-3xl sm:text-4xl font-bold mb-3">San Quintin National High School</h1>
            <p class="text-green-100 max-w-xl mx-auto">Science, Technology & Engineering Program — Enrollment Management</p>
        </div>

        <div class="max-w-4xl mx-auto grid sm:grid-cols-3 gap-4 mt-10">
            <div class="bg-white/10 backdrop-blur rounded-xl p-5 text-center">
                <p class="text-3xl font-bold">{{ $stats['pending'] }}</p>
                <p class="text-green-100 text-xs uppercase tracking-wide mt-1">Pending Requests</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-5 text-center">
                <p class="text-3xl font-bold">{{ $stats['approved'] }}</p>
                <p class="text-green-100 text-xs uppercase tracking-wide mt-1">Approved Students</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-5 text-center">
                <p class="text-3xl font-bold">{{ $stats['rejected'] }}</p>
                <p class="text-green-100 text-xs uppercase tracking-wide mt-1">Rejected</p>
            </div>
        </div>
    </section>

    {{-- ABOUT US --}}
    <section class="max-w-4xl mx-auto px-6 py-16">
        <h2 class="text-2xl font-bold text-green-900 mb-4">About the STE Program</h2>
        <p class="text-gray-600 leading-relaxed mb-4">
            The Science, Technology, and Engineering (STE) program at San Quintin National High School is a specialized
            curriculum track designed to nurture students with strong aptitude in the sciences and technology. Since 1968,
            SQNHS has served the municipality of San Quintin, Pangasinan, providing quality secondary education to the community.
        </p>
        <p class="text-gray-600 leading-relaxed">
            This LMS platform streamlines the enrollment process for STE students from Grade 7 to Grade 10, allowing parents
            and students to register, submit enrollment forms, and track their application status online — reducing the
            need for repeated in-person visits during enrollment season.
        </p>
    </section>

    {{-- STE HIGHLIGHTS --}}
    <section class="bg-green-50 border-y border-green-100 px-6 py-16">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-2xl font-bold text-green-900 mb-8 text-center">The STE Program</h2>
            <div class="grid sm:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl p-6 text-center border border-green-100">
                    <div class="w-12 h-12 mx-auto mb-4 rounded-full bg-green-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                    <p class="font-semibold text-green-900 text-sm mb-1">Science & Research</p>
                    <p class="text-gray-500 text-xs">Hands-on laboratory work and scientific inquiry from Grade 7.</p>
                </div>
                <div class="bg-white rounded-xl p-6 text-center border border-green-100">
                    <div class="w-12 h-12 mx-auto mb-4 rounded-full bg-green-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="font-semibold text-green-900 text-sm mb-1">Technology & Computing</p>
                    <p class="text-gray-500 text-xs">Programming, digital literacy, and computational thinking.</p>
                </div>
                <div class="bg-white rounded-xl p-6 text-center border border-green-100">
                    <div class="w-12 h-12 mx-auto mb-4 rounded-full bg-green-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <p class="font-semibold text-green-900 text-sm mb-1">Engineering & Design</p>
                    <p class="text-gray-500 text-xs">Project-based learning applying engineering principles.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- LOCATION --}}
    <section class="max-w-4xl mx-auto px-6 py-16">
        <h2 class="text-2xl font-bold text-green-900 mb-6">School Location</h2>
        <div class="grid sm:grid-cols-2 gap-6 items-start">
            <div>
                <p class="text-gray-600 mb-2"><span class="font-semibold text-green-800">Address:</span> San Quintin National High School, San Quintin, Pangasinan, Philippines</p>
                <p class="text-gray-600"><span class="font-semibold text-green-800">Founded:</span> 1968</p>
            </div>
            <div class="rounded-xl overflow-hidden border border-gray-200 h-64">
                <iframe
                    class="w-full h-full"
                    style="border:0;"
                    loading="lazy"
                    src="https://www.google.com/maps?q=San+Quintin+National+High+School,San+Quintin,Pangasinan&output=embed">
                </iframe>
            </div>
        </div>
    </section>

    <footer class="text-center text-gray-400 text-xs py-8 border-t border-gray-100">
        &copy; {{ date('Y') }} San Quintin National High School. All rights reserved.
    </footer>
@endsection