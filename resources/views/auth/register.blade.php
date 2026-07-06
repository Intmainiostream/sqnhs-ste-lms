@extends('layouts.guest')

@section('title', 'Register')

@section('content')
    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <label for="username" class="block text-sm font-medium text-green-800 mb-1">Username</label>
            <input type="text" name="username" id="username" value="{{ old('username') }}"
                   class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                   required autofocus>
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-green-800 mb-1">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}"
                   class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                   required>
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-green-800 mb-1">Password</label>
            <input type="password" name="password" id="password"
                   class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                   required>
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-green-800 mb-1">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation"
                   class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                   required>
        </div>

        <div>
            <label for="grade_level" class="block text-sm font-medium text-green-800 mb-1">Grade Level to Enroll</label>
            <select name="grade_level" id="grade_level"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                    required>
                <option value="" disabled selected>Select grade level</option>
                <option value="7" {{ old('grade_level') == 7 ? 'selected' : '' }}>Grade 7</option>
                <option value="8" {{ old('grade_level') == 8 ? 'selected' : '' }}>Grade 8</option>
                <option value="9" {{ old('grade_level') == 9 ? 'selected' : '' }}>Grade 9</option>
                <option value="10" {{ old('grade_level') == 10 ? 'selected' : '' }}>Grade 10</option>
            </select>
        </div>

        <div class="bg-green-50 border border-green-200 rounded-lg p-3 text-xs text-green-800">
            Already have an account for your child? Don't register again — just log in using the same username and password your child (or you) already created.
        </div>

        <button type="submit"
                class="w-full bg-green-700 hover:bg-green-800 text-white font-semibold py-2.5 rounded-lg transition">
            Register
        </button>

        <p class="text-center text-sm text-gray-500 mt-4">
            Already have an account?
            <a href="{{ route('login') }}" class="text-green-700 font-medium hover:underline">Log in</a>
        </p>
    </form>
@endsection