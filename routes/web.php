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

Route::get('/game', function () {
    dd(config("app.trello_api_end_point"));
});



Route::get('/','LoginController@trelloLogin' )->name('login');
Route::post('/login','LoginController@checkTrelloLogin' )->name('check-trello-login');
Route::post('/ajax_login','LoginController@ajax_login' );
Route::get('/add-data-in-session','LoginController@addSession' )->name('add-session');
Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard','DashboardController@mainDashboard' )->name('main-dashboard');
    Route::get('/boards','BoardsController@showBoards' )->name('show-board');
    Route::delete('/ajax_delete/{board}','BoardsController@distory');
    Route::get('/save-boards','BoardsController@saveBoards' )->name('save-board');
    Route::get('/update-board','BoardsController@updateBoard' )->name('update-board');
    Route::get('/lists/{id}','ListController@trelloList')->name('lists');
    Route::post('/register-web-hook','HookController@registerHook')->name('register_hook');
    Route::delete('/register-web-hook','HookController@removeHook')->name('register_hook');
    Route::post('/delete_hook','HookController@deleteHook')->name('delete_hook');
    Route::put('/disable_check_list/{list_id}','ListController@updateListcheckList')->name('disable_check_list');
    Route::put('/enable_check_list/{list_id}','ListController@updateListcheckList')->name('enable_check_list');
    Route::delete('/disable_hook/{board_id}','HookController@removeHook')->name('disable_hook');
    Route::put('/enable_report/{board_id}','HookController@registerHook')->name('enable_report');
    Route::get('/logout','LoginController@logout' )->name('logout');
});


Route::get('/testwebhook','TrelloListController@postGuzzleRequest' )->name('testwebhook');








