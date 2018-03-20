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

// Check role in route middleware
Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => ['auth', 'roles'], 'roles' => 'admin'], function () {
	Route::get('/', ['uses' => 'AdminController@index']);
	Route::resource('permissions', 'PermissionsController');
	Route::resource('roles', 'RolesController');
	Route::resource('users', 'UsersController', ['as' => 'admin']);
});