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

    Route::resource('fab', 'FaBController')->except(['create', 'show', 'edit']);

    Route::resource('trx_room', 'TrxRoomController')->except(['create', 'show', 'edit']);

    Route::resource('trx_fab', 'TrxFabController')->except(['index', 'create', 'show', 'edit']);

    Route::resource('trx_sptpd', 'TaxSPTPDController')->except(['create', 'show', 'edit']);

    Route::get('trx_rev', 'TrxRoomController@revenue')->name('trx_rev.index');

    Route::post('trx_sptpd/approve/{id}', 'TaxSPTPDController@approve')->name('trx_sptpd.approve');

    Route::post('trx_sptpd/status/{id}', 'TaxSPTPDController@status')->name('trx_sptpd.status');

});
