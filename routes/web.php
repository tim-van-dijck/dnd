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

Route::get('{token}/register', 'InviteController@invitation')->name('invitation');
Route::post('{token}/register', 'InviteController@registerInvitation')->name('register-invite');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', 'PageController@index');
    Route::get('/races', 'Character\ApiController@races');
    Route::get('/classes', 'Character\ApiController@classes');
    Route::get('/languages', 'Character\ApiController@languages');
    Route::get('/backgrounds', 'Character\ApiController@backgrounds');
    Route::get('/spells', 'Magic\SpellController@index');

    Route::get('/profile', 'UserController@profile')->name('profile.index');
    Route::post('/profile', 'UserController@save')->name('profile.save');

    Route::get('/permissions', 'Campaign\RoleController@permissions');
    Route::resource('/campaigns', 'Campaign\CampaignController');
    Route::group(['prefix' => '/campaign'], function () {
        Route::get('/', 'Campaign\CampaignController@currentCampaign');
        Route::get('/search', 'Campaign\CampaignController@search');
        Route::get('/logs', 'Campaign\CampaignController@logs');
        Route::resource('locations', 'Campaign\LocationController')->except(['create', 'edit']);
        Route::resource('characters', 'Character\CharacterController')->except(['create', 'edit']);
        Route::get('characters/{character}/sheet', 'Character\CharacterController@sheet');
        Route::resource('journal', 'Campaign\JournalController')
            ->except(['create', 'edit'])
            ->parameters(['journal' => 'journalEntry']);
        Route::resource('quests', 'Campaign\QuestController')->except(['create', 'edit']);
        Route::post('quests/{questId}/objectives/{objectiveId}/toggle', 'Campaign\QuestController@toggleObjectiveStatus');
        Route::resource('notes', 'Campaign\NoteController')->except(['create', 'edit']);
        Route::resource('roles', 'Campaign\RoleController')->except(['create', 'edit']);
        Route::get('permissions/{entity}/{entityId}', 'Campaign\RoleController@customEntityPermissions')
            ->middleware('can:create,App\Models\Campaign\Role');
        Route::resource('users', 'Campaign\UserController')->except(['create', 'store', 'edit']);
        Route::post('users/invite', 'Campaign\UserController@invite')->name('users.invite');
        Route::get('me', 'Campaign\UserController@me')->name('users.me');
    });
});