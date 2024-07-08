<?php

use App\Http\Middleware\CheckActiveTapel;
use App\Http\Middleware\CheckExistsGuru;
use App\Http\Middleware\CheckExistsJurusan;
use App\Http\Middleware\CheckExistsKelas;
use App\Http\Middleware\CheckExistsMapel;
use App\Http\Middleware\CheckExistsRombel;
use App\Http\Middleware\UserRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => UserRole::class,
            'active.tapel' => CheckActiveTapel::class,
            'exists.mapel' => CheckExistsMapel::class,
            'exists.rombel' => CheckExistsRombel::class,
            'exists.jurusan' => CheckExistsJurusan::class,
            'exists.kelas' => CheckExistsKelas::class,
            'exists.guru' => CheckExistsGuru::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
