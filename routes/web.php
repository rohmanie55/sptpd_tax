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

Auth::routes(['register' => false, 'reset' => false]);


Route::group(['middleware' => 'auth'], function()
{
    Route::get('/', 'DashboardController')->name('dashboard');

    Route::resource('user', 'UserController');

    Route::resource('company', 'CompanyController');

    Route::resource('room', 'RoomController');

    Route::resource('f&b', 'RoomController');

    Route::resource('trx_room', 'TrxRoomController');

    Route::resource('trx_f&b', 'TrxFabController');

    Route::resource('trx_rev', 'TrxFabController');

    Route::resource('trx_sptpd', 'TrxFabController');

});
