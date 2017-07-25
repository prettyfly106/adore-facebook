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

Route::get('/', function () {
    return view('welcome');
});

//Route::resource('user', 'UserController');
//Route::resource('permission', 'PermissionController');

Route::prefix('api/v1/auth')->middleware('cors')->group(function()
    {
        Route::post('login', 'AuthController@postLogin');
        Route::post('fbLogin', 'AuthController@facebookLogin');
        Route::post('register', 'AuthController@postRegister');
        Route::post('emailPassword', 'AuthController@postEmailPassword');
        Route::post('resetPassword', 'AuthController@postResetPassword')->name('password.reset');

    });
Route::prefix('api/v1/permission')->middleware(['cors','jwt.auth'])->group(function()
 {
        Route::get('/', 'PermissionController@index');
        Route::get('/{id}', 'PermissionController@edit');
        Route::post('/', 'PermissionController@store');
        Route::delete('/{id}', 'PermissionController@destroy');
        Route::post('/{id}', 'PermissionController@update');
    });
Route::prefix('api/v1/user-permission')->group(function()
 {
        Route::post('/', 'UserPermissionController@store');
        Route::get('get-granted-permission/{id}', 'UserPermissionController@getPermission');
        Route::get('get-granted-user/{id}', 'UserPermissionController@getGrantedUser');

    });
