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
    Route::get('/admin/{adminPage?}', 'AdminController@index')->where('adminPage', '.*')->name('admin');

    Route::get('/profile', 'UserController@profile')->name('profile.index');
    Route::post('/profile', 'UserController@save')->name('profile.save');

    Route::get('/permissions', 'Campaign\RoleController@permissions');
    Route::resource('/campaigns', 'Campaign\CampaignController');
});