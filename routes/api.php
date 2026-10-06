<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MahasiswaController;
use App\Http\Controllers\Api\MatakuliahController;
use Illuminate\Support\Facades\Route;

// ============ PUBLIC ROUTES ============
Route::get('/status', function () {
    return response()->json([
        'sukses' => true,
        'pesan'  => 'API Pemweb II aktif',
        'waktu'  => now()->toIso8601String(),
    ]);
});

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1');

// ============ PROTECTED ROUTES ============
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::get('/auth/profil', [AuthController::class, 'profil']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/logout-semua', [AuthController::class, 'logoutSemua']);

    // TUGAS 1 - Ubah Password
    Route::put('/auth/password', [AuthController::class, 'ubahPassword']);

    // Mahasiswa
    Route::get('/mahasiswa', [MahasiswaController::class, 'index']);
    Route::get('/mahasiswa/{mahasiswa}', [MahasiswaController::class, 'show']);
    Route::get('/program-studi/{id}/mahasiswa', [MahasiswaController::class, 'byProgramStudi']);

    Route::middleware('ability:mahasiswa:tulis')->group(function () {
        Route::post('/mahasiswa', [MahasiswaController::class, 'store']);
        Route::put('/mahasiswa/{mahasiswa}', [MahasiswaController::class, 'update']);
        Route::patch('/mahasiswa/{mahasiswa}', [MahasiswaController::class, 'update']);
        Route::delete('/mahasiswa/{mahasiswa}', [MahasiswaController::class, 'destroy'])
            ->middleware('peran.admin');
    });

    // Matakuliah
    Route::apiResource('matakuliah', MatakuliahController::class);
});
