<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware(['web', 'cache.prevent'])
                ->prefix('admin')
                ->group(base_path('routes/admin.php'));

            Route::middleware(['web', 'cache.prevent'])
                ->group(base_path('routes/college.php'));

            Route::middleware(['web', 'cache.prevent'])
                ->group(base_path('routes/center.php'));

            Route::middleware(['web', 'cache.prevent'])
                ->group(base_path('routes/institute.php'));

            Route::middleware(['web', 'cache.prevent'])
                ->group(base_path('routes/deanship.php'));

            Route::middleware(['web', 'cache.prevent'])
                ->group(base_path('routes/secretariat.php'));

            Route::middleware(['web', 'cache.prevent'])
                ->group(base_path('routes/staff.php'));

            Route::middleware(['web', 'cache.prevent'])
                ->group(base_path('routes/students.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Appending custom logging middleware only
        $middleware->append(\App\Http\Middleware\LogAccessMiddleware::class);

        // Registering Aliases so Route groups don't throw 500 error
        $middleware->alias([
            'abilities' => CheckAbilities::class,
            'ability' => CheckForAnyAbility::class,
            'cache.prevent' => \App\Http\Middleware\NoCacheHeaders::class,
        ]);

        $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // Ignore 404 exceptions from log files
        $exceptions->dontReport([
            NotFoundHttpException::class,
        ]);

        // Arabic 404 Logic
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('ar/*') || $request->is('ar')) {
                return response()->view('errors.404-ar', [], 404);
            }
        });

    })->create();