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

	Route::post('/users/{id}/follow', 'UsersController@followUser');
	Route::delete('/users/{id}/follow', 'UsersController@unfollowUser');
	Route::post('/tvs/{tv_id}/follow', 'UsersController@followTv');
	Route::delete('/tvs/{tv_id}/follow', 'UsersController@unfollowTv');
	Route::patch('users/{id}', 'UsersController@update');

	Route::get('/reviews/feed', 'ReviewsController@getNewsFeed');
});
Route::get('users', 'UsersController@search');
Route::get('users/{id}', 'UsersController@show');
Route::get('users/{id}/reviews', 'UsersController@reviews');
Route::get('users/{id}/followers', 'UsersController@followers');
Route::get('users/{id}/followings', 'UsersController@followings');
Route::get('users/{id}/watchlist', 'UsersController@getWatchlist');

Route::get('/movies/popular', 'MoviesController@popular');
