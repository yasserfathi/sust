<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Config; // إضافة مهمة
use Illuminate\Support\Facades\URL;    // إضافة مهمة
use Barryvdh\Debugbar\Facades\Debugbar;
// use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot()
    {
        if (!app()->runningInConsole()) {

            $currentUrl = request()->getSchemeAndHttpHost();
            $host = request()->getHost();

            Config::set('app.url', $currentUrl);
            URL::forceRootUrl($currentUrl);

            $currentSanctum = Config::get('sanctum.stateful', []);
            $newSanctum = array_merge($currentSanctum, [
                $host,
                $host . ':' . request()->getPort(),
            ]);

            Config::set('sanctum.stateful', array_unique($newSanctum));
        }

        // \URL::forceScheme('https');
        // Schema::defaultStringLength(191);

        if (request()->is('api/*')) {
            Debugbar::disable();
        }

        Collection::macro('paginate', function ($perPage, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

            return new LengthAwarePaginator(
                $total ? $this : $this->forPage($page, $perPage)->values(),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });
    }
}