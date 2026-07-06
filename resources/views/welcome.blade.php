<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SQNHS STE LMS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-gray-800 relative overflow-hidden">
    @include('partials.loading-screen')
    @include('partials.atoms-bg')

    <nav class="border-b border-green-100 px-6 py-4 flex justify-between items-center relative z-10 bg-white">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/sqnhs-logo.png') }}" alt="SQNHS Logo" class="h-9 w-9 object-contain">
            <span class="font-semibold text-green-800">SQNHS STE LMS</span>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('login') }}" class="text-sm font-medium text-green-800 hover:underline">Log in</a>
            <a href="{{ route('register') }}"
               class="text-sm font-medium bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded-lg transition">
                Register
            </a>
        </div>
    </nav>

    <section class="max-w-3xl mx-auto text-center px-6 pt-20 pb-16">
        <p class="text-green-700 font-semibold text-sm tracking-wide uppercase mb-3">
            Science, Technology &amp; Engineering Program
        </p>
        <h1 class="text-4xl sm:text-5xl font-bold text-green-900 leading-tight mb-5">
            San Quintin National High School
        </h1>
        <p class="text-gray-500 text-lg max-w-xl mx-auto mb-10">
            Enrollment and learning, in one place — for STE students, parents, teachers, and admin.
        </p>
        <div class="flex justify-center gap-4">
            <a href="{{ route('register') }}"
               class="bg-green-700 hover:bg-green-800 text-white font-semibold px-6 py-3 rounded-lg transition">
                Enroll Now
            </a>
            <a href="{{ route('login') }}"
               class="border border-green-700 text-green-800 font-semibold px-6 py-3 rounded-lg hover:bg-green-50 transition">
                Log In
            </a>
        </div>
    </section>

    <section class="bg-green-50 border-y border-green-100 py-16">
        <div class="max-w-4xl mx-auto px-6">
            <h2 class="text-center text-green-900 font-bold text-2xl mb-10">How enrollment works</h2>

            <div class="grid sm:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="w-10 h-10 mx-auto mb-3 rounded-full bg-green-700 text-white flex items-center justify-center font-bold">1</div>
                    <p class="font-semibold text-green-900 text-sm mb-1">Register</p>
                    <p class="text-gray-500 text-xs">Parent or student creates the student's account.</p>
                </div>
                <div class="text-center">
                    <div class="w-10 h-10 mx-auto mb-3 rounded-full bg-green-700 text-white flex items-center justify-center font-bold">2</div>
                    <p class="font-semibold text-green-900 text-sm mb-1">Fill out enrollment</p>
                    <p class="text-gray-500 text-xs">Grade 7 entrants complete the student &amp; parent form.</p>
                </div>
                <div class="text-center">
                    <div class="w-10 h-10 mx-auto mb-3 rounded-full bg-green-700 text-white flex items-center justify-center font-bold">3</div>
                    <p class="font-semibold text-green-900 text-sm mb-1">Wait for approval</p>
                    <p class="text-gray-500 text-xs">The school admin reviews and approves the enrollment.</p>
                </div>
                <div class="text-center">
                    <div class="w-10 h-10 mx-auto mb-3 rounded-full bg-green-700 text-white flex items-center justify-center font-bold">4</div>
                    <p class="font-semibold text-green-900 text-sm mb-1">Access the LMS</p>
                    <p class="text-gray-500 text-xs">Log in to view classes, grades, and school records.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="text-center text-gray-400 text-xs py-8">
        &copy; {{ date('Y') }} San Quintin National High School. All rights reserved.
    </footer>

</body>
</html>