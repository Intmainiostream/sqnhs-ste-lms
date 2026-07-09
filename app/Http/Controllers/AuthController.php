<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    private const MAX_ATTEMPTS = 5;
    private const LOCK_MINUTES = 5;

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'username'    => ['required', 'string', 'max:50', 'unique:users,username'],
            'email'       => ['required', 'email', 'max:100', 'unique:users,email'],
            'password'    => ['required', 'string', 'min:8', 'confirmed'],
            'grade_level' => ['required', 'in:7,8,9,10'],
        ]);

        session([
            'pending_registration' => [
                'username'    => $validated['username'],
                'email'       => $validated['email'],
                'password'    => Hash::make($validated['password']),
                'grade_level' => $validated['grade_level'],
            ],
        ]);

        return redirect()->route('enroll.create');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        $user = User::where('username', $credentials['username'])->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'username' => 'Invalid credentials.',
            ]);
        }

        if ($user->locked_until && $user->locked_until->isFuture()) {
            $minutesLeft = now()->diffInMinutes($user->locked_until) + 1;
            throw ValidationException::withMessages([
                'username' => "Account locked. Try again in {$minutesLeft} minute(s).",
            ]);
        }

        if (!Hash::check($credentials['password'], $user->password)) {
            $user->increment('failed_attempts');

            if ($user->failed_attempts >= self::MAX_ATTEMPTS) {
                $user->update([
                    'locked_until'    => now()->addMinutes(self::LOCK_MINUTES),
                    'failed_attempts' => 0,
                ]);

                throw ValidationException::withMessages([
                    'username' => 'Too many failed attempts. Account locked for ' . self::LOCK_MINUTES . ' minutes.',
                ]);
            }

            throw ValidationException::withMessages([
                'username' => 'Invalid credentials.',
            ]);
        }

        if ($user->status === 'pending') {
            throw ValidationException::withMessages([
                'username' => 'Your account has not been verified yet. Please wait for admin approval.',
            ]);
        }

        if ($user->status === 'inactive') {
            throw ValidationException::withMessages([
                'username' => 'This account has been disabled. Please contact the school admin.',
            ]);
        }

        $user->update([
            'failed_attempts' => 0,
            'locked_until'    => null,
        ]);

        Auth::login($user, $remember);
        $request->session()->regenerate();

        return $this->redirectByRole($user->role);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function redirectByRole(string $role)
    {
        return match ($role) {
            'admin'   => redirect()->route('admin.dashboard'),
            'teacher' => redirect()->route('teacher.dashboard'),
            'parent'  => redirect()->route('parent.dashboard'),
            'student' => redirect()->route('student.dashboard'),
            default   => redirect()->route('login'),
        };
    }
}