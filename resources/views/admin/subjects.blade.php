@extends('layouts.app')

@section('title', 'Manage Subjects')

@section('content')

@php
    $subjectsBySubjectId = [];
    foreach ([7, 8, 9, 10] as $g) {
        $subjectsBySubjectId[$g] = ($subjects[$g] ?? collect())->map(function ($s) {
            return [
                'id'          => $s->id,
                'name'        => $s->name,
                'is_gradable' => $s->is_gradable,
                'children'    => $s->children->map(fn ($c) => [
                    'id'   => $c->id,
                    'name' => $c->name,
                ])->values(),
            ];
        })->values();
    }
@endphp

<script>
    window.subjectsData = {!! json_encode($subjectsBySubjectId) !!};
</script>

<div x-data="{
    activeGrade: 7,
    subjects: window.subjectsData,
    newSubjectName: '',
    addingChildTo: null,
    newChildName: '',
    confirmingDelete: null,

    get currentSubjects() {
        return this.subjects[this.activeGrade] || [];
    },
}" class="min-h-screen">

    <div class="max-w-4xl mx-auto px-6 sm:px-10 lg:px-8 py-4 sm:py-6 lg:py-8">

        {{-- HEADER --}}
        <div class="anim-fade bg-gradient-to-br from-green-600 to-green-800 rounded-2xl shadow-lg px-4 sm:px-8 py-8 relative overflow-hidden">
            <svg class="absolute -right-8 -top-8 w-48 h-48 text-white/10 pointer-events-none" viewBox="0 0 64 64" fill="none">
                <circle cx="32" cy="32" r="3" fill="currentColor"/>
                <ellipse cx="32" cy="32" rx="28" ry="11" stroke="currentColor" stroke-width="2"/>
                <ellipse cx="32" cy="32" rx="28" ry="11" stroke="currentColor" stroke-width="2" transform="rotate(60 32 32)"/>
                <ellipse cx="32" cy="32" rx="28" ry="11" stroke="currentColor" stroke-width="2" transform="rotate(120 32 32)"/>
            </svg>
            <div class="relative">
                <h1 class="text-2xl sm:text-3xl font-bold text-white">Manage Subjects</h1>
                <p class="text-green-100 text-sm mt-1">Learning areas per grade level</p>
            </div>
        </div>

        @if (session('success'))
            <div class="mt-4 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        {{-- TABS --}}
        <div class="flex gap-2 mt-6">
            <template x-for="grade in [7, 8, 9, 10]" :key="grade">
                <button @click="activeGrade = grade"
                    :class="activeGrade === grade ? 'bg-green-600 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'"
                    class="px-5 py-2 rounded-lg text-sm font-medium transition-all duration-200">
                    Grade <span x-text="grade"></span>
                </button>
            </template>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mt-4">

            {{-- ADD SUBJECT --}}
            <form @submit.prevent="if (!newSubjectName.trim()) return;
                    $refs.addForm.submit();"
                x-ref="addForm"
                method="POST" action="{{ route('admin.subjects.store') }}"
                class="flex gap-2 px-6 py-4 border-b border-gray-100">
                @csrf
                <input type="hidden" name="grade_level" :value="activeGrade">
                <input type="text" name="name" x-model="newSubjectName" placeholder="Add a learning area (e.g. Mathematics)"
                    class="flex-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                <button type="submit"
                    class="px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white text-sm font-medium transition">
                    + Add
                </button>
            </form>

            {{-- LIST --}}
            <div class="divide-y divide-gray-100">
                <template x-if="currentSubjects.length === 0">
                    <div class="px-6 py-12 text-center">
                        <p class="text-gray-400 text-sm">No subjects yet for Grade <span x-text="activeGrade"></span>.</p>
                    </div>
                </template>

                <template x-for="subject in currentSubjects" :key="subject.id">
                    <div>
                        <div class="flex items-center justify-between px-6 py-3 hover:bg-gray-50/60 transition">
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-gray-900" x-text="subject.name"></span>
                                <span x-show="!subject.is_gradable"
                                    class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-500">grouped</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <button @click="addingChildTo = addingChildTo === subject.id ? null : subject.id; newChildName = ''"
                                    class="text-xs text-green-700 hover:underline font-medium">
                                    + Sub-subject
                                </button>
                                <button @click="confirmingDelete = subject"
                                    class="text-gray-400 hover:text-red-600 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- CHILDREN --}}
                        <div x-show="subject.children.length > 0" style="padding-left: 3rem;" class="pr-6 py-2 bg-gray-50/40">
                            <template x-for="child in subject.children" :key="child.id">
                                <div class="flex items-center gap-2 py-1">
                                    <span class="text-gray-300 text-xs">•</span>
                                    <span class="text-sm text-gray-600" x-text="child.name"></span>
                                    <button @click="confirmingDelete = child"
                                        class="text-gray-400 hover:text-red-600 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>

                        {{-- ADD CHILD ROW --}}
                        <template x-if="addingChildTo === subject.id">
                            <form method="POST" action="{{ route('admin.subjects.store') }}"
                                class="flex gap-2 pl-12 pr-6 py-2 bg-green-50/40">
                                @csrf
                                <input type="hidden" name="grade_level" :value="activeGrade">
                                <input type="hidden" name="parent_subject_id" :value="subject.id">
                                <input type="text" name="name" x-model="newChildName" placeholder="e.g. Music"
                                    class="flex-1 rounded-lg border border-gray-200 px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                                <button type="submit"
                                    class="px-3 py-1.5 rounded-lg bg-green-600 hover:bg-green-700 text-white text-xs font-medium transition">
                                    Add
                                </button>
                                <button type="button" @click="addingChildTo = null"
                                    class="px-3 py-1.5 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 text-xs font-medium transition">
                                    Cancel
                                </button>
                            </form>
                        </template>
                    </div>
                </template>
            </div>
        </div>
    </div>

    {{-- DELETE CONFIRM MODAL --}}
    <div x-show="confirmingDelete" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center px-4"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100">
        <div class="absolute inset-0 bg-black/40" @click="confirmingDelete = null"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm p-6"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100">
            <h3 class="text-lg font-semibold text-gray-900">Remove this subject?</h3>
            <p class="text-sm text-gray-500 mt-1.5">
                <span x-text="confirmingDelete ? confirmingDelete.name : ''"></span> and any grades recorded under it will be deleted. This can't be undone.
            </p>
            <div class="flex justify-end gap-2 mt-6">
                <button @click="confirmingDelete = null"
                    class="px-4 py-2 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 text-sm font-medium transition">
                    Cancel
                </button>
                <form method="POST" :action="confirmingDelete ? '{{ url('admin/subjects') }}/' + confirmingDelete.id : ''">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-medium transition">
                        Yes, Remove
                    </button>
                </form>
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