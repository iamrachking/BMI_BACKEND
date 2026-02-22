<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Gestion\DashboardController;
use App\Http\Controllers\Gestion\EquipmentController;
use App\Http\Controllers\Gestion\MaintenanceController;
use App\Http\Controllers\Gestion\FailureController;
use App\Http\Controllers\Gestion\UserController;
use App\Http\Controllers\Gestion\HelpController;
use Illuminate\Support\Facades\Route;

// Favicon : servi par Laravel pour que ça marche en prod (racine web ≠ public/)
Route::get('/favicon.ico', function () {
    $path = public_path('favicon.ico');
    if (!file_exists($path)) {
        abort(404);
    }
    return response()->file($path, ['Content-Type' => 'image/x-icon']);
});

// Documentation API (Swagger) — pas de préfixe /api
Route::get('/api-docs/swagger.json', function () {
    $path = storage_path('api-docs/swagger.json');
    if (!file_exists($path)) {
        abort(404, 'Générez la doc avec : php vendor/bin/openapi app -o storage/api-docs/swagger.json');
    }
    return response()->file($path, ['Content-Type' => 'application/json']);
})->name('api-docs.json');

Route::get('/api-docs', function () {
    return view('api-docs');
})->name('api-docs');

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

    // Administration e-commerce (admin + gestionnaire)
    Route::middleware('admin.ecommerce')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', \App\Http\Controllers\Admin\AdminDashboardController::class)->name('dashboard');
        Route::resource('categories', \App\Http\Controllers\Admin\CategoryAdminController::class);
        Route::resource('products', \App\Http\Controllers\Admin\ProductAdminController::class);
        Route::get('orders', [\App\Http\Controllers\Admin\OrderAdminController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [\App\Http\Controllers\Admin\OrderAdminController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}/status', [\App\Http\Controllers\Admin\OrderAdminController::class, 'updateStatus'])->name('orders.update-status');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
