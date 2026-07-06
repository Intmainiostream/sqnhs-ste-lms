@extends('layouts.guest')

@section('title', 'Enrollment Form')

@section('content')
    <form method="POST" action="{{ route('enroll.store') }}" class="space-y-4">
        @csrf

        <p class="text-sm text-gray-500 mb-2">
            Grade Level: <span class="font-semibold text-green-800">Grade {{ $student->grade_level }}</span>
        </p>

        <div>
            <label for="first_name" class="block text-sm font-medium text-green-800 mb-1">First Name</label>
            <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}"
                   class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                   required autofocus>
        </div>

        <div>
            <label for="last_name" class="block text-sm font-medium text-green-800 mb-1">Last Name</label>
            <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}"
                   class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                   required>
        </div>

        <div>
            <label for="middle_name" class="block text-sm font-medium text-green-800 mb-1">Middle Name</label>
            <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name') }}"
                   class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
        </div>

        <div>
            <label for="birthdate" class="block text-sm font-medium text-green-800 mb-1">Birthdate</label>
            <input type="date" name="birthdate" id="birthdate" value="{{ old('birthdate') }}"
                   class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                   required>
        </div>

        <div>
            <label for="address" class="block text-sm font-medium text-green-800 mb-1">Address</label>
            <input type="text" name="address" id="address" value="{{ old('address') }}"
                   class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                   required>
        </div>

        <hr class="border-green-100 my-4">

        <p class="text-sm font-semibold text-green-800">Parent / Guardian Information</p>

        <div>
            <label for="parent_name" class="block text-sm font-medium text-green-800 mb-1">Parent / Guardian Name</label>
            <input type="text" name="parent_name" id="parent_name" value="{{ old('parent_name') }}"
                   class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                   required>
        </div>

        <div>
            <label for="parent_contact" class="block text-sm font-medium text-green-800 mb-1">Contact Number</label>
            <input type="text" name="parent_contact" id="parent_contact" value="{{ old('parent_contact') }}"
                   class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                   required>
        </div>

        <div>
            <label for="parent_relationship" class="block text-sm font-medium text-green-800 mb-1">Relationship to Student</label>
            <select name="parent_relationship" id="parent_relationship"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                    required>
                <option value="" disabled {{ old('parent_relationship') ? '' : 'selected' }}>Select relationship</option>
                <option value="Father" {{ old('parent_relationship') == 'Father' ? 'selected' : '' }}>Father</option>
                <option value="Mother" {{ old('parent_relationship') == 'Mother' ? 'selected' : '' }}>Mother</option>
                <option value="Guardian" {{ old('parent_relationship') == 'Guardian' ? 'selected' : '' }}>Guardian</option>
            </select>
        </div>

        <button type="submit"
                class="w-full bg-green-700 hover:bg-green-800 text-white font-semibold py-2.5 rounded-lg transition">
            Submit Enrollment
        </button>
    </form>
@endsection