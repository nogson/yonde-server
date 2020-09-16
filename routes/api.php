<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/talk/{id}', 'TalkController@getTalkById');
Route::get('/talks', 'TalkController@index');
Route::get('/talks/{id}', 'TalkController@getTalkByTag');
Route::post('/talk', 'TalkController@store');
Route::post('/add_play_count', 'TalkController@addPlayCount');
Route::post('/like', 'TalkController@like');


Route::get('/tags', 'TagController@index');

