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

Route::post('register', 'RegisterController@register');

Route::middleware('auth:api')->group( function () {
	Route::get('user', 'UsersController@self');
	Route::patch('users/update', 'UsersController@update');

	//Route::apiResource('reviews', 'ReviewsController')->except(['index', 'show']);
});
Route::get('users/{id}', 'UsersController@show');
Route::get('users/{id}/reviews', 'UsersController@reviews');

Route::get('/movies/popular', 'MoviesController@popular');
