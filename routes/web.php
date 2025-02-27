
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\AuthController;


Route::get('/', function () {
    return view('welcome');
});



Route::resource('users', UserController::class);
Route::get('cities/{state_id}', [CityController::class, 'getCities']);
Route::get('states', [StateController::class, 'index']);


Route::get('/get-cities', [UserController::class, 'getCities'])->name('get.cities');

// Route::get('users', [UserController::class, 'index'])->name('users.index');




// -----------------------------------------------------------
Route::middleware(['auth'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
});


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// -----------------------------------------------------------------











use App\Http\Controllers\RoleController;

Route::resource('roles', RoleController::class);
Route::post('/users/{user}/assign-role', [RoleController::class, 'assignRole'])->name('users.assignRole');
Route::delete('/users/{user}/roles/{role}', [RoleController::class, 'removeRole'])->name('users.removeRole');







Route::resource('roles', RoleController::class);

Route::get('/roles/manage/{id}', [RoleController::class, 'manageRoles'])->name('roles.manage');
Route::post('/roles/assign/{id}', [RoleController::class, 'assignRole'])->name('roles.assign');
Route::post('/roles/remove/{id}', [RoleController::class, 'removeRole'])->name('roles.remove');



use App\Http\Controllers\UserRoleController;

Route::resource('roles', RoleController::class);
Route::get('user-roles', [UserRoleController::class, 'index'])->name('user.roles.index');
Route::post('user-roles/{user}/attach', [UserRoleController::class, 'attachRole'])->name('user.roles.attach');
Route::delete('user-roles/{user}/{role}', [UserRoleController::class, 'detachRole'])->name('user.roles.detach');




/* This code snippet is defining routes related to managing suppliers and customers in a Laravel
application. Here's a breakdown of what the code is doing: */
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Gate;


Route::middleware(['auth'])->group(function () {
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create')->middleware('can:manage-suppliers');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store')->middleware('can:manage-suppliers');
    Route::get('/suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit')->middleware('can:manage-suppliers');
    Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update')->middleware('can:manage-suppliers');
    Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy')->middleware('can:manage-suppliers');
});



Route::middleware(['auth'])->group(function () {
    Route::resource('customers', CustomerController::class);
});


/* These routes are defining endpoints for exporting user data in different formats and retrieving user
data via an API. */
Route::get('/users/export/csv', [UserController::class, 'exportCsv'])->name('users.export.csv');
Route::get('/users/export/excel', [UserController::class, 'exportExcel'])->name('users.export.excel');
Route::get('/users/export/pdf', [UserController::class, 'exportPdf'])->name('users.export.pdf');
Route::get('/api/users', [UserController::class, 'getUsers'])->name('api.users');






