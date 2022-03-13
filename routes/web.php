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

use Illuminate\Support\Facades\Route;

Auth::routes(['verify' => true]);

Route::get('{token}/register', 'InviteController@invitation')->name('invitation');
Route::post('{token}/register', 'InviteController@registerInvitation')->name('register-invite');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', 'PageController@index');
    Route::get('/races', 'Character\ApiController@races');
    Route::get('/classes', 'Character\ApiController@classes');
    Route::get('/languages', 'Character\ApiController@languages');
    Route::get('/backgrounds', 'Character\ApiController@backgrounds');
    Route::get('/spells', 'Magic\SpellController@all');

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

        Route::get('inventories', 'Campaign\InventoryController@index');
        Route::get('inventories/{inventory}', 'Campaign\InventoryController@show');
        Route::put('inventories/{inventory}', 'Campaign\InventoryController@update');
        Route::post('inventories/{inventory}/items', 'Campaign\InventoryController@addItem');
        Route::delete('inventories/{inventory}/items', 'Campaign\InventoryController@removeItem');

        Route::get('items/{category}', 'Campaign\ItemController@index');

        Route::resource('journal', 'Campaign\JournalController')
            ->except(['create', 'edit'])
            ->parameters(['journal' => 'journalEntry']);
        Route::post('journal/sort', 'Campaign\JournalController@sort');
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

    Route::group(['prefix' => 'admin'], function () {
        Route::get('/', 'AdminController@index')->name('admin');
        Route::resource('campaigns', 'Admin\CampaignController')
            ->only(['index', 'store', 'show', 'update', 'destroy'])->names([
                'index' => 'admin.campaigns.index',
                'store' => 'admin.campaigns.store',
                'show' => 'admin.campaigns.show',
                'update' => 'admin.campaigns.update',
                'destroy' => 'admin.campaigns.destroy',
            ]);
        Route::get('races/traits', 'Admin\RaceController@traits')->name('admin.races.traits');
        Route::resource('races', 'Admin\RaceController')
            ->only(['index', 'store', 'show', 'update', 'destroy'])
            ->names([
                'index' => 'admin.races.index',
                'store' => 'admin.races.store',
                'show' => 'admin.races.show',
                'update' => 'admin.races.update',
                'delete' => 'admin.races.delete',
            ]);
        Route::resource('users', 'Admin\UserController')
            ->only(['index', 'store', 'show', 'update', 'destroy'])
            ->names([
                'index' => 'admin.users.index',
                'store' => 'admin.users.store',
                'show' => 'admin.users.show',
                'update' => 'admin.users.update',
                'delete' => 'admin.users.delete',
            ]);
        Route::resource('spells', 'Magic\SpellController')
            ->only(['index', 'store', 'show', 'update', 'destroy'])
            ->names([
                'index' => 'admin.spells.index',
                'store' => 'admin.spells.store',
                'show' => 'admin.spells.show',
                'update' => 'admin.spells.update',
                'delete' => 'admin.spells.delete',
            ]);
        Route::resource('proficiencies', 'Admin\ProficiencyController')
            ->only(['index'])->names(['index' => 'admin.proficiencies.index']);
    });
});