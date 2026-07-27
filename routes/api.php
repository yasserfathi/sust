<?php

use App\Models\College;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\AcademicProgramController;
use App\Http\Controllers\Admin\AcademicCourseController;
use App\Http\Controllers\Admin\AdController;
use App\Http\Controllers\Admin\AdEnController;
use App\Http\Controllers\Admin\AlbumController;
use App\Http\Controllers\Admin\AlbumPhotoController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\CalendarEnController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CategoryPageController;
use App\Http\Controllers\Admin\CategoryPageUserController;
use App\Http\Controllers\Admin\CollegesController;
use App\Http\Controllers\Admin\CollegeGalleryController;
use App\Http\Controllers\Admin\CollegeStrategicController;
use App\Http\Controllers\Admin\DeanOfCollegeController;
use App\Http\Controllers\Admin\DepartmentsController;
use App\Http\Controllers\Admin\HeadOfDepartmentController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\NewsEnController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\StaffAcademicController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StaffAlbumPhotoController;
use App\Http\Controllers\Admin\StaffEmploysController;
use App\Http\Controllers\Admin\StaffResumeController;
use App\Http\Controllers\Admin\UserUploadFileController;
use App\Http\Controllers\Admin\ViceChancellorController;
use App\Http\Controllers\Admin\UniversityOfficialRankController;
use App\Http\Controllers\Admin\WorkshopController;
use App\Http\Controllers\Admin\WorkshopEnController;
use App\Http\Controllers\Admin\StudentAuthController;
use App\Http\Controllers\StudentDataController;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::group(['prefix' => 'auth'], routes: function ($router) {
    Route::post('student_data', [StudentDataController::class, 'index'])->name('student_data');
    Route::post('register', [RegisteredUserController::class, 'store'])->name('register');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login');
    // Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    // Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

// Student Authentication
Route::post('student/login', [App\Http\Controllers\Admin\StudentAuthController::class, 'login'])->name('student.login');
Route::post('student/register', [App\Http\Controllers\Admin\StudentAuthController::class, 'register'])->name('student.register');
Route::post('student/setup-password', [App\Http\Controllers\Admin\StudentAuthController::class, 'setupPassword'])->name('student.setup-password');
Route::post('student/forgot-password', [App\Http\Controllers\Admin\StudentAuthController::class, 'forgotPassword'])->name('student.forgot-password');
Route::post('student/reset-password', [App\Http\Controllers\Admin\StudentAuthController::class, 'resetPassword'])->name('student.reset-password');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('auth/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/user', function (Illuminate\Http\Request $request) {
        return $request->user();
    })->name('user');

    Route::get('users-counts', function () {
        return response()->json(['result' => User::where('active', 1)->count(), 'status' => 200]);
    })->name('users.counts');

    Route::get('college-counts', function () {
        $counts = College::selectRaw("
            SUM(college_type = 'college') as colleges_count,
            SUM(college_type = 'center') as centers_count,
            SUM(college_type = 'deanship') as deanships_count,
            COUNT(*) as total
        ")->first();

        return response()->json([
            'colleges_count' => $counts->colleges_count,
            'centers_count' => $counts->centers_count,
            'deanships_count' => $counts->deanships_count,
        ]);
    })->name('college.counts');

    Route::post('head_of_department/head', [HeadOfDepartmentController::class, 'head'])->name('department_head');

    Route::get('user_pages/{page?}', [AuthenticatedSessionController::class, 'userPages'])->name('userPages');

    //colleges
    Route::post('departments/list', [DepartmentsController::class, 'list'])->name('departments.list');
    Route::get('category/list', [CategoryController::class, 'list'])->name('category.list');
    Route::get('colleges/list', [CollegesController::class, 'list'])->name('colleges.list');
    Route::post('albums/list', [AlbumController::class, 'list'])->name('albums.list');
    Route::post('album_photos/list', [AlbumPhotoController::class, 'list'])->name('album_photos.list');
    Route::get('album_photos/list_photos/{id}', [AlbumPhotoController::class, 'list_photos'])->name('album_photos.list_photos');
    Route::post('users/list', [UserController::class, 'list'])->name('users.list');

    Route::put('ads/priority/{id}', [AdController::class, 'priority'])->name('ads_priority');
    Route::put('ads_en/priority/{id}', [AdEnController::class, 'priority'])->name('ads_en_priority');
    Route::put('news/priority/{id}', [NewsController::class, 'priority'])->name('news_priority');
    Route::put('news_en/priority/{id}', [NewsEnController::class, 'priority'])->name('news_en_priority');

    // PageController
    Route::get('page_categories/list', [PageController::class, 'categoryList'])->name('page_categories.list');
    Route::get('page/{slug}', [PageController::class, 'index'])->name('page.index');
    Route::get('page/{slug}/{id}', [PageController::class, 'show'])->name('page.show');
    Route::post('page/{slug}', [PageController::class, 'store'])->name('page.store');
    Route::put('page/{slug}/{id}', [PageController::class, 'update'])->name('page.update');
    Route::delete('page/{slug}/{page}', [PageController::class, 'destroy'])->name('page.destroy');

    // category_page_user/list
    Route::post('category_page/list', [CategoryPageController::class, 'list'])->name('category_pages_list');

    Route::apiResources([
        'academic_programs' => AcademicProgramController::class,
        'academic_courses' => AcademicCourseController::class,
        'ads' => AdController::class,
        'ads_en' => AdEnController::class,
        'albums' => AlbumController::class,
        'album_photos' => AlbumPhotoController::class,
        'calendar' => CalendarController::class,
        'calendar_en' => CalendarEnController::class,
        'category' => CategoryController::class,
        'category_page' => CategoryPageController::class,
        'colleges' => CollegesController::class,
        'dean_of_college' => DeanOfCollegeController::class,
        'departments' => DepartmentsController::class,
        'head_of_department' => HeadOfDepartmentController::class,
        'news' => NewsController::class,
        'news_en' => NewsEnController::class,
        'users' => UserController::class,
        'staff_academic/staff_resume' => StaffResumeController::class,
        'staff_academic/staff_album_photos' => StaffAlbumPhotoController::class,
        'staff_employ' => StaffEmploysController::class,
        'vice_chancellors' => ViceChancellorController::class,
        'university_official_ranks' => UniversityOfficialRankController::class,
        'workshops' => WorkshopController::class,
        'workshops_en' => WorkshopEnController::class,
        'college_strategics' => CollegeStrategicController::class,
    ]);

    Route::apiResource('college_galleries', CollegeGalleryController::class)->only([
        'index',
        'store',
        'show',
        'update',
    ]);


    // StaffAcademicController
    Route::get('staff_academic/{slug}', [StaffAcademicController::class, 'index'])->name('staff_academic.index');
    Route::get('staff_academic/{slug}/{staff_academic}', [StaffAcademicController::class, 'show'])->name('staff_academic.show');
    Route::post('staff_academic/{slug}', [StaffAcademicController::class, 'store'])->name('staff_academic.store');
    Route::put('staff_academic/{slug}/{id}', [StaffAcademicController::class, 'update'])->name('staff_academic.update');
    Route::delete('staff_academic/{slug}/{staff_academic}', [StaffAcademicController::class, 'destroy'])->name('staff_academic.destroy');


    // CategoryPageUserController
    Route::get('category_page_user/{user_id}', [CategoryPageUserController::class, 'index'])->name('category_page_user.index');
    Route::get('category_page_user/{user_id}/{category_id}', [CategoryPageUserController::class, 'show'])->name('category_page_user.show');
    Route::post('category_page_user', [CategoryPageUserController::class, 'store'])->name('category_page_user.store');
    Route::put('category_page_user', [CategoryPageUserController::class, 'update'])->name('category_page_user.update');

    Route::delete('category_page_user/{user_id}/{category_id}', [CategoryPageUserController::class, 'destroy'])->name('category_page_user.destroy');
    //UserUploadFileController
    Route::post('/user-upload-data', [UserUploadFileController::class, 'store'])->name(name: 'user-upload-data.store');

    // Student email confirmation
    Route::post('student/confirm-email', [StudentAuthController::class, 'confirmEmail'])->name('student.confirm-email');

});
