<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
});

// Guest routes (register/login)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/enroll', [EnrollmentController::class, 'create'])->name('enroll.create');
    Route::post('/enroll', [EnrollmentController::class, 'store'])->name('enroll.store');
    Route::get('/enroll/pending', [EnrollmentController::class, 'pending'])->name('enroll.pending');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/requests', [AdminController::class, 'requests'])->name('requests');
        Route::get('/records', [AdminController::class, 'records'])->name('records');
        Route::post('/students/{student}/approve', [AdminController::class, 'approve'])->name('students.approve');
        Route::post('/students/{student}/reject', [AdminController::class, 'reject'])->name('students.reject');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.destroy');
    });

    Route::get('/teacher/dashboard', function () {
        return view('teacher.dashboard');
    })->name('teacher.dashboard');

    Route::get('/parent/dashboard', function () {
        return view('parent.dashboard');
    })->name('parent.dashboard');

    Route::get('/student/dashboard', function () {
        return view('student.dashboard');
    })->name('student.dashboard');
});
