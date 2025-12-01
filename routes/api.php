<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GeneralController;
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
    Route::post('/whoami', [AuthController::class, 'logged_user']);
    Route::put('/profile/edit', [AuthController::class, 'update_profile']);
    Route::get('/info', [GeneralController::class, 'info']);
    Route::post('/reset-password', [AuthController::class, 'reset']);

    Route::middleware(['role:' . User::ROLE_SUPERADMIN . ',' . User::ROLE_KEPSEK])->group(function () {
        Route::apiResource('/jurusan', JurusanController::class)->names('jurusan');
        Route::apiResource('/users', UserController::class)->names('users');
        Route::apiResource('/kelas', KelasController::class)->parameters(['kelas' => 'kelas'])->names('kelas');
        Route::get('/kelas/list', [KelasController::class, 'list_aturan_kelas'])->name('kelas.list');
        Route::post('/kelas/list/generate', [KelasController::class, 'generate_list_aturan_kelas'])->name('kelas.list.generate');
    });

    Route::middleware(['role:' . User::ROLE_GURU . ',' . User::ROLE_GURU_PIKET . ',' . User::ROLE_KESISWAAN . ',' . User::ROLE_BK . User::ROLE_SUPERADMIN . ',' . User::ROLE_KEPSEK])->group(function () {
        Route::apiResource('/siswa', SiswaController::class)->only(['index', 'show'])->names('siswa');
    });

    Route::middleware(['role:' . User::ROLE_GURU_PIKET . ',' . User::ROLE_KESISWAAN . ',' . User::ROLE_BK  . ',' . User::ROLE_SUPERADMIN . ',' . User::ROLE_KEPSEK])->group(function () {
        Route::apiResource('/siswa', SiswaController::class)->only(['store', 'update', 'destroy'])->names('siswa');
    });

    Route::middleware(['role:' . implode(',', [
        User::ROLE_SUPERADMIN,
        User::ROLE_KEPSEK,
        User::ROLE_GURU,
        User::ROLE_GURU_PIKET,
        User::ROLE_KESISWAAN,
        User::ROLE_BK,
        User::ROLE_ORANGTUA,
    ])])->group(function () {
        Route::apiResource('/keterlambatan', KeterlambatanController::class)->only(['index', 'show'])->names('keterlambatan');
    });

    Route::middleware(['role:' . User::ROLE_GURU_PIKET . ',' . User::ROLE_KESISWAAN . ',' . User::ROLE_BK])->group(function () {
        Route::apiResource('/keterlambatan', KeterlambatanController::class)->only(['store', 'destroy'])->names('keterlambatan');
    });

    Route::middleware(['role:' . implode(',', [
        User::ROLE_SUPERADMIN,
        User::ROLE_KEPSEK,
        User::ROLE_GURU,
        User::ROLE_GURU_PIKET,
        User::ROLE_KESISWAAN,
        User::ROLE_ORANGTUA,
        User::ROLE_BK,
        User::ROLE_SATPAM,
    ])])->group(function () {
        Route::apiResource('/perizinan', PerizinanController::class)->only(['index', 'show'])->names('perizinan');
    });

    Route::middleware(['role:' . User::ROLE_GURU_PIKET . ',' . User::ROLE_KESISWAAN . ',' . User::ROLE_BK . ',' . User::ROLE_GURU])->group(function () {
        Route::apiResource('/perizinan', PerizinanController::class)->only(['store', 'destroy'])->names('perizinan');
    });

    Route::middleware(['role:' . User::ROLE_GURU_PIKET . ',' . User::ROLE_KESISWAAN . ',' . User::ROLE_BK])->group(function () {
        Route::put('/perizinan/status/{perizinan}', [PerizinanController::class, 'status'])->name('perizinan.status');
    });
});
