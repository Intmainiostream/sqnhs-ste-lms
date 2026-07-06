@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <h1 class="text-xl font-bold text-green-800 mb-6">Pending Enrollments</h1>

    @if ($pendingStudents->isEmpty())
        <p class="text-gray-500 text-sm">No pending enrollments right now.</p>
    @else
        <div class="bg-white rounded-xl border border-green-100 shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-green-50 text-green-800 text-left">
                    <tr>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Grade</th>
                        <th class="px-4 py-3">Username</th>
                        <th class="px-4 py-3">Registered</th>
                        <th class="px-4 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pendingStudents as $student)
                        <tr class="border-t border-green-50">
                            <td class="px-4 py-3">
                                {{ $student->first_name ?? '—' }} {{ $student->last_name ?? '' }}
                            </td>
                            <td class="px-4 py-3">Grade {{ $student->grade_level }}</td>
                            <td class="px-4 py-3">{{ $student->user->username }}</td>
                            <td class="px-4 py-3">{{ $student->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-3 text-right space-x-2">
                                <form method="POST" action="{{ route('admin.students.approve', $student) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-700 font-medium hover:underline">Approve</button>
                                </form>
                                <form method="POST" action="{{ route('admin.students.reject', $student) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-red-600 font-medium hover:underline">Reject</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection