<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\DivSecUnitController;
use App\Http\Controllers\EmployeeController;

Route::middleware(['auth'])->group(function () {
    // Dashboard Routes
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Position Management Routes
    Route::get('/positions', [\App\Http\Controllers\PositionController::class, 'index'])->name('positions.index');
    Route::get('/positions/create', [\App\Http\Controllers\PositionController::class, 'create'])->name('positions.create');
    Route::post('/positions', [\App\Http\Controllers\PositionController::class, 'store'])->name('positions.store');
    Route::get('/positions/{position}', [\App\Http\Controllers\PositionController::class, 'edit'])->name('positions.edit');
    Route::put('/positions/{position}', [\App\Http\Controllers\PositionController::class, 'update'])->name('positions.update');
    Route::delete('/positions/{position}', [\App\Http\Controllers\PositionController::class, 'destroy'])->name('positions.destroy');

    // Division/Section/Unit Management Routes
    Route::get('/divsecunits', [\App\Http\Controllers\DivSecUnitController::class, 'index'])->name('divsecunits.index');
    Route::get('/divsecunits/create', [\App\Http\Controllers\DivSecUnitController::class, 'create'])->name('divsecunits.create');
    Route::post('/divsecunits', [\App\Http\Controllers\DivSecUnitController::class, 'store'])->name('divsecunits.store');
    Route::get('/divsecunits/{divSecUnit}', [\App\Http\Controllers\DivSecUnitController::class, 'edit'])->name('divsecunits.edit');
    Route::put('/divsecunits/{divSecUnit}', [\App\Http\Controllers\DivSecUnitController::class, 'update'])->name('divsecunits.update');
    Route::delete('/divsecunits/{divSecUnit}', [\App\Http\Controllers\DivSecUnitController::class, 'destroy'])->name('divsecunits.destroy');

    // User Management Routes
    Route::get('/users', [\App\Http\Controllers\UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/create', [\App\Http\Controllers\UserManagementController::class, 'create'])->name('users.create');
    Route::post('/users', [\App\Http\Controllers\UserManagementController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [\App\Http\Controllers\UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [\App\Http\Controllers\UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [\App\Http\Controllers\UserManagementController::class, 'destroy'])->name('users.destroy');
    
    // Employee Management Routes
    Route::get('/employees', [\App\Http\Controllers\EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/create', [\App\Http\Controllers\EmployeeController::class, 'create'])->name('employees.create');
    Route::post('/employees', [\App\Http\Controllers\EmployeeController::class, 'store'])->name('employees.store');
    Route::get('/employees/{employee}', [\App\Http\Controllers\EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/employees/{employee}', [\App\Http\Controllers\EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/employees/{employee}', [\App\Http\Controllers\EmployeeController::class, 'destroy'])->name('employees.destroy');
});

// Authentication Routes
Route::middleware(['guest'])->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('/register', [RegisteredUserController::class, 'create'])
    ->middleware(['guest', \App\Http\Middleware\CheckAdminExists::class])
    ->name('register');

    Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware(['guest', \App\Http\Middleware\CheckAdminExists::class])
    ->name('register.store');
});

Route::post('logout', [AuthController::class, 'logout'])->name('logout');
