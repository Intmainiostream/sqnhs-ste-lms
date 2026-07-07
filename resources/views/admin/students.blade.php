@extends('layouts.app')

@section('title', 'Students')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Students</h1>
        <p class="text-gray-500 text-sm mt-1">All approved and enrolled students</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        @if ($students->isEmpty())
            <div class="px-6 py-16 text-center">
                <p class="text-gray-400 text-sm">No approved students yet.</p>
            </div>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-left text-xs uppercase tracking-wide">
                        <th class="px-6 py-3 font-medium">Student</th>
                        <th class="px-6 py-3 font-medium">Grade</th>
                        <th class="px-6 py-3 font-medium">Section</th>
                        <th class="px-6 py-3 font-medium">Username</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($students as $student)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $student->first_name }} {{ $student->last_name }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700">
                                    Grade {{ $student->grade_level }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $student->section ?? '—' }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $student->user->username }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection