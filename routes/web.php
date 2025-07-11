<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;

Route::middleware(['auth'])->group(function () {
    // Dashboard Routes
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Travel Orders Routes
    Route::resource('travel-orders', TravelOrderController::class);
    Route::get('travel-orders/print/{id}', [TravelOrderController::class, 'print'])->name('travel-orders.print');

    // Users Routes
    Route::resource('users', UserController::class);
    Route::put('users/{id}/privileges', [UserController::class, 'updatePrivileges'])->name('users.privileges.update');

    // Printing Routes
    Route::get('printing', [PrintingController::class, 'index'])->name('printing');
});

// Authentication Routes
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
