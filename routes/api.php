<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

//Route::post('login', ['as' => 'login', 'uses' => 'CustomLogin@userLogin']);


Route::post('admin-login','CustomLogin@userLogin');
Route::post('login','CustomLogin@betUserLogin');
Route::post('logout','CustomLogin@logOut')->middleware('auth:api');
Route::post('change-password','ChangePassword@changePassword')->middleware('auth:api');
Route::group(['prefix' => 'admin/','middleware' => 'auth:api'], function() {
    // Route::post('add-user','Admin\AdminController@addUser');
    Route::post('add-account','Admin\AdminController@addAccount');
    Route::get('list-client/{id}','Admin\AdminController@clientList');
    Route::get('list-user/{id}','Admin\AdminController@userList');
    Route::get('details-user/{id}','Admin\AdminController@userDetails');
    Route::post('update-client/{id}','Admin\AdminController@updateClient');
    Route::post('update-user/{id}','Admin\AdminController@updateUser');
    Route::post('change-client-status/{id}','Admin\AdminController@changeStatusForClients');
    Route::post('change-user-status/{id}','Admin\AdminController@changeStatusForUser');
    Route::post('change-multisign-user/{id}','Admin\AdminController@changeMultiSignUser');
    Route::post('change-multisign-client/{id}','Admin\AdminController@changeMultiSignClient');
    Route::delete('delete-client/{id}','Admin\AdminController@deleteClient');
    Route::delete('delete-user/{id}','Admin\AdminController@deleteUser');

    Route::get('list-controller','Admin\RolesController@getController');
    Route::get('list-roles','Admin\RolesController@getRoles');
    Route::get('details-roles/{id}','Admin\RolesController@rolesDetails');
    Route::post('add-roles','Admin\RolesController@addRoles');
    Route::post('update-role/{id}','Admin\RolesController@updateRole');
    Route::delete('delete-roles/{id}','Admin\RolesController@deleteRole');

    Route::get('search-users','Admin\AdminController@searchUser');

    Route::post('add-sport','Admin\SportsController@addSports');
    Route::get('list-sport','Admin\SportsController@sportList');
    Route::get('list-sporttype/{id?}','Admin\SportsController@sportsType');
    Route::get('details-sport/{id}','Admin\SportsController@sportDetails');
    Route::post('update-sport/{id}','Admin\SportsController@updateSport');
    Route::post('change-sport-status/{id}','Admin\SportsController@changeStatus');
    Route::delete('delete-sport/{id}','Admin\SportsController@deleteSport');

    Route::get('list-match/{id?}','Admin\MatchController@listMatch');
    Route::post('add-match','Admin\MatchController@addMatch');
    Route::post('update-match/{id}','Admin\MatchController@updateMatch');
    Route::post('advance-update-match/{id}','Admin\MatchController@advanceUpdateMatch');
    Route::delete('delete-match/{id}','Admin\MatchController@deleteMatch');
    Route::post('change-status/{id}', 'Admin\MatchController@changeStatusForAll');

    Route::get('list-competetion/{id?}/{sport_type?}','Admin\CompetetionController@competetionList');
    Route::post('add-competetion','Admin\CompetetionController@addCompetetion');
    Route::get('details-competetion/{id}','Admin\CompetetionController@competetionDetails');
    Route::post('update-competetion/{id}','Admin\CompetetionController@updateCompetetion');
    Route::post('advance-update-competetion/{id}','Admin\CompetetionController@advanceUpdateCompetetion');
    Route::post('change-status-competetion/{id}','Admin\CompetetionController@statusChange');
    Route::delete('delete-competetion/{id}','Admin\CompetetionController@deleteCompetetion');

    Route::get('list-team/{id?}','Admin\TeamController@teamList');
    Route::post('add-team','Admin\TeamController@addTeam');
    Route::post('update-team/{id}','Admin\TeamController@updateTeam');
    Route::post('advance-update-team/{id}','Admin\TeamController@advanceUpdateTeam');
    Route::post('change-team-status/{id}','Admin\TeamController@statusChangeTeam');
    Route::delete('delete-team/{id}','Admin\TeamController@deleteTeam');

    Route::post('add-market','Admin\MarketController@addMarket');
    Route::post('update-market/{id}','Admin\MarketController@updateMarket');
    Route::get('list-market/{id?}','Admin\MarketController@listMarket');
    Route::delete('delete-market/{id}','Admin\MarketController@deleteMarket');
    Route::post('change-status-market/{id}', 'Admin\MarketController@changeStatusForAll');
    Route::post('locked-market/{id}', 'Admin\MarketController@lockedMarket');
});
    
Route::group(['prefix' => '','middleware' => 'auth:bet_api'], function(){
    Route::get('list-sport-header','Admin\SportsController@sportListForHeader');
    Route::get('list-sport-sidebar','Admin\SportsController@sportListForSidebar');
    Route::get('list-match/{id?}','Admin\MatchController@ClientListMatch');
    
    Route::get('list-match-cricket','Admin\MatchController@listMatchForCricket');
    Route::get('list-match-football','Admin\MatchController@listMatchForFootball');
    Route::get('list-match-tennis','Admin\MatchController@listMatchForTennis');
    Route::get('match-details/{id}','Admin\MatchController@matchDetails');
});
