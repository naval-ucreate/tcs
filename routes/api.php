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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::any('/test-web-hook','TrelloListController@testWebHook')->name('test_web_hook');
Route::post('/list-trigger/{board_id}','HookController@listenTrigger')->name('list_trigger');
Route::get('/list-trigger/{board_id}','HookController@ok')->name('list_trigger');
Route::post('/register-web-hook/{board_id}','HookController@RegisterHook')->name('register_hook');