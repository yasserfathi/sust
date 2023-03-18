<?php

use Illuminate\Support\Facades\Route;


// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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
