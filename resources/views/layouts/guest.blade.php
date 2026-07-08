<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SQNHS STE LMS')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-green-50 min-h-screen flex items-center justify-center py-10 relative overflow-x-hidden overflow-y-auto">
    @include('partials.loading-screen')
    @include('partials.atoms-bg')

    <div class="w-full max-w-md relative z-10">
        <div class="flex justify-center mb-6">
            <img src="{{ asset('images/sqnhs-logo.png') }}" alt="SQNHS Logo" class="h-20 w-20 object-contain">
        </div>

        <div class="bg-white shadow-md rounded-xl border border-green-100 p-8">
            <h1 class="text-center text-green-800 font-bold text-xl mb-1">
                San Quintin National High School
            </h1>
            <p class="text-center text-green-600 text-sm mb-6">
                STE Enrollment & Learning Management System
            </p>

            @yield('content')
        </div>

        <p class="text-center text-green-400 text-xs mt-6">
            &copy; {{ date('Y') }} SQNHS. All rights reserved.
        </p>
    </div>
</body>
</html>