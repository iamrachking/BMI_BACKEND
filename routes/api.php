<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Module 1 (Gestion) : pour une API consommée par d'autres clients, créer les contrôleurs dans App\Http\Controllers\Api\Gestion\
// Module 2 (E-commerce) : routes API pour l'app mobile (catalogue, panier, commandes, etc.)
