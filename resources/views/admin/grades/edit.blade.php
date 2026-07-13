@extends('layouts.app')

@section('title', 'Edit Grades')

@section('content')

@php
    $rows = [];

    foreach ($subjects as $subject) {
        if ($subject->children->isEmpty()) {
            $grade = $existingGrades->get($subject->id);
            $rows[] = [
                'subject_id'   => $subject->id,
                'parent_id'    => null,
                'grade_level'  => $subject->grade_level,
                'has_children' => false,
                'name'         => $subject->name,
                'term1'        => $grade->term1 ?? null,
                'term2'        => $grade->term2 ?? null,
                'term3'        => $grade->term3 ?? null,
                'final_grade'  => $grade->final_grade ?? null,
                'is_override'  => $grade->is_override ?? false,
                'remarks'      => $grade->remarks ?? '',
            ];
        } else {
            $parentGrade = $existingGrades->get($subject->id);
            $rows[] = [
                'subject_id'   => $subject->id,
                'parent_id'    => null,
                'grade_level'  => $subject->grade_level,
                'has_children' => true,
                'name'         => $subject->name,
                'term1'        => null,
                'term2'        => null,
                'term3'        => null,
                'final_grade'  => $parentGrade->final_grade ?? null,
                'is_override'  => $parentGrade->is_override ?? false,
                'remarks'      => $parentGrade->remarks ?? '',
            ];

            foreach ($subject->children as $child) {
                $grade = $existingGrades->get($child->id);
                $rows[] = [
                    'subject_id'   => $child->id,
                    'parent_id'    => $subject->id,
                    'grade_level'  => $subject->grade_level,
                    'has_children' => false,
                    'name'         => $child->name,
                    'term1'        => $grade->term1 ?? null,
                    'term2'        => $grade->term2 ?? null,
                    'term3'        => $grade->term3 ?? null,
                    'final_grade'  => $grade->final_grade ?? null,
                    'is_override'  => $grade->is_override ?? false,
                    'remarks'      => $grade->remarks ?? '',
                ];
            }
        }
    }
@endphp

<script>
    window.gradesData = {!! json_encode($rows) !!};
</script>

