<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;

use App\Http\Controllers\VehicleController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index']);
});

Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer/dashboard', [CustomerController::class, 'index']);
});

Route::get('/', [VehicleController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::resource('vehicles', VehicleController::class);
    
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::post('/vehicles/{vehicle}/book', [BookingController::class, 'store'])->name('bookings.store');
    Route::post('/bookings/{booking}/approve', [BookingController::class, 'approve'])->name('bookings.approve');
    Route::post('/bookings/{booking}/return', [BookingController::class, 'markReturned'])->name('bookings.return');

    Route::get('/bookings/{booking}/pay', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/bookings/{booking}/pay', [PaymentController::class, 'store'])->name('payments.store');
});