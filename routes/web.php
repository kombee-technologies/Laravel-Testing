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







Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);













use App\Http\Controllers\RoleController;

Route::resource('roles', RoleController::class);
Route::post('/users/{user}/assign-role', [RoleController::class, 'assignRole'])->name('users.assignRole');
Route::delete('/users/{user}/roles/{role}', [RoleController::class, 'removeRole'])->name('users.removeRole');





// use App\Http\Controllers\RoleController;

// Route::get('/assign-role/{userId}/{roleId}', [RoleController::class, 'assignRole']);


// use App\Http\Controllers\RoleController;

Route::resource('roles', RoleController::class);

// Route::get('/users/{user}/assign-role', [RoleController::class, 'showAssignRoleForm'])->name('roles.show');
// Route::post('/assign-role/{user}', [RoleController::class, 'assignRole'])->name('roles.assign');
// Route::post('/remove-role/{user}', [RoleController::class, 'removeRole'])->name('roles.remove');


// Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
// Route::post('/roles/assign/{user}', [RoleController::class, 'assignRole'])->name('roles.assign');
// Route::post('/roles/remove/{user}', [RoleController::class, 'removeRole'])->name('roles.remove');




Route::get('/roles/manage/{id}', [RoleController::class, 'manageRoles'])->name('roles.manage');
Route::post('/roles/assign/{id}', [RoleController::class, 'assignRole'])->name('roles.assign');
Route::post('/roles/remove/{id}', [RoleController::class, 'removeRole'])->name('roles.remove');
