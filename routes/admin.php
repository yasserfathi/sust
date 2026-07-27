<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application.
| These routes are loaded by the RouteServiceProvider with the "web" 
| middleware group and usually prefixed with "admin".
|
*/

Route::group(['middleware' => 'cache.prevent'], function () {
    
    // Catch-all route for Vue.js admin panel
    Route::get('/{any?}', function () {
        return view('admin-panel');
    })->where('any', '[\/\w\.-]*');

});