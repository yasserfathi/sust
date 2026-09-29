<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ar\StaffArController;

/* |-------------------------------------------------------------------------- | Staff Routes |-------------------------------------------------------------------------- | | Here is where you can register staff routes for your application. | */

// English Routes
Route::group(['prefix' => 'staff', 'middleware' => 'cache.prevent', 'where' => ['slug' => '[^/]+']], function () {
    Route::get('/{slug}', [StaffController::class, 'show'])->name('staff_home');
    Route::get('/{slug}/cv', [StaffController::class, 'cv'])->name('staff.cv');
    Route::get('/{slug}/scientific-papers', [StaffController::class, 'scientificPapers'])->name('staff.scientific_papers');
    Route::get('/{slug}/books', [StaffController::class, 'books'])->name('staff.books');
    Route::get('/{slug}/running-projects', [StaffController::class, 'runningProjects'])->name('staff.running_projects');
    Route::get('/{slug}/courses', [StaffController::class, 'courses'])->name('staff.courses');
    Route::get('/{slug}/community-service', [StaffController::class, 'communityService'])->name('staff.communityservice');
    Route::get('/{slug}/workshops', [StaffController::class, 'workshops'])->name('staff.workshops');
    Route::get('/{slug}/supervising-projects', [StaffController::class, 'supervisingProjects'])->name('staff.supervising_projects');
    Route::get('/{slug}/research-topics', [StaffController::class, 'researchTopics'])->name('staff.research_topics');
    Route::get('/{slug}/positions', [StaffController::class, 'positions'])->name('staff.positions');
    Route::get('/{slug}/committees', [StaffController::class, 'committees'])->name('staff.committees');
    Route::get('/{slug}/training-courses', [StaffController::class, 'trainingCourses'])->name('staff.training_courses');
    Route::get('/{slug}/certificates', [StaffController::class, 'certificates'])->name('staff.certificates');
    Route::get('/{slug}/google-scholar', [StaffController::class, 'googleScholar'])->name('staff.google_scholar');
    Route::get('/{slug}/articles', [StaffController::class, 'articles'])->name('staff.articles');
    Route::get('/{slug}/links', [StaffController::class, 'links'])->name('staff.links');
});

// Arabic Routes
Route::group(['prefix' => 'ar/staff', 'middleware' => 'cache.prevent', 'where' => ['slug' => '[^/]+']], function () {
    Route::get('/{slug}', [StaffArController::class, 'show'])->name('staff_home_ar');
    Route::get('/{slug}/cv', [StaffArController::class, 'cv'])->name('staff.cv_ar');
    Route::get('/{slug}/scientific-papers', [StaffArController::class, 'scientificPapers'])->name('staff.scientific_papers_ar');
    Route::get('/{slug}/books', [StaffArController::class, 'books'])->name('staff.books_ar');
    Route::get('/{slug}/running-projects', [StaffArController::class, 'runningProjects'])->name('staff.running_projects_ar');
    Route::get('/{slug}/courses', [StaffArController::class, 'courses'])->name('staff.courses_ar');
    Route::get('/{slug}/community-service', [StaffArController::class, 'communityService'])->name('staff.communityservice_ar');
    Route::get('/{slug}/workshops', [StaffArController::class, 'workshops'])->name('staff.workshops_ar');
    Route::get('/{slug}/supervising-projects', [StaffArController::class, 'supervisingProjects'])->name('staff.supervising_projects_ar');
    Route::get('/{slug}/research-topics', [StaffArController::class, 'researchTopics'])->name('staff.research_topics_ar');
    Route::get('/{slug}/positions', [StaffArController::class, 'positions'])->name('staff.positions_ar');
    Route::get('/{slug}/committees', [StaffArController::class, 'committees'])->name('staff.committees_ar');
    Route::get('/{slug}/training-courses', [StaffArController::class, 'trainingCourses'])->name('staff.training_courses_ar');
    Route::get('/{slug}/certificates', [StaffArController::class, 'certificates'])->name('staff.certificates_ar');
    Route::get('/{slug}/google-scholar', [StaffArController::class, 'googleScholar'])->name('staff.google_scholar_ar');
    Route::get('/{slug}/articles', [StaffArController::class, 'articles'])->name('staff.articles_ar');
    Route::get('/{slug}/links', [StaffArController::class, 'links'])->name('staff.links_ar');
});
