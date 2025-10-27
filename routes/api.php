<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\KeterlambatanController;
use App\Http\Controllers\PerizinanController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\UserController;
use App\Models\User;
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

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::middleware(['role:' . User::ROLE_SUPERADMIN . ',' . User::ROLE_KEPSEK])->group(function () {
        Route::apiResource('/jurusan', JurusanController::class)->only(['index', 'store', 'update', 'destroy'])->names('jurusan');
        Route::apiResource('/users', UserController::class)->only(['index', 'store', 'update', 'destroy'])->names('users');
        Route::apiResource('/kelas', KelasController::class)->only(['index', 'store', 'update', 'destroy'])->parameters(['kelas' => 'kelas'])->names('kelas');
        Route::get('/kelas/list', [KelasController::class, 'list_aturan_kelas'])->name('kelas.list');
        Route::post('/kelas/list/generate', [KelasController::class, 'generate_list_aturan_kelas'])->name('kelas.list.generate');
    });

    Route::middleware(['role:' . User::ROLE_GURU . ',' . User::ROLE_GURU_PIKET])->group(function () {
        Route::apiResource('/siswa', SiswaController::class)->only(['index'])->names('siswa');
    });

    Route::middleware(['role:' . User::ROLE_GURU_PIKET])->group(function () {
        Route::apiResource('/siswa', SiswaController::class)->only(['store', 'update', 'destroy'])->names('siswa');
    });

    Route::middleware(['role:' . implode(',', [
        User::ROLE_SUPERADMIN,
        User::ROLE_KEPSEK,
        User::ROLE_GURU,
        User::ROLE_GURU_PIKET,
        User::ROLE_KESISWAAN,
        User::ROLE_ORANGTUA,
    ])])->group(function () {
        Route::apiResource('/keterlambatan', KeterlambatanController::class)->only(['index'])->names('keterlambatan');
    });

    Route::middleware(['role:' . User::ROLE_GURU_PIKET . ',' . User::ROLE_KESISWAAN])->group(function () {
        Route::apiResource('/keterlambatan', KeterlambatanController::class)->only(['store', 'destroy'])->names('keterlambatan');
    });

    Route::middleware(['role:' . implode(',', [
        User::ROLE_SUPERADMIN,
        User::ROLE_KEPSEK,
        User::ROLE_GURU,
        User::ROLE_GURU_PIKET,
        User::ROLE_KESISWAAN,
        User::ROLE_ORANGTUA,
    ])])->group(function () {
        Route::apiResource('/perizinan', PerizinanController::class)->only(['index'])->names('perizinan');
    });

    Route::middleware(['role:' . User::ROLE_GURU_PIKET . ',' . User::ROLE_KESISWAAN])->group(function () {
        Route::apiResource('/perizinan', PerizinanController::class)->only(['store', 'destroy'])->names('perizinan');
        Route::put('/perizinan/status', [PerizinanController::class, 'status'])->name('perizinan.status');
    });
});
