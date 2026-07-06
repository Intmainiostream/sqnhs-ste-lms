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

        $user = User::create([
            'username' => $validated['username'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => 'parent',
            'status'   => 'pending',
        ]);

        $activeSchoolYear = \App\Models\SchoolYear::where('is_active', true)->first();

        $student = Student::create([
            'user_id'           => $user->id,
            'school_year_id'    => $activeSchoolYear?->id,
            'grade_level'       => $validated['grade_level'],
            'enrollment_status' => 'pending',
        ]);

        Auth::login($user);

        if ($validated['grade_level'] == 7) {
            return redirect()->route('enroll.create');
        }

        return redirect()->route('enroll.pending');
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

        if ($user->status !== 'active') {
            throw ValidationException::withMessages([
                'username' => 'Your account is not yet active. Please wait for admin approval.',
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