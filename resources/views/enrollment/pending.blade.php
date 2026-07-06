@extends('layouts.guest')

@section('title', 'Pending Approval')

@section('content')
    <div class="text-center py-6">
        <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-green-100 flex items-center justify-center">
            <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="text-green-700">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <p class="font-semibold text-green-800 text-lg mb-1">Enrollment Submitted</p>
        <p class="text-sm text-gray-500">
            Your enrollment is now pending admin approval. You'll be able to log in once your account is activated.
        </p>

        <form method="POST" action="{{ route('logout') }}" class="mt-6">
            @csrf
            <button type="submit" class="text-sm text-green-700 font-medium hover:underline">
                Log out
            </button>
        </form>
    </div>
@endsection