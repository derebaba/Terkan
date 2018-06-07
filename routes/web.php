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

// Authentication Routes...
Route::get('login', [
	'as' => 'login',
	'uses' => 'Auth\LoginController@showLoginForm'
]);
Route::post('/login' , 'Auth\LoginController@authenticate');
Route::post('logout', [
	'as' => 'logout',
	'uses' => 'Auth\LoginController@logout'
]);

// Password Reset Routes...
Route::post('password/email', [
	'as' => 'password.email',
	'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail'
]);
Route::get('password/reset', [
	'as' => 'password.request',
	'uses' => 'Auth\ForgotPasswordController@showLinkRequestForm'
]);
Route::post('password/reset', [
	'as' => '',
	'uses' => 'Auth\ResetPasswordController@reset'
]);
Route::get('password/reset/{token}', [
	'as' => 'password.reset',
	'uses' => 'Auth\ResetPasswordController@showResetForm'
]);

// Registration Routes...
Route::get('register', [
	'as' => 'register',
	'uses' => 'Auth\RegisterController@showRegistrationForm'
]);
Route::post('register', [
	'as' => '',
	'uses' => 'Auth\RegisterController@register'
]);


Route::get('/', 'HomeController@home')->name('home');

Route::middleware(['auth'])->group(function () {
	Route::resource('reviews', 'ReviewsController');
	Route::get('/users/{followee}/follow', 'UsersController@follow')->name('users.follow');
	
	Route::get('/reviews/{review}/like', 'ReviewsController@like');
	//Route::get('/movies/{id?}', 'MoviesController@review');
	Route::put('/addToWatchlist', 'UsersController@addToWatchlist')->name('users.addToWatchlist');
	Route::put('/removeFromWatchlist', 'UsersController@removeFromWatchlist')->name('users.removeFromWatchlist');

	Route::post('/tvs/{tv_id}/follow', 'UsersController@followTv')->name('users.followTv');
	Route::post('/tvs/{tv_id}/unfollow', 'UsersController@unfollowTv')->name('users.unfollowTv');
});

Route::resource('movies', 'MoviesController');

//	TV
Route::get('tvs/{id}', 'TvsController@show')->name('tvs.show');
Route::get('tvs/{id}/season/{season_number?}', 'TvsController@getSeason')->name('tvs.season');

//	Users
Route::resource('users', 'UsersController')->except(['index']);
Route::get('/users/{user}/followers', 'UsersController@followers');
Route::get('/users/{user}/watchlist', 'UsersController@watchlist')->name('users.watchlist');

Route::get('/verifyemail/{token}', 'Auth\RegisterController@verify');

//	Search
Route::get('/search/movie', ['uses' => 'SearchController@searchMovies', 'as' => 'search.movie']);
Route::get('/search/people', ['uses' => 'SearchController@searchPeople', 'as' => 'search.people']);
Route::get('/search/tv', ['uses' => 'SearchController@searchTv', 'as' => 'search.tv']);
Route::get('/browse/genre/{genre}/{page}', 'SearchController@browseByGenre')->name('browseByGenre');
Route::get('/browse/tv/{genre}/{page}', 'SearchController@browseTvByGenre')->name('browseTvByGenre');
Route::get('/search/autocomplete', 'SearchController@searchAutocomplete');

//	Discover
Route::get('/discover/movies', ['uses' => 'SearchController@discoverMovies', 'as' => 'discover.movies']);
Route::get('/discover/tv', ['uses' => 'SearchController@discoverTv', 'as' => 'discover.tv']);

// OAuth Routes
Route::get('auth/{provider}', 'Auth\AuthController@redirectToProvider');
Route::get('auth/{provider}/callback', 'Auth\AuthController@handleProviderCallback');

Route::group(['prefix' => 'info'], function() {
	Route::get('/contact', function () {
		return view('info.contact');
	});
	Route::get('/cookiepolicy', function () {
		return view('info.cookiepolicy');
	});
	Route::get('/terms-and-conditions', function () {
		return view('info.terms-and-conditions');
	});
});

// Admin routes
Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => ['auth', 'roles'], 'roles' => 'admin'], function () {
	Route::get('/', ['uses' => 'AdminController@index']);
	Route::get('/generator', function () {
		return view('vendor.laravel-admin.generator');
	});
	Route::resource('permissions', 'PermissionsController');
	Route::resource('roles', 'RolesController');
	Route::resource('users', 'UsersController', ['as' => 'admin']);
});

Route::post('/email/pipe', 'MailController@pipeEmail');