<div x-data="{
    rows: window.gradesData,
    activeGrade: {{ (int) $student->grade_level }},
    studentGradeLevel: {{ (int) $student->grade_level }},

    initFinals() {
        this.rows.forEach(r => {
            if (!r.has_children && !r.is_override) {
                r.final_grade = this.average(r);
            }
        });
        this.rows.forEach(r => {
            if (r.has_children && !r.is_override) {
                r.final_grade = this.groupAverage(r.subject_id);
            }
        });
    },

    get currentRows() {
        return this.rows.filter(r => r.grade_level === this.activeGrade);
    },

    average(row) {
        const terms = [row.term1, row.term2, row.term3].filter(t => t !== null && t !== '' && !isNaN(t));
        if (terms.length === 0) return null;
        const sum = terms.reduce((a, b) => a + parseFloat(b), 0);
        return Math.round((sum / terms.length) * 100) / 100;
    },

    groupAverage(parentId) {
        const finals = this.rows
            .filter(r => r.parent_id === parentId)
            .map(r => r.final_grade)
            .filter(f => f !== null && f !== '' && !isNaN(f));
        if (finals.length === 0) return null;
        const sum = finals.reduce((a, b) => a + parseFloat(b), 0);
        return Math.round((sum / finals.length) * 100) / 100;
    },

    recalcParent(parentId) {
        if (!parentId) return;
        const parent = this.rows.find(r => r.subject_id === parentId);
        if (parent && !parent.is_override) {
            parent.final_grade = this.groupAverage(parentId);
        }
    },

    onInput(row) {
        if (!row.is_override) {
            row.final_grade = this.average(row);
        }
        this.recalcParent(row.parent_id);
    },

    toggleOverride(row) {
        if (!row.is_override) {
            row.final_grade = row.has_children ? this.groupAverage(row.subject_id) : this.average(row);
        }
    },

    remarksFor(row) {
        const g = parseFloat(row.final_grade);
        if (isNaN(g)) return '';
        if (g >= 90) return 'Outstanding';
        if (g >= 85) return 'Very Satisfactory';
        if (g >= 80) return 'Satisfactory';
        if (g >= 75) return 'Fairly Satisfactory';
        return 'Failed';
    },

    remarksClass(row) {
        const g = parseFloat(row.final_grade);
        if (isNaN(g)) return 'text-gray-300';
        return g >= 75 ? 'text-green-700' : 'text-red-600';
    },
}" x-init="initFinals()" class="min-h-screen">

    <div class="max-w-5xl mx-auto px-6 sm:px-10 lg:px-8 py-4 sm:py-6 lg:py-8">

        {{-- HEADER --}}
        <div class="anim-fade bg-gradient-to-br from-green-600 to-green-800 rounded-2xl shadow-lg px-4 sm:px-8 py-8 relative overflow-hidden">
            <svg class="absolute -right-8 -top-8 w-48 h-48 text-white/10 pointer-events-none" viewBox="0 0 64 64" fill="none">
                <circle cx="32" cy="32" r="3" fill="currentColor"/>
                <ellipse cx="32" cy="32" rx="28" ry="11" stroke="currentColor" stroke-width="2"/>
                <ellipse cx="32" cy="32" rx="28" ry="11" stroke="currentColor" stroke-width="2" transform="rotate(60 32 32)"/>
                <ellipse cx="32" cy="32" rx="28" ry="11" stroke="currentColor" stroke-width="2" transform="rotate(120 32 32)"/>
            </svg>
            <div class="relative flex items-center">
                <a href="{{ url()->previous() }}"
                    class="absolute left-0 flex items-center justify-center w-11 h-11 rounded-xl bg-white/10 hover:bg-white/20 transition-all duration-200 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="white" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div class="w-full text-center">
                    <h1 class="text-2xl sm:text-3xl font-bold text-white">{{ $student->first_name }} {{ $student->last_name }}</h1>
                    <p class="text-green-100 text-sm mt-1">
                        Grade {{ $student->grade_level }}
                        @if(!empty($schoolYear->name ?? $schoolYear->label ?? null))
                            · {{ $schoolYear->name ?? $schoolYear->label }}
                        @endif
                    </p>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="mt-4 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex gap-2 mt-6 items-end">
            <template x-for="grade in [7, 8, 9, 10]" :key="grade">
                <div class="flex flex-col items-center gap-1">
                    <span class="text-[10px] whitespace-nowrap px-2 py-0.5 rounded-full font-semibold"
                        :style="grade === studentGradeLevel
                            ? 'background-color:#facc15; color:#713f12;'
                            : 'visibility:hidden; background-color:transparent;'">
                        current
                    </span>
                    <button type="button" @click="activeGrade = grade"
                        :class="activeGrade === grade ? 'bg-green-600 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'"
                        class="px-5 py-2 rounded-lg text-sm font-medium transition-all duration-200">
                        Grade <span x-text="grade"></span>
                    </button>
                </div>
            </template>
        </div>

        <form method="POST" action="{{ route('admin.students.grades.update', $student) }}" class="mt-4">
            @csrf
            @method('PUT')

            <template x-if="currentRows.length === 0">
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm px-6 py-16 text-center">
                    <p class="text-gray-400 text-sm">No subjects set up for Grade <span x-text="activeGrade"></span> yet.</p>
                    <a href="{{ route('admin.subjects') }}" class="inline-block mt-3 text-green-700 text-sm font-medium hover:underline">
                        Manage Subjects →
                    </a>
                </div>
            </template>

            <div x-show="currentRows.length > 0" class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[720px]">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-left text-xs uppercase tracking-wide border-b border-gray-100">
                            <th class="px-6 py-3 font-medium">Learning Area</th>
                            <th class="px-3 py-3 font-medium text-center w-20">Term 1</th>
                            <th class="px-3 py-3 font-medium text-center w-20">Term 2</th>
                            <th class="px-3 py-3 font-medium text-center w-20">Term 3</th>
                            <th class="px-3 py-3 font-medium text-center w-24">Final</th>
                            <th class="px-3 py-3 font-medium text-center w-20">Override</th>
                            <th class="px-6 py-3 font-medium">Remarks</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <template x-for="row in rows" :key="row.subject_id">
                            <tr x-show="row.grade_level === activeGrade" :class="row.has_children ? 'bg-gray-50/60' : 'hover:bg-green-50/30 transition-colors'">
                                <td :class="row.parent_id ? 'pl-12 pr-6 py-2' : 'px-6 py-3'">
                                        <span :class="row.has_children ? 'font-medium text-gray-900' : (row.parent_id ? 'text-sm text-gray-600' : 'font-medium text-gray-900')"
                                            x-text="row.name"></span>
                                        <input type="hidden" :name="'grades[' + row.subject_id + '][subject_id]'" :value="row.subject_id">
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        <template x-if="!row.has_children">
                                            <input type="number" step="0.01" min="60" max="100"
                                                x-model="row.term1" @input="onInput(row)"
                                                :name="'grades[' + row.subject_id + '][term1]'"
                                                class="w-full text-center rounded-lg border border-gray-200 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                                        </template>
                                        <span x-show="row.has_children" class="flex items-center justify-center h-[34px] text-gray-300">—</span>
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        <template x-if="!row.has_children">
                                            <input type="number" step="0.01" min="60" max="100"
                                                x-model="row.term2" @input="onInput(row)"
                                                :name="'grades[' + row.subject_id + '][term2]'"
                                                class="w-full text-center rounded-lg border border-gray-200 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                                        </template>
                                        <span x-show="row.has_children" class="flex items-center justify-center h-[34px] text-gray-300">—</span>
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        <template x-if="!row.has_children">
                                            <input type="number" step="0.01" min="60" max="100"
                                                x-model="row.term3" @input="onInput(row)"
                                                :name="'grades[' + row.subject_id + '][term3]'"
                                                class="w-full text-center rounded-lg border border-gray-200 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                                        </template>
                                        <span x-show="row.has_children" class="flex items-center justify-center h-[34px] text-gray-300">—</span>
                                    </td>
                                    <td class="px-3 py-2">
                                        <input type="number" step="0.01" min="60" max="100"
                                            x-model="row.final_grade" :readonly="!row.is_override"
                                            :name="'grades[' + row.subject_id + '][final_grade]'"
                                            :class="row.is_override ? 'bg-white' : 'bg-gray-50 text-gray-500'"
                                            class="w-full text-center rounded-lg border border-gray-200 py-1.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-green-500">
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        <input type="checkbox" x-model="row.is_override" @change="toggleOverride(row)"
                                            :name="'grades[' + row.subject_id + '][is_override]'" value="1"
                                            class="w-4 h-4 rounded border-gray-300 text-green-600 focus:ring-0">
                                    </td>
                                    <td class="px-6 py-2">
                                        <span class="text-sm font-medium" :class="remarksClass(row)" x-text="remarksFor(row) || '—'"></span>
                                        <input type="hidden" :name="'grades[' + row.subject_id + '][remarks]'" :value="remarksFor(row)">
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                </table>
                </div>

                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end">
                    <button type="submit"
                        class="px-5 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white text-sm font-medium transition">
                        Save Grades
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>

<style>
    .anim-fade { animation: fadeIn 0.4s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
    [x-cloak] { display: none !important; }
</style>
@endsection