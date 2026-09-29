<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\AdsController;
use App\Http\Controllers\EventsController;

use App\Http\Controllers\ar\HomeController as HomeArController;
use App\Http\Controllers\ar\NewsArController;
use App\Http\Controllers\ar\AdsArController;
use App\Http\Controllers\ar\EventsArController;



use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Global Search Route
Route::get('/search', [\App\Http\Controllers\SearchController::class, 'index'])->name('search.index');

// XML Sitemaps (SEO)
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap.index');
Route::get('/sitemap-pages.xml', [\App\Http\Controllers\SitemapController::class, 'pages'])->name('sitemap.pages');
Route::get('/sitemap-news.xml', [\App\Http\Controllers\SitemapController::class, 'news'])->name('sitemap.news');
Route::get('/sitemap-colleges.xml', [\App\Http\Controllers\SitemapController::class, 'colleges'])->name('sitemap.colleges');

Route::get('/admin/departments/print', [\App\Http\Controllers\Admin\DepartmentsController::class, 'print'])->name('departments.print');
Route::get('/admin/schools/print', [\App\Http\Controllers\Admin\SchoolsController::class, 'print'])->name('schools.print');

// Arabic Routes
Route::group(['prefix' => 'ar', 'middleware' => 'cache.prevent'], function () {
    Route::get('/', [HomeArController::class, 'index'])->name('home_ar');
    Route::get('/about_sust', [HomeArController::class, 'about_sust'])->name('about_sust_ar');
    Route::get('/leadership', [HomeArController::class, 'leadership'])->name('leadership_ar');
    Route::get('/sust_leaders', [HomeArController::class, 'sust_leaders'])->name('sust_leaders_ar');
    Route::get('/former_vice_chancellors', [HomeArController::class, 'former_vice_chancellors'])->name('former_vice_chancellors_ar');
    Route::get('/vice_chancellor_message', [HomeArController::class, 'vice_chancellor_message'])->name('vice_chancellor_message_ar');
    Route::get('/khartoum_state', [HomeArController::class, 'khartoum_state'])->name('khartoum_state_ar');
    Route::get('/university_campuses', [HomeArController::class, 'university_campuses'])->name('university_campuses_ar');
    Route::get('/sust_campuses', [HomeArController::class, 'university_campuses'])->name('sust_campuses_ar');
    Route::get('/sust_campuses/{slug}', [HomeArController::class, 'campus_detail'])->name('sust_campus_detail_ar');
    Route::get('/sust_mission_vision_goals', [HomeArController::class, 'sust_mission_vision_goals'])->name('sust_mission_vision_goals_ar');
    Route::get('/medical_campus', [HomeArController::class, 'medical_campus'])->name('medical_campus_ar');
    Route::get('/about', [HomeArController::class, 'about'])->name('about_ar');

    // News & Ads
    Route::get('news', [NewsArController::class, 'archive'])->name('news_ar_archive');
    Route::get('news/details/{slug}', [NewsArController::class, 'details'])->name('news_ar_detail');
    Route::get('ads', [AdsArController::class, 'archive'])->name('ads_ar_archive');
    Route::get('ads/details/{slug}', [AdsArController::class, 'details'])->name('ads_ar_detail');

    // Conferences, Seminars and workshops
    Route::prefix('{type}')
    ->whereIn('type', ['conferences', 'seminars', 'workshops'])
    ->controller(EventsArController::class)
    ->name('events_ar_')
    ->group(function () {
        Route::get('/', 'archive')->name('archive');
        Route::get('details/{slug}', 'details')->name('detail');
    });

    // Dynamic Pages
    Route::get('/{slug}', [HomeArController::class, 'dynamic_page'])->name('home_dynamic_page_ar');
});

// English Routes
Route::group(['middleware' => 'cache.prevent'], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/about_sust', [HomeController::class, 'about_sust'])->name('about_sust');
    Route::get('/leadership', [HomeController::class, 'leadership'])->name('leadership');
    Route::get('/sust_leaders', [HomeController::class, 'sust_leaders'])->name('sust_leaders');
    Route::get('/former_vice_chancellors', [HomeController::class, 'former_vice_chancellors'])->name('former_vice_chancellors');
    Route::get('/vice_chancellor_message', [HomeController::class, 'vice_chancellor_message'])->name('vice_chancellor_message');
    Route::get('/khartoum_state', [HomeController::class, 'khartoum_state'])->name('khartoum_state');
    Route::get('/university_campuses', [HomeController::class, 'university_campuses'])->name('university_campuses');
    Route::get('/sust_campuses', [HomeController::class, 'university_campuses'])->name('sust_campuses');
    Route::get('/sust_campuses/{slug}', [HomeController::class, 'campus_detail'])->name('sust_campus_detail');
    Route::get('/sust_mission_vision_goals', [HomeController::class, 'sust_mission_vision_goals'])->name('sust_mission_vision_goals');
    Route::get('/medical_campus', [HomeController::class, 'medical_campus'])->name('medical_campus');
    Route::get('/about', [HomeController::class, 'about'])->name('about');

    // News & Ads
    Route::get('news', [NewsController::class, 'archive'])->name('news_archive');
    Route::get('news/details/{slug}', [NewsController::class, 'details'])->name('news_detail');
    Route::get('ads', [AdsController::class, 'archive'])->name('ads_archive');
    Route::get('ads/details/{slug}', [AdsController::class, 'details'])->name('ads_detail');

    // Conferences, Seminars and workshops
    Route::prefix('{type}')
    ->whereIn('type', ['conferences', 'seminars', 'workshops'])
    ->controller(EventsController::class)
    ->name('events_')
    ->group(function () {
        Route::get('/', 'archive')->name('archive');
        Route::get('details/{slug}', 'details')->name('detail');
    });
});

// Redirect /students to /student
Route::redirect('/students', '/student');
Route::any('/students/{any}', function ($any) {
    return redirect('/student/' . $any);
})->where('any', '.*');

// Utility Routes (Protected)
Route::get('/linkstorage', function () {
    if (!auth()->check() && !app()->environment('local')) {
        abort(403, 'غير مصرح لك بإجراء هذه العملية');
    }
    Artisan::call('storage:link');
    return response()->json(['status' => 200, 'message' => 'Storage Linked Successfully']);
})->middleware('throttle:3,1');

Route::get('/clear-cache', function () {
    if (!auth()->check() && !app()->environment('local')) {
        abort(403, 'غير مصرح لك بإجراء هذه العملية');
    }
    Artisan::call('optimize:clear');
    return response()->json(['status' => 200, 'message' => 'Cache cleared successfully']);
})->middleware('throttle:3,1');

// Route::get('/migrate', function () {
//     try {
//         Artisan::call('migrate', ['--force' => true]);
//         $output = Artisan::output();
//         return response()->json([
//             'status' => 200,
//             'message' => 'Migration executed successfully',
//             'output' => $output
//         ]);
//     } catch (\Throwable $e) {
//         return response()->json([
//             'status' => 500,
//             'error' => $e->getMessage(),
//             'file' => $e->getFile(),
//             'line' => $e->getLine(),
//         ], 500);
//     }
// });

// Dynamic Pages (Catch-all for single segments)
Route::group(['middleware' => 'cache.prevent'], function () {
    Route::get('/{slug}', [HomeController::class, 'dynamic_page'])
        ->name('home_dynamic_page')
        ->where('slug', '^(?!admin$)[^/]+$');
});

Route::fallback(function () {
    abort(404);
});