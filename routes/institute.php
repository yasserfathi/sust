<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CollegeController;
use App\Http\Controllers\ar\CollegeArController;

// English Routes
Route::group(['prefix' => 'institute', 'middleware' => 'cache.prevent'], function () {
    Route::get('/{name}', [CollegeController::class, 'index'])->name('institute_home');
    Route::get('/{name}/about', [CollegeController::class, 'about'])->name('institute_about');
    Route::get('/{name}/activities', [CollegeController::class, 'activities'])->name('institute_activities');
    Route::get('/{name}/vision-mission-objectives', [CollegeController::class, 'vision_mission_objectives'])->name('institute_vision_mission_objectives');
    Route::get('/{name}/dean_message', [CollegeController::class, 'dean_message'])->name('institute_dean_message');
    Route::get('/{name}/department/{dept_name}', [CollegeController::class, 'about_department'])->name('institute_about_department');
    Route::get('/{name}/department/{dept_name}/programs', [CollegeController::class, 'department_programs'])->name('institute_department_programs');
    // News & Ads
    Route::get('/{name}/news', [CollegeController::class, 'archive'])->name('institute_news_archive');
    Route::get('/{name}/news/details/{slug}', [CollegeController::class, 'details'])->name('institute_news_detail');
    Route::get('/{name}/ads', [CollegeController::class, 'ads_archive'])->name('institute_ads_archive');
    Route::get('/{name}/ads/details/{slug}', [CollegeController::class, 'ads_details'])->name('institute_ads_detail');
    Route::get('/{name}/staff', [CollegeController::class, 'staff'])->name('institute_staff');
    Route::get('/{name}/{slug}', [CollegeController::class, 'dynamic_page'])->name('institute_dynamic_page');
});

// Arabic Routes
Route::group(['prefix' => 'ar/institute'], function () {
    Route::get('/{name}', [CollegeArController::class, 'index'])->name('institute_home_ar');
    Route::get('/{name}/about', [CollegeArController::class, 'about'])->name('institute_about_ar');
    Route::get('/{name}/academic-programs', [CollegeArController::class, 'academic_programs'])->name('institute_academic_programs_ar');
    Route::get('/{name}/vision-mission-objectives', [CollegeArController::class, 'vision_mission_objectives'])->name('institute_vision_mission_objectives_ar');
    Route::get('/{name}/dean_message', [CollegeArController::class, 'dean_message'])->name('institute_dean_message_ar');
    Route::get('/{name}/department/{dept_name}', [CollegeArController::class, 'about_department'])->name('institute_about_department_ar');
    Route::get('/{name}/department/{dept_name}/programs', [CollegeArController::class, 'department_programs'])->name('institute_department_programs_ar');
    Route::get('/{name}/staff', [CollegeArController::class, 'staff'])->name('institute_staff_ar');
    // News & Ads
    Route::get('/{name}/news', [CollegeArController::class, 'news_archive'])->name('institute_news_archive_ar');
    Route::get('/{name}/news/details/{slug}', [CollegeArController::class, 'news_details'])->name('institute_news_detail_ar');
    Route::get('/{name}/ads', [CollegeArController::class, 'ads_archive'])->name('institute_ads_archive_ar');
    Route::get('/{name}/ads/details/{slug}', [CollegeArController::class, 'ads_details'])->name('institute_ads_detail_ar');
    Route::get('/{name}/{slug}', [CollegeArController::class, 'dynamic_page'])->name('institute_dynamic_page_ar');
});
