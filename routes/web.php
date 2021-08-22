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

    Route::resource('user', 'UserController')->except(['create', 'show', 'edit']);

    Route::resource('company', 'CompanyController')->except(['create', 'show', 'edit']);

    Route::resource('room', 'RoomController')->except(['create', 'show', 'edit']);

    Route::resource('guest', 'GuestController')->except(['create', 'show', 'edit']);

    Route::resource('f&b', 'FaBController')->except(['create', 'show', 'edit']);

    Route::resource('trx_room', 'TrxRoomController')->except(['create', 'show', 'edit']);

    Route::resource('trx_f&b', 'TrxFabController');

    Route::resource('trx_rev', 'TrxFabController');

    Route::resource('trx_sptpd', 'TrxFabController');

});
