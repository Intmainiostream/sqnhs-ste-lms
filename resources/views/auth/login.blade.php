@extends('layouts.guest')

@section('title', 'Login')

@section('content')
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label for="username" class="block text-sm font-medium text-green-800 mb-1">Username</label>
            <input type="text" name="username" id="username" value="{{ old('username') }}"
                   class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                   required autofocus>
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-green-800 mb-1">Password</label>
            <input type="password" name="password" id="password"
                   class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                   required>
        </div>

        <div class="flex items-center">
            <input type="checkbox" name="remember" id="remember"
                   class="rounded border-gray-300 text-green-700 focus:ring-green-500">
            <label for="remember" class="ml-2 text-sm text-gray-600">Remember me</label>
        </div>

        <button type="submit"
                class="w-full bg-green-700 hover:bg-green-800 text-white font-semibold py-2.5 rounded-lg transition">
            Log In
        </button>

        <p class="text-center text-sm text-gray-500 mt-4">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-green-700 font-medium hover:underline">Register</a>
        </p>
    </form>
@endsection