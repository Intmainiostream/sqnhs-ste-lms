<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SQNHS STE LMS')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50" x-data="{ mobileNavOpen: false }">

    @include('partials.loading-screen')
    @include('partials.success-modal')

    <header class="bg-white border-b border-gray-200 sticky top-0 z-30">
        <div class="px-4 sm:px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-8">
                <span class="font-semibold text-green-800 text-sm sm:text-base">SQNHS STE LMS</span>

                <nav class="hidden sm:flex items-center gap-6">
                    <a href="{{ route('student.dashboard') }}"
                       class="text-sm font-medium transition {{ request()->routeIs('student.dashboard') ? 'text-green-700' : 'text-gray-500 hover:text-green-700' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('student.grades') }}"
                       class="text-sm font-medium transition {{ request()->routeIs('student.grades') ? 'text-green-700' : 'text-gray-500 hover:text-green-700' }}">
                        My Grades
                    </a>
                </nav>
            </div>

            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-green-700 text-white flex items-center justify-center text-xs font-bold">
                    {{ strtoupper(substr(auth()->user()->username ?? 'S', 0, 2)) }}
                </div>
                <span class="text-sm text-gray-600 hidden sm:inline">{{ strtoupper(auth()->user()->username ?? 'Student') }}</span>

                <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
                    @csrf
                    <button type="submit" class="text-sm text-gray-500 hover:text-red-600 transition">
                        Logout
                    </button>
                </form>

                <button @click="mobileNavOpen = !mobileNavOpen" class="sm:hidden p-2 rounded-lg hover:bg-gray-100 transition">
                    <svg x-show="!mobileNavOpen" class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="mobileNavOpen" x-cloak class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <div x-show="mobileNavOpen" x-cloak
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="sm:hidden border-t border-gray-100 px-4 py-3 flex flex-col gap-1">
            <a href="{{ route('student.dashboard') }}"
               class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('student.dashboard') ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50' }}">
                Dashboard
            </a>
            <a href="{{ route('student.grades') }}"
               class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('student.grades') ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50' }}">
                My Grades
            </a>
            <form method="POST" action="{{ route('logout') }}" class="mt-1">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium text-red-600 hover:bg-red-50 transition">
                    Logout
                </button>
            </form>
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>
</html>