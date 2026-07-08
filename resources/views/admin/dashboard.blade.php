@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    {{-- HERO / STATS --}}
    <section id="top" class="bg-gradient-to-br from-green-700 to-green-800 text-white px-6 py-14">
        <div class="max-w-5xl mx-auto text-center">
            <p class="text-green-200 text-sm font-semibold uppercase tracking-wide mb-2">Admin Dashboard</p>
            <h1 class="text-3xl sm:text-4xl font-bold mb-3">San Quintin National High School</h1>
            <p class="text-green-100 max-w-xl mx-auto">Science, Technology & Engineering Program — Enrollment Management</p>
        </div>

        <div class="max-w-2xl mx-auto flex flex-wrap justify-center gap-8 mt-10">
            <a href="#top" class="text-white text-sm font-semibold uppercase tracking-wide hover:text-green-200 transition">Home</a>
            <a href="#about" class="text-green-200 text-sm font-semibold uppercase tracking-wide hover:text-white transition">About</a>
            <a href="{{ route('admin.records') }}" class="text-green-200 text-sm font-semibold uppercase tracking-wide hover:text-white transition">Records</a>
            <a href="{{ route('admin.users') }}" class="text-green-200 text-sm font-semibold uppercase tracking-wide hover:text-white transition">Users</a>
        </div>
    </section>

    {{-- ABOUT US --}}
    <section id="about" class="max-w-4xl mx-auto px-6 py-16">
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
                <div class="bg-white rounded-2xl p-6 border border-green-100 relative overflow-hidden">
                    <svg class="w-14 h-14 mb-4 text-green-700" viewBox="0 0 64 64" fill="none">
                        <path d="M24 8h16M27 8v14l-11 24a4 4 0 003.6 6h25a4 4 0 003.6-6l-11-24V8" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/>
                        <path d="M22 40h20" stroke="currentColor" stroke-width="2.5"/>
                        <circle cx="28" cy="48" r="1.8" fill="currentColor"/>
                        <circle cx="36" cy="52" r="2.2" fill="currentColor"/>
                        <circle cx="32" cy="45" r="1.4" fill="currentColor"/>
                    </svg>
                    <p class="font-bold text-green-900 mb-1">Science & Research</p>
                    <p class="text-gray-500 text-xs">Hands-on laboratory work and scientific inquiry from Grade 7.</p>
                </div>
                <div class="bg-white rounded-2xl p-6 border border-green-100 relative overflow-hidden">
                    <svg class="w-14 h-14 mb-4 text-green-700" viewBox="0 0 64 64" fill="none">
                        <circle cx="32" cy="32" r="4.5" fill="currentColor"/>
                        <ellipse cx="32" cy="32" rx="26" ry="10" stroke="currentColor" stroke-width="2.5"/>
                        <ellipse cx="32" cy="32" rx="26" ry="10" stroke="currentColor" stroke-width="2.5" transform="rotate(60 32 32)"/>
                        <ellipse cx="32" cy="32" rx="26" ry="10" stroke="currentColor" stroke-width="2.5" transform="rotate(120 32 32)"/>
                    </svg>
                    <p class="font-bold text-green-900 mb-1">Technology & Computing</p>
                    <p class="text-gray-500 text-xs">Programming, digital literacy, and computational thinking.</p>
                </div>
                <div class="bg-white rounded-2xl p-6 border border-green-100 relative overflow-hidden">
                    <svg class="w-14 h-14 mb-4 text-green-700" viewBox="0 0 64 64" fill="none">
                        <path d="M32 6l4.5 8.4 9.4 1.4-6.8 6.6 1.6 9.3L32 27.3l-8.7 4.4 1.6-9.3-6.8-6.6 9.4-1.4L32 6z" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/>
                        <path d="M18 40h28M18 48h20M18 56h24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                    </svg>
                    <p class="font-bold text-green-900 mb-1">Engineering & Design</p>
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

    <footer class="bg-gradient-to-b from-slate-900 via-emerald-950 to-slate-950 text-slate-300 mt-8 relative overflow-hidden">
        <svg class="absolute -right-16 -top-16 w-[420px] h-[420px] text-emerald-800/20 pointer-events-none" viewBox="0 0 64 64" fill="none">
            <circle cx="32" cy="32" r="3" fill="currentColor"/>
            <ellipse cx="32" cy="32" rx="28" ry="11" stroke="currentColor" stroke-width="1.5"/>
            <ellipse cx="32" cy="32" rx="28" ry="11" stroke="currentColor" stroke-width="1.5" transform="rotate(60 32 32)"/>
            <ellipse cx="32" cy="32" rx="28" ry="11" stroke="currentColor" stroke-width="1.5" transform="rotate(120 32 32)"/>
        </svg>
        

        <div class="max-w-6xl mx-auto px-4 sm:px-6 pt-10 pb-6 relative">
            <div class="grid sm:grid-cols-12 gap-6 sm:gap-8">
                <div class="sm:col-span-5">
                    <div class="flex items-center gap-2.5 mb-2.5">
                        <img src="{{ asset('images/sqnhs-logo.png') }}" alt="Logo" class="h-9 w-9 object-contain">
                        <span class="font-bold text-white text-lg tracking-tight" style="font-family: 'Sora', sans-serif;">SQNHS STE LMS</span>
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed max-w-sm mb-3">
                        Science, Technology & Engineering Program at San Quintin National High School — nurturing young innovators since 1968.
                    </p>
                    <div class="flex gap-2">
                        <span class="px-2.5 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-semibold">Est. 1968</span>
                        <span class="px-2.5 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-semibold">Grades 7–10</span>
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <p class="text-white text-xs font-bold uppercase tracking-widest mb-3">Quick Links</p>
                    <ul class="space-y-1.5">
                        <li><a href="#top" class="text-slate-400 hover:text-emerald-400 transition text-sm font-medium">Home</a></li>
                        <li><a href="#about" class="text-slate-400 hover:text-emerald-400 transition text-sm font-medium">About</a></li>
                        <li><a href="{{ route('admin.records') }}" class="text-slate-400 hover:text-emerald-400 transition text-sm font-medium">Records</a></li>
                        <li><a href="{{ route('admin.users') }}" class="text-slate-400 hover:text-emerald-400 transition text-sm font-medium">Users</a></li>
                    </ul>
                </div>

                <div class="sm:col-span-4">
                    <p class="text-white text-xs font-bold uppercase tracking-widest mb-3">Contact</p>
                    <p class="text-slate-400 text-sm leading-relaxed">San Quintin National High School</p>
                    <p class="text-slate-500 text-sm leading-relaxed mb-2">San Quintin, Pangasinan, Philippines</p>
                    <a href="#top" class="inline-flex items-center gap-1.5 text-emerald-400 hover:text-emerald-300 transition text-sm font-semibold">
                        Back to top
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="border-t border-white/10 relative">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 py-3 flex flex-col sm:flex-row justify-between items-center gap-1">
                <p class="text-slate-500 text-xs">&copy; {{ date('Y') }} San Quintin National High School. All rights reserved.</p>
                <p class="text-slate-600 text-xs">Built for the STE Program</p>
            </div>
        </div>
    </footer>
@endsection