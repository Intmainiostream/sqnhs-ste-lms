<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SQNHS STE ENROLLMENT SYSTEM')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50" x-data="{ sidebarOpen: false }" @keydown.escape.window="sidebarOpen = false">

    @include('partials.loading-screen')
    @include('partials.success-modal')

    @include('admin.partials.sidebar', [
        'pendingCount' => \App\Models\Student::where('enrollment_status', 'pending')->count(),
        'accountRequestCount' => \App\Models\AccountChangeRequest::where('status', 'pending')->count(),
    ])

    <header class="bg-white border-b border-gray-200 px-4 sm:px-6 py-4 flex items-center justify-between sticky top-0 z-30">
        <div class="flex items-center gap-3">
            <button @click="sidebarOpen = true" class="p-2 rounded-lg hover:bg-gray-100 transition">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <span class="font-semibold text-green-800 text-sm sm:text-base">SQNHS STE ENROLLMENT SYSTEM</span>
        </div>
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-green-700 text-white flex items-center justify-center text-xs font-bold">
                @if(auth()->user()->role !== 'admin' && auth()->user()->fname && auth()->user()->lname)
                    {{ strtoupper(substr(auth()->user()->fname, 0, 1) . substr(auth()->user()->lname, 0, 1)) }}
                @else
                    {{ strtoupper(substr(auth()->user()->username ?? 'A', 0, 2)) }}
                @endif
            </div>
            <span class="text-sm text-gray-600 hidden sm:inline">
                @if(in_array(auth()->user()->role, ['admin', 'parent', 'student']))
                    {{ strtoupper(auth()->user()->username ?? 'Admin') }}
                @else
                    {{ auth()->user()->username ?? 'Admin' }}
                @endif
            </span>
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>
</html>