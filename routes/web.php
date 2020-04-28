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
Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/', 'PageController@index');
    Route::get('/races', 'Character\RaceController@index');

    Route::resource('/campaigns', 'Campaign\CampaignController');
    Route::group(['prefix' => '/campaign/{campaignId}/'], function () {
        Route::resource('locations', 'Campaign\LocationController')->except(['create', 'edit']);
        Route::resource('characters', 'Character\CharacterController')->except(['create', 'edit']);
    });
});