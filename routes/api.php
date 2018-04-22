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

//Route::post('register', 'API\RegisterController@register');


Route::middleware('auth:api')->group( function () {
	Route::patch('users/update', 'UsersController@update');
});
Route::get('users/{id}', 'UsersController@show');

Route::get('/movies/popular', 'MoviesController@popular');
