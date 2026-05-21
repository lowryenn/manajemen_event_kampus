<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DesignPatternsDemoController;

// Index Redirect
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'admin' 
            ? redirect()->route('admin.dashboard') 
            : redirect()->route('user.home');
    }
    return redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// User Protected Routes
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/home', [EventController::class, 'index'])->name('user.home');
    Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');
    Route::post('/register-event', [RegistrationController::class, 'register'])->name('events.register');
    Route::get('/my-registrations', [RegistrationController::class, 'index'])->name('user.registrations');
    Route::post('/my-registrations/{id}/pay', [RegistrationController::class, 'pay'])->name('registrations.pay');
});

// Admin Protected Routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/events/create', [AdminController::class, 'showCreateEventForm'])->name('admin.events.create');
    Route::post('/admin/events', [EventController::class, 'store'])->name('admin.events.store');
    Route::delete('/admin/events/{id}', [AdminController::class, 'destroyEvent'])->name('admin.events.destroy');
    Route::get('/admin/events/{id}/edit', [AdminController::class, 'showEditEventForm'])->name('admin.events.edit');
    Route::put('/admin/events/{id}', [EventController::class, 'update'])->name('admin.events.update');
    Route::get('/admin/participants', [AdminController::class, 'participants'])->name('admin.participants');
});

// Design Pattern Demo Route
Route::get('/design-patterns-demo', [DesignPatternsDemoController::class, 'index'])->name('demo.patterns');
