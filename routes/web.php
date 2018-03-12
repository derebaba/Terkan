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
Auth::routes();


Route::get('/', 'HomeController@home')->name('home');

Route::middleware(['auth'])->group(function () {
	Route::resource('reviews', 'ReviewsController');
	Route::get('/users/{followee}/follow', 'UsersController@follow')->name('users.follow');
	
	Route::get('/reviews/{review}/like', 'ReviewsController@like');
    //Route::get('/movies/{id?}', 'MoviesController@review');
});

Route::resource('movies', 'MoviesController');
Route::resource('users', 'UsersController');
Route::get('/users/{user}/followers', 'UsersController@followers');

Route::get('/verifyemail/{token}', 'Auth\RegisterController@verify');
Route::get('/search', ['uses' => 'SearchController@search', 'as' => 'search']);
Route::get('/browse/genre/{genre}/{page}', 'SearchController@browseByGenre')->name('browseByGenre');
Route::get('tvs/{id}', 'TvsController@show')->name('tvs.show');

// OAuth Routes
Route::get('auth/{provider}', 'Auth\AuthController@redirectToProvider');
Route::get('auth/{provider}/callback', 'Auth\AuthController@handleProviderCallback');

Route::get('/info/privacypolicy', function () {
	return view('/info/privacypolicy');
});