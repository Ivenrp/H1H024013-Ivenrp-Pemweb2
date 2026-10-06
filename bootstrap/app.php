<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'peran.admin' => \App\Http\Middleware\PeranAdmin::class,
            'ability'     => \Laravel\Sanctum\Http\Middleware\CheckAbilities::class,
            'abilities'   => \Laravel\Sanctum\Http\Middleware\CheckForAnyAbility::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        // Handler 401 — token tidak valid / belum dikirim
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'sukses' => false,
                    'pesan'  => 'Token tidak valid atau belum dikirim',
                ], 401);
            }
        });

        // Handler 404 — resource tidak ditemukan
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'sukses' => false,
                    'pesan'  => 'Sumber daya tidak ditemukan',
                ], 404);
            }
        });

        // Handler 422 — validasi gagal
        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'sukses' => false,
                    'pesan'  => 'Data yang dikirim tidak valid',
                    'galat'  => $e->errors(),
                ], 422);
            }
        });
    })->create();
