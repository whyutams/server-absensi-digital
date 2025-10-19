<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/status', function () {
    return response()->json(['status' => 'API is running']);
});

// untuk menampilkan semua route API
Route::get('/routes', function () {
    $routes = collect(Route::getRoutes())
        ->filter(fn($route) => str_starts_with($route->uri, 'api/'))
        ->map(fn($route) => [
            'method' => implode('|', $route->methods),
            'uri' => $route->uri,
            // 'name' => $route->getName(),
            // 'action' => $route->getActionName(),
        ])
        ->values();

    return response()->json([
        'status' => 'success',
        'count' => $routes->count(),
        'routes' => $routes,
    ]);
});

Route::resource('/users', UserController::class)->names('users');