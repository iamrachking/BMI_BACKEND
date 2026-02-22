<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Gestion\DashboardController;
use App\Http\Controllers\Gestion\EquipmentController;
use App\Http\Controllers\Gestion\MaintenanceController;
use App\Http\Controllers\Gestion\FailureController;
use App\Http\Controllers\Gestion\UserController;
use App\Http\Controllers\Gestion\HelpController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if (in_array($user->role->name ?? '', ['admin', 'gestionnaire', 'technicien'], true)) {
            return redirect()->route('dashboard');
        }
    }
    return redirect()->route('login');
})->name('home');

Route::middleware(['auth', 'verified', 'internal'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/aide', [HelpController::class, 'index'])->name('help');
    Route::resource('equipments', EquipmentController::class);
    Route::resource('maintenances', MaintenanceController::class);
    Route::resource('failures', FailureController::class)->only(['index', 'show', 'create', 'store', 'update']);
    Route::middleware('admin')->resource('users', UserController::class)->only(['index', 'create', 'store', 'edit', 'update']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
