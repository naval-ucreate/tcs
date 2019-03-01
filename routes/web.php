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
Route::get('/logout','LoginController@logout' )->name('logout');
Route::post('/login','LoginController@checkTrelloLogin' )->name('check-trello-login');
Route::post('/ajax_login','LoginController@ajax_login' );
Route::get('/add-data-in-session','LoginController@addSession' )->name('add-session');

Route::group(['middleware' => ['TrelloOauthCheck']], function () {
    Route::get('/dashboard','DashboardController@mainDashboard' )->name('main-dashboard');
    Route::get('/boards','BoardsController@showBoards' )->name('show-board');
    Route::delete('/ajax_delete/{board}','BoardsController@distory');
    Route::get('/save-boards','BoardsController@saveBoards' )->name('save-board');
    Route::get('/update-board','BoardsController@updateBoard' )->name('update-board');
    Route::get('/lists/{id}','BoardsController@TrelloList')->name('lists');
});








