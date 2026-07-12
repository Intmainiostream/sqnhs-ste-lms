@extends('layouts.guest')

@section('title', 'Register')

@section('content')
    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div x-data="{ accountType: '{{ old('account_type', 'parent') }}' }">
            <label class="block text-sm font-medium text-green-800 mb-2">Registering as</label>
            <div class="grid grid-cols-2 gap-3">
                <label class="flex items-center justify-center gap-2 border rounded-lg py-2 cursor-pointer transition"
                       :class="accountType === 'parent' ? 'border-green-600 bg-green-50' : 'border-gray-300'">
                    <input type="radio" name="account_type" value="parent" x-model="accountType"
                           class="text-green-700 focus:ring-green-500">
                    <span class="text-sm text-green-900">Parent</span>
                </label>
                <label class="flex items-center justify-center gap-2 border rounded-lg py-2 cursor-pointer transition"
                       :class="accountType === 'student' ? 'border-green-600 bg-green-50' : 'border-gray-300'">
                    <input type="radio" name="account_type" value="student" x-model="accountType"
                           class="text-green-700 focus:ring-green-500">
                    <span class="text-sm text-green-900">Student</span>
                </label>
            </div>
            <p class="text-xs text-gray-400 mt-1">This is just a label — the account created will always be your child's student account.</p>
        </div>

        <div>
            <label for="username" class="block text-sm font-medium text-green-800 mb-1">Username</label>
            <input type="text" name="username" id="username" value="{{ old('username') }}"
                   class="w-full rounded-lg border px-3 py-2 focus:outline-none focus:ring-2
                          {{ $errors->has('username') ? 'border-red-400 focus:ring-red-400' : 'border-gray-300 focus:ring-green-500' }}"
                   required autofocus>
            @error('username')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-green-800 mb-1">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}"
                   class="w-full rounded-lg border px-3 py-2 focus:outline-none focus:ring-2
                          {{ $errors->has('email') ? 'border-red-400 focus:ring-red-400' : 'border-gray-300 focus:ring-green-500' }}"
                   required>
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-green-800 mb-1">Password</label>
            <div class="relative">
                <input type="password" name="password" id="password"
                       class="w-full rounded-lg border px-3 py-2 pr-10 focus:outline-none focus:ring-2
                              {{ $errors->has('password') ? 'border-red-400 focus:ring-red-400' : 'border-gray-300 focus:ring-green-500' }}"
                       required>
                <button type="button" onclick="togglePassword('password', this)"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-green-700">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-green-800 mb-1">Confirm Password</label>
            <div class="relative">
                <input type="password" name="password_confirmation" id="password_confirmation"
                       class="w-full rounded-lg border border-gray-300 px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-green-500"
                       required>
                <button type="button" onclick="togglePassword('password_confirmation', this)"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-green-700">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>
        </div>

        <div>
            <label for="grade_level" class="block text-sm font-medium text-green-800 mb-1">Grade Level to Enroll</label>
            <select name="grade_level" id="grade_level"
                    class="w-full rounded-lg border px-3 py-2 focus:outline-none focus:ring-2
                           {{ $errors->has('grade_level') ? 'border-red-400 focus:ring-red-400' : 'border-gray-300 focus:ring-green-500' }}"
                    required>
                <option value="" disabled selected>Select grade level</option>
                <option value="7" {{ old('grade_level') == 7 ? 'selected' : '' }}>Grade 7</option>
                <option value="8" {{ old('grade_level') == 8 ? 'selected' : '' }}>Grade 8</option>
                <option value="9" {{ old('grade_level') == 9 ? 'selected' : '' }}>Grade 9</option>
                <option value="10" {{ old('grade_level') == 10 ? 'selected' : '' }}>Grade 10</option>
            </select>
            @error('grade_level')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="bg-green-50 border border-green-200 rounded-lg p-3 text-xs text-green-800">
            Already registered your child? Don't register again — just log in using the username and password you created for them.
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

    <script>
        function togglePassword(fieldId, btn) {
            const input = document.getElementById(fieldId);
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';

            btn.innerHTML = isHidden
                ? `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18M10.584 10.587a2 2 0 002.828 2.83M9.363 5.365A9.466 9.466 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-1.847 3.362M6.228 6.228A10.05 10.05 0 002.458 12c1.274 4.057 5.064 7 9.542 7a9.47 9.47 0 004.635-1.223" />
                   </svg>`
                : `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                   </svg>`;
        }
    </script>
@endsection