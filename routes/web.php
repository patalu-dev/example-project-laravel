<?php

use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRoleController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return to_route('dashboard');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::resources([
        'permissions' => PermissionController::class,
        'roles' => RoleController::class,
        'users' => UserController::class
    ]);

    Route::put('/users/{id}/change-status', [UserController::class, 'changeStatus'])->name('users.change.status');
    Route::put('/users/{id}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset.password');

    Route::get('/users/{user}/roles', [UserRoleController::class, 'edit'])->name('users.roles.edit');
    Route::put('/users/{user}/roles', [UserRoleController::class, 'update'])->name('users.roles.update');

    Route::get('/export/users', [UserController::class, 'export'])->name('users.export');
});

Route::get('/roles/{id}/permissions', [RolePermissionController::class, 'edit'])->name('roles.permissions.edit');
Route::put('/roles/{id}/permissions', [RolePermissionController::class, 'update'])->name('roles.permissions.update');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
