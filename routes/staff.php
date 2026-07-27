<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ar\StaffArController;

/* |-------------------------------------------------------------------------- | Staff Routes |-------------------------------------------------------------------------- | | Here is where you can register staff routes for your application. | */

// English Routes
Route::group(['prefix' => 'staff', 'middleware' => 'cache.prevent'], function () {
    Route::get('/{name_en}', [StaffController::class, 'show'])->name('staff_home');
    Route::get('/{name_en}/cv', [StaffController::class, 'cv'])->name('staff.cv');
    Route::get('/{name_en}/scientific-papers', [StaffController::class, 'scientificPapers'])->name('staff.scientific_papers');
    Route::get('/{name_en}/books', [StaffController::class, 'books'])->name('staff.books');
    Route::get('/{name_en}/running-projects', [StaffController::class, 'runningProjects'])->name('staff.running_projects');
    Route::get('/{name_en}/courses', [StaffController::class, 'courses'])->name('staff.courses');
    Route::get('/{name_en}/community-service', [StaffController::class, 'communityService'])->name('staff.communityservice');
    Route::get('/{name_en}/workshops', [StaffController::class, 'workshops'])->name('staff.workshops');
    Route::get('/{name_en}/supervising-projects', [StaffController::class, 'supervisingProjects'])->name('staff.supervising_projects');
    Route::get('/{name_en}/research-topics', [StaffController::class, 'researchTopics'])->name('staff.research_topics');
    Route::get('/{name_en}/positions', [StaffController::class, 'positions'])->name('staff.positions');
    Route::get('/{name_en}/committees', [StaffController::class, 'committees'])->name('staff.committees');
    Route::get('/{name_en}/training-courses', [StaffController::class, 'trainingCourses'])->name('staff.training_courses');
    Route::get('/{name_en}/certificates', [StaffController::class, 'certificates'])->name('staff.certificates');
    Route::get('/{name_en}/google-scholar', [StaffController::class, 'googleScholar'])->name('staff.google_scholar');
    Route::get('/{name_en}/articles', [StaffController::class, 'articles'])->name('staff.articles');
    Route::get('/{name_en}/links', [StaffController::class, 'links'])->name('staff.links');
});

// Arabic Routes
Route::group(['prefix' => 'ar/staff', 'middleware' => 'cache.prevent'], function () {
    Route::get('/{name_en}', action: [StaffArController::class, 'show'])->name('staff_home_ar');
    Route::get('/{name_en}/cv', [StaffArController::class, 'cv'])->name('staff.cv_ar');
    Route::get('/{name_en}/scientific-papers', [StaffArController::class, 'scientificPapers'])->name('staff.scientific_papers_ar');
    Route::get('/{name_en}/books', [StaffArController::class, 'books'])->name('staff.books_ar');
    Route::get('/{name_en}/running-projects', [StaffArController::class, 'runningProjects'])->name('staff.running_projects_ar');
    Route::get('/{name_en}/courses', [StaffArController::class, 'courses'])->name('staff.courses_ar');
    Route::get('/{name_en}/community-service', [StaffArController::class, 'communityService'])->name('staff.communityservice_ar');
    Route::get('/{name_en}/workshops', [StaffArController::class, 'workshops'])->name('staff.workshops_ar');
    Route::get('/{name_en}/supervising-projects', [StaffArController::class, 'supervisingProjects'])->name('staff.supervising_projects_ar');
    Route::get('/{name_en}/research-topics', [StaffArController::class, 'researchTopics'])->name('staff.research_topics_ar');
    Route::get('/{name_en}/positions', [StaffArController::class, 'positions'])->name('staff.positions_ar');
    Route::get('/{name_en}/committees', [StaffArController::class, 'committees'])->name('staff.committees_ar');
    Route::get('/{name_en}/training-courses', [StaffArController::class, 'trainingCourses'])->name('staff.training_courses_ar');
    Route::get('/{name_en}/certificates', [StaffArController::class, 'certificates'])->name('staff.certificates_ar');
    Route::get('/{name_en}/google-scholar', [StaffArController::class, 'googleScholar'])->name('staff.google_scholar_ar');
    Route::get('/{name_en}/articles', [StaffArController::class, 'articles'])->name('staff.articles_ar');
    Route::get('/{name_en}/links', [StaffArController::class, 'links'])->name('staff.links_ar');
});
