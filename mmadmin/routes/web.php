<?php

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

Route::get('signup', 'SignUpController@index');
Route::get('thankyou/{id}', 'SignUpController@thankYou');

Route::group(['middleware' => 'web'], function () {
    Route::auth();

    // Admin routers
    Route::get('/', 'AdminController@index');
    Route::get('user/{id}', 'AdminController@userView');
    Route::get('claim/{id}', 'AdminController@userClaim');
    Route::get('search', 'AdminController@userSearch');
    Route::get('searchexport', 'AdminController@userSearchExport');
    Route::post('search', 'AdminController@userSearch');
    Route::get('export', 'AdminController@export');
});


