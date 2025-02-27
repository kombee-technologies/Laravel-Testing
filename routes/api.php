<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->get('/user', [AuthController::class, 'user']);




use App\Http\Controllers\SupplierController;



Route::middleware(['auth:api'])->group(function () {
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create')->middleware('can:manage-suppliers');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store')->middleware('can:manage-suppliers');
    Route::get('/suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit')->middleware('can:manage-suppliers');
    Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update')->middleware('can:manage-suppliers');
    Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy')->middleware('can:manage-suppliers');
});

// Route::middleware('auth:api')->group(function () {
    Route::get('/suppliers/export/csv', [SupplierController::class, 'exportCSV']);
    Route::get('/suppliers/export/excel', [SupplierController::class, 'exportExcel']);
    Route::get('/suppliers/export/pdf', [SupplierController::class, 'exportPDF']);
// });









use App\Http\Controllers\CustomerController;

Route::middleware('auth:api')->group(function () {
    Route::resource('customers', CustomerController::class);
    Route::get('/customers', [CustomerController::class, 'index']);
});
Route::get('/customers/export/csv', [CustomerController::class, 'exportCSV']);
Route::get('/customers/export/excel', [CustomerController::class, 'exportExcel']);
Route::get('/customers/export/pdf', [CustomerController::class, 'exportPDF']);





use App\Http\Controllers\UserController;

Route::middleware('auth:api')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/export/csv', [UserController::class, 'exportCSV']);
    Route::get('/users/export/excel', [UserController::class, 'exportExcel']);
    Route::get('/users/export/pdf', [UserController::class, 'exportPDF']);
});



use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\UserController;

Route::middleware('auth:api')->group(function () {
    Route::get('/users', [UserController::class, 'index']); // List users with pagination
    Route::get('/users/{id}', [UserController::class, 'show']); // Get a specific user
    Route::post('/users', [UserController::class, 'store']); // Create a new user
    Route::put('/users/{id}', [UserController::class, 'update']); // Update user details
    Route::delete('/users/{id}', [UserController::class, 'destroy']); // Delete a user

    // Export user data
    Route::get('/users/export/csv', [UserController::class, 'exportCsv']); 
    Route::get('/users/export/excel', [UserController::class, 'exportExcel']); 
    Route::get('/users/export/pdf', [UserController::class, 'exportPdf']); 
});
