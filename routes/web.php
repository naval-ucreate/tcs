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

//dashboard
Route::get('/dashboard','DashboardController@mainDashboard' )->name('main-dashboard');

//boards
Route::get('/boards','BoardsController@showBoards' )->name('show-board');



Route::get('/add-data-in-session','LoginController@addSession' )->name('add-session');
Route::group(['middleware' => ['TrelloOauthCheck']], function () {
    Route::get('/boards','BoardsController@showBoards' )->name('show-board');
});









Route::get('/save-boards','BoardsController@saveBoards' )->name('save-board');