@extends('student.layouts.app')

@section('title', 'My Grades')

@section('content')

@php
    $rows = [];

    foreach ($subjects as $subject) {
        if ($subject->children->isEmpty()) {
            $grade = $grades->get($subject->id);
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
                'remarks'      => $grade->remarks ?? '',
            ];
        } else {
            $parentGrade = $grades->get($subject->id);
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
                'remarks'      => $parentGrade->remarks ?? '',
            ];

            foreach ($subject->children as $child) {
                $grade = $grades->get($child->id);
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

    get currentRows() {
        return this.rows.filter(r => r.grade_level === this.activeGrade);
    },
}" class="min-h-screen">

    <div class="max-w-5xl mx-auto px-6 sm:px-10 lg:px-8 py-4 sm:py-6 lg:py-8">

        {{-- HEADER --}}
        <div class="anim-fade bg-gradient-to-br from-green-600 to-green-800 rounded-2xl shadow-lg px-4 sm:px-8 py-8 relative overflow-hidden">
            <svg class="absolute -right-8 -top-8 w-48 h-48 text-white/10 pointer-events-none" viewBox="0 0 64 64" fill="none">
                <circle cx="32" cy="32" r="3" fill="currentColor"/>
                <ellipse cx="32" cy="32" rx="28" ry="11" stroke="currentColor" stroke-width="2"/>
                <ellipse cx="32" cy="32" rx="28" ry="11" stroke="currentColor" stroke-width="2" transform="rotate(60 32 32)"/>
                <ellipse cx="32" cy="32" rx="28" ry="11" stroke="currentColor" stroke-width="2" transform="rotate(120 32 32)"/>
            </svg>
            <div class="relative text-center">
                <h1 class="text-2xl sm:text-3xl font-bold text-white">My Grades</h1>
                <p class="text-green-100 text-sm mt-1">
                    @if(!empty($schoolYear->year_label))
                        {{ $schoolYear->year_label }}
                    @endif
                </p>
            </div>
        </div>

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

        <template x-if="currentRows.length === 0">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm px-6 py-16 text-center mt-4">
                <p class="text-gray-400 text-sm">No subjects set up for Grade <span x-text="activeGrade"></span> yet.</p>
            </div>
        </template>

        <div x-show="currentRows.length > 0" class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mt-4">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-left text-xs uppercase tracking-wide border-b border-gray-100">
                        <th class="px-6 py-3 font-medium">Learning Area</th>
                        <th class="px-3 py-3 font-medium text-center w-20">Term 1</th>
                        <th class="px-3 py-3 font-medium text-center w-20">Term 2</th>
                        <th class="px-3 py-3 font-medium text-center w-20">Term 3</th>
                        <th class="px-3 py-3 font-medium text-center w-24">Final</th>
                        <th class="px-6 py-3 font-medium">Remarks</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <template x-for="row in rows" :key="row.subject_id">
                        <tr x-show="row.grade_level === activeGrade" :class="row.has_children ? 'bg-gray-50/60' : 'hover:bg-green-50/30 transition-colors'">
                            <td :class="row.parent_id ? 'pl-12 pr-6 py-2' : 'px-6 py-3'">
                                <span :class="row.has_children ? 'font-medium text-gray-900' : (row.parent_id ? 'text-sm text-gray-600' : 'font-medium text-gray-900')"
                                    x-text="row.name"></span>
                            </td>
                            <td class="px-3 py-2 text-center" x-text="row.has_children ? '—' : (row.term1 ?? '—')"></td>
                            <td class="px-3 py-2 text-center" x-text="row.has_children ? '—' : (row.term2 ?? '—')"></td>
                            <td class="px-3 py-2 text-center" x-text="row.has_children ? '—' : (row.term3 ?? '—')"></td>
                            <td class="px-3 py-2 text-center font-medium" x-text="row.final_grade ?? '—'"></td>
                            <td class="px-6 py-2 text-gray-600" x-text="row.remarks || '—'"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

    </div>
</div>

<style>
    .anim-fade { animation: fadeIn 0.4s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endsection