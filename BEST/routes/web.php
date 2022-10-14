<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\CollegesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::group(['middleware' => 'auth', 'verified'], function() {	
// 	Route::resource('college', CollegesController::class);	
// });
require __DIR__.'/auth.php';

Route::get('refresh-csrf', function(){ 
    session()->regenerate();
    return csrf_token(); 
});
Route::get('/clear-cache', function() {
    Artisan::call('config:cache');
    Artisan::call('view:cache');
    Artisan::call('cache:clear');
    Artisan::call('storage:link');
    return "Cache is cleared";
});
