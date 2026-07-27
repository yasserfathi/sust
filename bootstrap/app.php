<?php

use App\Http\Middleware\PreventCaching;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request; // <--- Added for 404 logic
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException; // <--- Added for 404 logic
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        // Register the Admin Routes here
        then: function () {
            Route::middleware('web')
                ->prefix('admin')
                ->group(base_path('routes/admin.php'));

            Route::middleware('web')
                ->group(base_path('routes/college.php'));

            Route::middleware('web')
                ->group(base_path('routes/center.php'));

            Route::middleware('web')
                ->group(base_path('routes/deanship.php'));

            Route::middleware('web')
                ->group(base_path('routes/secretariat.php'));

            Route::middleware('web')
                ->group(base_path('routes/staff.php'));

            Route::middleware('web')
                ->group(base_path('routes/students.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'abilities' => CheckAbilities::class,
            'ability' => CheckForAnyAbility::class,
            'cache.prevent' => \App\Http\Middleware\NoCacheHeaders::class,
        ]);
        $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions) {

        //  Arabic 404 Logic here
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {

            // Check if URL is 'ar' or starts with 'ar/'
            if ($request->is('ar/*') || $request->is('ar')) {
                return response()->view('errors.404-ar', [], 404);
            }

            // Otherwise, Laravel loads the default errors/404.blade.php
        });

    })->create();