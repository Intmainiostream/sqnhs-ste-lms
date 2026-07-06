<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SQNHS STE LMS')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-green-50 min-h-screen py-10 relative">
    @include('partials.loading-screen')
    @include('partials.atoms-bg')

    <div class="w-full max-w-3xl mx-auto px-4 relative z-10">
        <div class="flex justify-center mb-6">
            <img src="{{ asset('images/sqnhs-logo.png') }}" alt="SQNHS Logo" class="h-16 w-16 object-contain">
        </div>

        <div class="bg-white shadow-md rounded-xl border border-green-100 p-8">
            <h1 class="text-center text-green-800 font-bold text-xl mb-1">
                San Quintin National High School
            </h1>
            <p class="text-center text-green-600 text-sm mb-6">
                STE Enrollment &amp; Learning Management System
            </p>

            @if ($errors->any())
                <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>

        <p class="text-center text-green-400 text-xs mt-6 pb-6">
            &copy; {{ date('Y') }} SQNHS. All rights reserved.
        </p>
    </div>
</body>
</html>