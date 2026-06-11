<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\Admin\EventController as AdminEventController;

// Index Redirects directly to Listing
Route::get('/', function () {
    return redirect()->route('user.home');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/register/otp', [AuthController::class, 'showOtpForm'])->name('register.otp');
    Route::post('/register/otp', [AuthController::class, 'verifyOtp']);
    Route::post('/register/otp/resend', [AuthController::class, 'resendOtp'])->name('register.otp.resend');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Public Event Routes (Accessible by all)
Route::get('/home', [EventController::class, 'index'])->name('user.home');
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');

// Authenticated User Routes
Route::middleware('auth')->group(function () {
    Route::post('/register-event', [RegistrationController::class, 'register'])->name('events.register');
    Route::get('/my-registrations', [RegistrationController::class, 'index'])->name('user.registrations');
    Route::post('/my-registrations/{id}/cancel', [RegistrationController::class, 'cancel'])->name('registrations.cancel');
});

// Admin Dashboard & CRUD Event (Requires Admin Role)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminEventController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/events/create', [AdminEventController::class, 'create'])->name('admin.events.create');
    Route::post('/admin/events', [AdminEventController::class, 'store'])->name('admin.events.store');
    Route::get('/admin/events/{id}/edit', [AdminEventController::class, 'edit'])->name('admin.events.edit');
    Route::put('/admin/events/{id}', [AdminEventController::class, 'update'])->name('admin.events.update');
    Route::delete('/admin/events/{id}', [AdminEventController::class, 'destroy'])->name('admin.events.destroy');
    Route::get('/admin/participants', [AdminEventController::class, 'participants'])->name('admin.participants');
});
