@extends('layouts.app')

@section('title', 'School Years')

@section('content')
<div x-data="{ showConfirm: false }" class="max-w-3xl mx-auto px-6 sm:px-10 lg:px-8 py-4 sm:py-6 lg:py-8">

    <div class="bg-gradient-to-br from-green-600 to-green-800 rounded-2xl shadow-lg px-4 sm:px-8 py-8 relative overflow-hidden">
        <svg class="absolute -right-8 -top-8 w-48 h-48 text-white/10 pointer-events-none" viewBox="0 0 64 64" fill="none">
            <circle cx="32" cy="32" r="3" fill="currentColor"/>
            <ellipse cx="32" cy="32" rx="28" ry="11" stroke="currentColor" stroke-width="2"/>
            <ellipse cx="32" cy="32" rx="28" ry="11" stroke="currentColor" stroke-width="2" transform="rotate(60 32 32)"/>
            <ellipse cx="32" cy="32" rx="28" ry="11" stroke="currentColor" stroke-width="2" transform="rotate(120 32 32)"/>
        </svg>
        <div class="relative">
            <h1 class="text-2xl sm:text-3xl font-bold text-white">School Years</h1>
            <p class="text-green-100 text-sm mt-1">Manage the active school year and student promotion</p>
        </div>
    </div>

    @if (session('error'))
        <div class="mt-4 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3">{{ session('error') }}</div>
    @endif

    {{-- Advance to next school year --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mt-4">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Advance School Year</h2>
                <p class="text-xs text-gray-400 mt-1">Creates the next school year, activates it, and promotes all students one grade level.</p>
            </div>
            <button type="button" @click="showConfirm = true"
                class="px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white text-sm font-medium transition whitespace-nowrap">
                Advance to Next School Year
            </button>
        </div>
    </div>

    {{-- List --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mt-4">
        @forelse($schoolYears as $sy)
            <div class="p-6 flex items-center justify-between {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                <div>
                    <p class="font-medium text-gray-900">{{ $sy->year_label }}</p>
                    @if($sy->is_active)
                        <span class="inline-block mt-1 text-xs font-medium text-green-700 bg-green-50 border border-green-200 rounded-full px-2 py-0.5">Active</span>
                    @else
                        <span class="inline-block mt-1 text-xs font-medium text-gray-400">Inactive</span>
                    @endif
                </div>
                @unless($sy->is_active)
                    <form method="POST" action="{{ route('admin.school-years.destroy', $sy) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 rounded-lg bg-white border border-red-200 hover:bg-red-50 text-red-600 text-xs font-medium transition">
                            Delete
                        </button>
                    </form>
                @endunless
            </div>
        @empty
            <div class="px-6 py-16 text-center text-gray-400 text-sm">No school years yet.</div>
        @endforelse
    </div>

    {{-- CONFIRM MODAL --}}
    <div x-show="showConfirm" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        style="background: rgba(0,0,0,0.45); backdrop-filter: blur(6px);">
        <div x-show="showConfirm"
            x-transition:enter="transition-all duration-200 ease-out"
            x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100"
            class="bg-white rounded-2xl shadow-2xl flex flex-col items-center text-center p-8"
            style="width: 400px;"
            @click.away="showConfirm = false">
            <div class="w-14 h-14 rounded-2xl bg-amber-50 flex items-center justify-center mb-4">
                <svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
            </div>
            <p class="text-sm font-semibold text-gray-800 mb-1">Advance School Year?</p>
            <p class="text-sm text-gray-500 mb-6 leading-snug">
                This will create the next school year and activate it. All Grade 7–9 students will be promoted one level, and Grade 10 students will be marked inactive.
            </p>
            <div class="flex gap-3 w-full">
                <button type="button" @click="showConfirm = false"
                    class="flex-1 px-6 py-2.5 text-sm font-semibold rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition-all">
                    Cancel
                </button>
                <form method="POST" action="{{ route('admin.school-years.next') }}" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full px-6 py-2.5 text-sm font-semibold rounded-xl bg-green-600 hover:bg-green-700 text-white transition-all">
                        Confirm
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

<style>[x-cloak] { display: none !important; }</style>
@endsection