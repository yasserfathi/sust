<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\AdsController;

use App\Http\Controllers\ar\HomeController as HomeArController;
use App\Http\Controllers\ar\NewsArController;
use App\Http\Controllers\ar\AdsArController;


use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/admin/departments/print', [\App\Http\Controllers\Admin\DepartmentsController::class, 'print'])->name('departments.print');

// Arabic Routes
Route::group(['prefix' => 'ar', 'middleware' => 'cache.prevent'], function () {
    Route::get('/', [HomeArController::class, 'index'])->name('home_ar');
    Route::get('/administration', [HomeArController::class, 'administration'])->name('administration_ar');
    Route::get('/about_sust', [HomeArController::class, 'about_sust'])->name('about_sust_ar');
    Route::get('/leadership', [HomeArController::class, 'leadership'])->name('leadership_ar');
    Route::get('/sust_leaders', [HomeArController::class, 'sust_leaders'])->name('sust_leaders_ar');
    Route::get('/former_vice_chancellors', [HomeArController::class, 'former_vice_chancellors'])->name('former_vice_chancellors_ar');
    Route::get('/vice_chancellor_message', [HomeArController::class, 'vice_chancellor_message'])->name('vice_chancellor_message_ar');
    Route::get('/khartoum_state', [HomeArController::class, 'khartoum_state'])->name('khartoum_state_ar');
    Route::get('/university_campuses', [HomeArController::class, 'university_campuses'])->name('university_campuses_ar');
    Route::get('/sust_mission_vision_goals', [HomeArController::class, 'sust_mission_vision_goals'])->name('sust_mission_vision_goals_ar');
    Route::get('/medicale_campus', [HomeArController::class, 'medicale_campus'])->name('medicale_campus_ar');
    Route::get('/about', [HomeArController::class, 'about'])->name('about_ar');
    Route::get('/dean_word', [HomeArController::class, 'dean_word'])->name('dean_word_ar');

    // News & Ads
    Route::get('news', [NewsArController::class, 'archive'])->name('news_ar_archive');
    Route::get('news/details/{slug}', [NewsArController::class, 'details'])->name('news_ar_detail');
    Route::get('ads', [AdsArController::class, 'archive'])->name('ads_ar_archive');
    Route::get('ads/details/{slug}', [AdsArController::class, 'details'])->name('ads_ar_detail');

    // Dynamic Pages
    Route::get('/{slug}', [HomeArController::class, 'dynamic_page'])->name('home_dynamic_page_ar');
});

// English Routes
Route::group(['middleware' => 'cache.prevent'], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/administration', [HomeController::class, 'administration'])->name('administration');
    Route::get('/about_sust', [HomeController::class, 'about_sust'])->name('about_sust');
    Route::get('/leadership', [HomeController::class, 'leadership'])->name('leadership');
    Route::get('/sust_leaders', [HomeController::class, 'sust_leaders'])->name('sust_leaders');
    Route::get('/former_vice_chancellors', [HomeController::class, 'former_vice_chancellors'])->name('former_vice_chancellors');
    Route::get('/vice_chancellor_message', [HomeController::class, 'vice_chancellor_message'])->name('vice_chancellor_message');
    Route::get('/khartoum_state', [HomeController::class, 'khartoum_state'])->name('khartoum_state');
    Route::get('/university_campuses', [HomeController::class, 'university_campuses'])->name('university_campuses');
    Route::get('/sust_mission_vision_goals', [HomeController::class, 'sust_mission_vision_goals'])->name('sust_mission_vision_goals');
    Route::get('/medicale_campus', [HomeController::class, 'medicale_campus'])->name('medicale_campus');
    Route::get('/about', [HomeController::class, 'about'])->name('about');
    Route::get('/dean_word', [HomeController::class, 'dean_word'])->name('dean_word');

    // News & Ads
    Route::get('news', [NewsController::class, 'archive'])->name('news_archive');
    Route::get('news/details/{slug}', [NewsController::class, 'details'])->name('news_detail');
    Route::get('ads', [AdsController::class, 'archive'])->name('ads_archive');
    Route::get('ads/details/{slug}', [AdsController::class, 'details'])->name('ads_detail');
});

// Redirect /students to /student
Route::redirect('/students', '/student');
Route::any('/students/{any}', function ($any) {
    return redirect('/student/' . $any);
})->where('any', '.*');

// Utility Routes
Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
    return 'Storage Linked';
});

Route::get('/clear-cache', function () {
    Artisan::call('optimize:clear');
    return "Cache cleared successfully";
});

// Dynamic Pages (Catch-all for single segments)
Route::group(['middleware' => 'cache.prevent'], function () {
    Route::get('/{slug}', [HomeController::class, 'dynamic_page'])
        ->name('home_dynamic_page')
        ->where('slug', '^(?!admin$)[^/]+$');
});

Route::fallback(function () {
    abort(404);
});