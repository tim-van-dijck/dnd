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
Auth::routes(['verify' => true]);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', 'PageController@index');
    Route::get('/races', 'Character\RaceController@index');

    Route::get('/permissions', 'Campaign\RoleController@permissions');
    Route::resource('/campaigns', 'Campaign\CampaignController');
    Route::group(['prefix' => '/campaign'], function () {
        Route::resource('locations', 'Campaign\LocationController')->except(['create', 'edit']);
        Route::resource('characters', 'Character\CharacterController')->except(['create', 'edit']);
        Route::resource('quests', 'Campaign\QuestController')->except(['create', 'edit']);
        Route::post('quests/{questId}/objectives/{objectiveId}/toggle', 'Campaign\QuestController@toggleObjectiveStatus');
        Route::resource('notes', 'Campaign\NoteController')->except(['create', 'edit']);
        Route::resource('roles', 'Campaign\RoleController')->except(['create', 'edit']);
        Route::resource('users', 'Campaign\UserController')->except(['create', 'edit']);
    });
});