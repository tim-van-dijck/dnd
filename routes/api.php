<?php

use App\Http\Middleware\ValidateCampaignId;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/backgrounds', 'Character\ApiController@backgrounds');
    Route::get('/classes', 'Character\ApiController@classes');
    Route::get('/languages', 'Character\ApiController@languages');
    Route::get('/races', 'Character\ApiController@races');
    Route::get('/spells', 'Magic\SpellController@all');
    Route::get('/permissions', 'Campaign\RoleController@permissions');
    Route::get('me', 'UserController@me')->name('users.me');

    Route::group(['prefix' => '/campaign', 'middleware' => [ValidateCampaignId::class]], function () {
        Route::get('/', 'Campaign\CampaignController@currentCampaign');
        Route::get('/search', 'Campaign\CampaignController@search');
        Route::get('/logs', 'Campaign\CampaignController@logs');
        Route::resource('locations', 'Campaign\LocationController')->except(['create', 'edit']);
        Route::resource('characters', 'Character\CharacterController')->except(['create', 'edit']);

        Route::resource('inventories', 'Campaign\InventoryController')->only(['index', 'show', 'update']);
        Route::post('inventories/{inventory}/items', 'Campaign\InventoryController@addItem');
        Route::delete('inventories/{inventory}/items', 'Campaign\InventoryController@removeItem');
        Route::get('items/{category}', 'Campaign\ItemController@index');

        Route::resource('journal', 'Campaign\JournalController')
            ->except(['create', 'edit'])
            ->parameters(['journal' => 'journalEntry']);
        Route::post('journal/sort', 'Campaign\JournalController@sort');

        Route::post('quests/{questId}/objectives/{objectiveId}/toggle', 'Campaign\QuestController@toggleObjectiveStatus');
        Route::resource('quests', 'Campaign\QuestController')->except(['create', 'edit']);

        Route::resource('notes', 'Campaign\NoteController')->except(['create', 'edit']);
        Route::resource('roles', 'Campaign\RoleController')->except(['create', 'edit']);
        Route::get('permissions/{entity}/{entityId}', 'Campaign\RoleController@customEntityPermissions')
            ->middleware('can:create,App\Models\Campaign\Role');

        Route::resource('users', 'Campaign\UserController')->except(['create', 'store', 'edit']);
        Route::post('users/invite', 'Campaign\UserController@invite')->name('users.invite');
    });

    Route::group(['prefix' => 'admin'], function () {
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
        Route::post('users/invite', 'Admin\UserController@invite');
        Route::post('users/reset-password', 'Admin\UserController@resetPassword');
        Route::resource('users', 'Admin\UserController')
            ->only(['index', 'show', 'update', 'destroy'])
            ->names([
                'index' => 'admin.users.index',
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
