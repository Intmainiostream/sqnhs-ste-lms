<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SQNHS STE LMS')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-green-50 min-h-screen relative">
    @include('partials.loading-screen')
    @include('partials.atoms-bg')

    <nav class="bg-green-700 text-white px-6 py-4 flex justify-between items-center shadow relative z-10">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/sqnhs-logo.png') }}" alt="Logo" class="h-8 w-8 object-contain">
            <span class="font-semibold">SQNHS STE LMS</span>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm hover:underline">Log out</button>
        </form>
    </nav>

    <main class="max-w-5xl mx-auto px-6 py-8 relative z-10">
        @if (session('success'))
            <div class="mb-4 p-3 rounded-lg bg-green-100 border border-green-300 text-green-800 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>