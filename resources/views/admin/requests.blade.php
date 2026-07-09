@extends('layouts.app')

@section('title', 'Pending Requests')

@section('content')

@php
    $pendingData = $pendingStudents->map(function ($s) {
        return [
            'id'          => $s->id,
            'first_name'  => $s->first_name,
            'last_name'   => $s->last_name,
            'grade_level' => $s->grade_level,
            'username'    => $s->user->username,
            'created_at'  => $s->created_at->format('M d, Y'),
            'approve_url' => route('admin.students.approve', $s),
            'reject_url'  => route('admin.students.reject', $s),
        ];
    })->values();
@endphp

<script>
    window.pendingData = {!! $pendingData->toJson() !!};
</script>

<div x-data="{
    students: window.pendingData,
    searchQuery: '',
    selectedGrades: [],
    currentPage: 1,
    perPage: 10,
    showFilters: false,

    get filteredStudents() {
        let result = [...this.students];
        if (this.searchQuery.trim()) {
            const q = this.searchQuery.toLowerCase().trim();
            result = result.filter(s =>
                s.username.toLowerCase().includes(q) ||
                (s.first_name && s.first_name.toLowerCase().includes(q)) ||
                (s.last_name && s.last_name.toLowerCase().includes(q))
            );
        }
        if (this.selectedGrades.length > 0) {
            result = result.filter(s => this.selectedGrades.includes(s.grade_level));
        }
        return result;
    },
    get totalPages() { return Math.max(1, Math.ceil(this.filteredStudents.length / this.perPage)); },
    get pagedStudents() {
        const start = (this.currentPage - 1) * this.perPage;
        return this.filteredStudents.slice(start, start + this.perPage);
    },
    init() {
        this.$watch('searchQuery', () => this.currentPage = 1);
        this.$watch('selectedGrades', () => this.currentPage = 1);
    },

    displayName(s) {
        return (s.first_name || s.last_name) ? `${s.first_name ?? ''} ${s.last_name ?? ''}`.trim() : null;
    },
}" class="min-h-screen">

    <div class="max-w-6xl mx-auto px-6 sm:px-10 lg:px-8 py-4 sm:py-6 lg:py-8">

        {{-- HEADER BAR --}}
        <div class="anim-fade bg-gradient-to-br from-green-600 to-green-800 rounded-2xl shadow-lg px-4 sm:px-8 py-8 relative overflow-hidden">
            <svg class="absolute -right-8 -top-8 w-48 h-48 text-white/10 pointer-events-none" viewBox="0 0 64 64" fill="none">
                <circle cx="32" cy="32" r="3" fill="currentColor"/>
                <ellipse cx="32" cy="32" rx="28" ry="11" stroke="currentColor" stroke-width="2"/>
                <ellipse cx="32" cy="32" rx="28" ry="11" stroke="currentColor" stroke-width="2" transform="rotate(60 32 32)"/>
                <ellipse cx="32" cy="32" rx="28" ry="11" stroke="currentColor" stroke-width="2" transform="rotate(120 32 32)"/>
            </svg>
            <div class="relative">
                <h1 class="text-2xl sm:text-3xl font-bold text-white">Pending Requests</h1>
                <p class="text-green-100 text-sm mt-1">Review and approve or reject student enrollments</p>
            </div>

            <div class="relative grid grid-cols-2 sm:grid-cols-4 gap-3 mt-6">
                <template x-for="grade in [7, 8, 9, 10]" :key="grade">
                    <button @click="selectedGrades = selectedGrades.includes(grade) ? selectedGrades.filter(g => g !== grade) : [grade]"
                        class="bg-white/10 backdrop-blur rounded-xl p-4 text-left hover:bg-white/20 transition-all duration-200 border"
                        :class="selectedGrades.includes(grade) ? 'border-white/60' : 'border-white/0'">
                        <p class="text-2xl font-bold text-white" x-text="students.filter(s => s.grade_level === grade).length"></p>
                        <p class="text-green-100 text-xs uppercase tracking-wide mt-1">Grade <span x-text="grade"></span></p>
                    </button>
                </template>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden mt-6">

            {{-- CONTROLS --}}
            <div class="flex flex-col sm:flex-row sm:items-center gap-3 px-6 py-4 border-b border-gray-100">
                <div class="relative flex-1 sm:max-w-xs group">
                    <input type="text" x-model="searchQuery" placeholder="Search students..."
                        autocomplete="off"
                        class="w-full pl-9 pr-8 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all">
                    <svg class="absolute left-2.5 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <button x-show="searchQuery.length > 0" @click="searchQuery = ''" class="absolute right-2.5 top-2.5 text-gray-400 hover:text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="relative">
                    <button @click="showFilters = !showFilters"
                        class="py-2 px-3.5 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition flex items-center gap-1.5 text-sm text-gray-600 relative"
                        :class="{ 'bg-green-50 border-green-300 text-green-700': showFilters || selectedGrades.length > 0 }">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Filter
                        <span x-show="selectedGrades.length > 0"
                            class="absolute -top-1.5 -right-1.5 w-4 h-4 bg-green-600 text-white text-[10px] font-bold rounded-full flex items-center justify-center border-2 border-white"
                            x-text="selectedGrades.length"></span>
                    </button>

                    <div x-show="showFilters" @click.away="showFilters = false"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        class="absolute left-0 mt-2 w-40 bg-white rounded-lg shadow-lg border border-gray-100 z-30 overflow-hidden"
                        style="display: none;">
                        <div class="p-2.5 space-y-0.5">
                            <template x-for="grade in [7, 8, 9, 10]" :key="grade">
                                <label class="flex items-center gap-2.5 px-2 py-1.5 rounded-md hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" :value="grade" x-model="selectedGrades" class="w-3.5 h-3.5 rounded border-gray-300 text-green-600 focus:ring-0">
                                    <span class="text-sm text-gray-700">Grade <span x-text="grade"></span></span>
                                </label>
                            </template>
                        </div>
                        <div class="px-2.5 py-1.5 border-t border-gray-100">
                            <button @click="selectedGrades = []" class="text-xs text-gray-400 hover:text-gray-600">Clear</button>
                        </div>
                    </div>
                </div>

                <div x-show="selectedGrades.length > 0" class="flex flex-wrap items-center gap-1.5">
                    <template x-for="grade in selectedGrades" :key="grade">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-100">
                            Grade <span x-text="grade"></span>
                            <button @click="selectedGrades = selectedGrades.filter(g => g !== grade)" class="ml-1 hover:text-green-900">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </span>
                    </template>
                </div>
            </div>

            {{-- TABLE --}}
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-left text-xs uppercase tracking-wide border-b border-gray-100">
                        <th class="px-4 sm:px-8 py-3 font-medium">Student</th>
                        <th class="px-4 py-3 font-medium">Grade</th>
                        <th class="px-4 py-3 font-medium">Username</th>
                        <th class="px-4 py-3 font-medium">Registered</th>
                        <th class="px-4 sm:px-8 py-3 font-medium text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <template x-for="student in pagedStudents" :key="student.id">
                        <tr class="hover:bg-green-50/40 transition-colors duration-150">
                            <td class="px-4 sm:px-8 py-3">
                                <p class="font-medium text-gray-900" x-text="displayName(student) ?? '—'"></p>
                                <p class="text-xs text-gray-400" x-show="!displayName(student)">Enrollment form not yet submitted</p>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700">
                                    Grade <span x-text="student.grade_level"></span>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600" x-text="student.username"></td>
                            <td class="px-4 py-3 text-gray-500" x-text="student.created_at"></td>
                            <td class="px-4 sm:px-8 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <form method="POST" :action="student.approve_url">
                                        @csrf
                                        <button type="submit"
                                                class="px-3 py-1.5 rounded-lg bg-green-600 hover:bg-green-700 text-white text-xs font-medium transition">
                                            Approve
                                        </button>
                                    </form>
                                    <form method="POST" :action="student.reject_url">
                                        @csrf
                                        <button type="submit"
                                                class="px-3 py-1.5 rounded-lg border border-red-200 text-red-600 hover:bg-red-50 text-xs font-medium transition">
                                            Reject
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="filteredStudents.length === 0">
                        <td colspan="5" class="px-8 py-16 text-center">
                            <svg class="mx-auto mb-3 text-gray-300" width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-gray-400 text-sm">No pending enrollments right now.</p>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div x-show="filteredStudents.length > 0" class="px-4 sm:px-8 py-4 border-t border-gray-100 bg-gray-50 flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-sm text-gray-500">
                    Showing
                    <span class="font-medium" x-text="Math.min((currentPage - 1) * perPage + 1, filteredStudents.length)"></span>–<span class="font-medium" x-text="Math.min(currentPage * perPage, filteredStudents.length)"></span>
                    of <span class="font-medium" x-text="filteredStudents.length"></span> students
                </p>
                <div x-show="totalPages > 1" class="flex items-center gap-2">
                    <button @click="currentPage > 1 && currentPage--" :disabled="currentPage === 1"
                        class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 hover:bg-green-50 hover:text-green-700 hover:border-green-200 transition-all duration-200 disabled:opacity-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <template x-for="page in Array.from({length: totalPages}, (_, i) => i + 1)" :key="page">
                        <button @click="currentPage = page"
                            :class="currentPage === page ? 'bg-green-600 text-white hover:bg-green-700' : 'border border-gray-200 bg-white text-gray-600 hover:bg-green-50 hover:text-green-700 hover:border-green-200'"
                            class="w-8 h-8 flex items-center justify-center rounded-lg text-sm font-medium transition-all duration-200"
                            x-text="page"></button>
                    </template>
                    <button @click="currentPage < totalPages && currentPage++" :disabled="currentPage === totalPages"
                        class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 hover:bg-green-50 hover:text-green-700 hover:border-green-200 transition-all duration-200 disabled:opacity-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    .anim-fade { animation: fadeIn 0.4s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
    [x-cloak] { display: none !important; }
</style>
@endsection