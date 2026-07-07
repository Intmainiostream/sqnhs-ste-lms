@extends('layouts.app')

@section('title', 'Pending Requests')

@section('content')
    <div class="max-w-6xl mx-auto px-6 py-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Pending Requests</h1>
            <p class="text-gray-500 text-sm mt-1">Review and approve or reject student enrollments</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            @if ($pendingStudents->isEmpty())
                <div class="px-6 py-16 text-center">
                    <svg class="mx-auto mb-3 text-gray-300" width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-gray-400 text-sm">No pending enrollments right now.</p>
                </div>
            @else
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-left text-xs uppercase tracking-wide">
                            <th class="px-6 py-3 font-medium">Student</th>
                            <th class="px-6 py-3 font-medium">Grade</th>
                            <th class="px-6 py-3 font-medium">Username</th>
                            <th class="px-6 py-3 font-medium">Registered</th>
                            <th class="px-6 py-3 font-medium text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($pendingStudents as $student)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-900">
                                        {{ $student->first_name ?? '—' }} {{ $student->last_name ?? '' }}
                                    </p>
                                    @if (!$student->first_name)
                                        <p class="text-xs text-gray-400">Enrollment form not yet submitted</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700">
                                        Grade {{ $student->grade_level }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $student->user->username }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $student->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <form method="POST" action="{{ route('admin.students.approve', $student) }}">
                                            @csrf
                                            <button type="submit"
                                                    class="px-3 py-1.5 rounded-lg bg-green-600 hover:bg-green-700 text-white text-xs font-medium transition">
                                                Approve
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.students.reject', $student) }}">
                                            @csrf
                                            <button type="submit"
                                                    class="px-3 py-1.5 rounded-lg border border-red-200 text-red-600 hover:bg-red-50 text-xs font-medium transition">
                                                Reject
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection