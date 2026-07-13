<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AccountRequestController;

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

Route::get('/enroll', [EnrollmentController::class, 'create'])->name('enroll.create');
Route::post('/enroll', [EnrollmentController::class, 'store'])->name('enroll.store');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/enroll/pending', [EnrollmentController::class, 'pending'])->name('enroll.pending');

    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/requests', [AdminController::class, 'requests'])->name('requests');
        Route::get('/records', [AdminController::class, 'records'])->name('records');
        Route::post('/students/{student}/approve', [AdminController::class, 'approve'])->name('students.approve');
        Route::post('/students/{student}/reject', [AdminController::class, 'reject'])->name('students.reject');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.destroy');

        Route::get('/subjects', [App\Http\Controllers\SubjectController::class, 'index'])->name('subjects');
        Route::post('/subjects', [App\Http\Controllers\SubjectController::class, 'store'])->name('subjects.store');
        Route::delete('/subjects/{subject}', [App\Http\Controllers\SubjectController::class, 'destroy'])->name('subjects.destroy');

        Route::get('/students/{student}/grades', [App\Http\Controllers\GradeController::class, 'edit'])->name('students.grades.edit');
        Route::put('/students/{student}/grades', [App\Http\Controllers\GradeController::class, 'update'])->name('students.grades.update');

        Route::get('/school-years', [App\Http\Controllers\SchoolYearController::class, 'index'])->name('school-years');
        Route::post('/school-years/next', [App\Http\Controllers\SchoolYearController::class, 'storeNext'])->name('school-years.next');
        Route::delete('/school-years/{schoolYear}', [App\Http\Controllers\SchoolYearController::class, 'destroy'])->name('school-years.destroy');
    });

    
   // Student
Route::prefix('student')->name('student.')->middleware('role:student')->group(function () {
    Route::get('/profile', [StudentController::class, 'profile'])->name('profile');
    Route::post('/profile/info', [StudentController::class, 'requestInfoUpdate'])->name('profile.info');
    Route::delete('/profile/requests/{accountChangeRequest}', [StudentController::class, 'cancelChangeRequest'])->name('profile.cancel');
    Route::post('/profile/password', [StudentController::class, 'requestPasswordChange'])->name('profile.password');
    
});

// Admin
Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
    Route::get('/account-requests', [AccountRequestController::class, 'index'])->name('account-requests');
    Route::put('/account-requests/{accountChangeRequest}/approve', [AccountRequestController::class, 'approve'])->name('account-requests.approve');
    Route::put('/account-requests/{accountChangeRequest}/reject', [AccountRequestController::class, 'reject'])->name('account-requests.reject');
});
    

    Route::prefix('student')->name('student.')->middleware('role:student')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\StudentController::class, 'dashboard'])->name('dashboard');
        Route::get('/grades', [App\Http\Controllers\StudentController::class, 'grades'])->name('grades');
    });
});
