<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use JD\Cloudder\Facades\Cloudder;
use File;
use Socialite;

class AuthController extends Controller
{
	/**
	 * Redirect the user to the OAuth Provider.
	 *
	 * @return Response
	 */
	public function redirectToProvider($provider)
	{
		return Socialite::driver($provider)->redirect();
	}

	/**
	 * Obtain the user information from provider.  Check if the user already exists in our
	 * database by looking up their email in the database.
	 * If the user exists, log them in. Otherwise, create a new user then log them in. After that 
	 * redirect them to the authenticated users homepage.
	 *
	 * @return Response
	 */
	public function handleProviderCallback($provider)
	{
		$user = Socialite::driver($provider)->user();
		
		$authUser = $this->findOrCreateUser($user, $provider);
		$authUser->access_token = $user->token;
		$authUser->verified = 1;

		if ($authUser->pic == null) {
			$fileContents = file_get_contents($user->avatar_original);
			$path = public_path('profilepics' . DIRECTORY_SEPARATOR . 'temp');
			File::put($path, $fileContents);
			Cloudder::upload($path, null, [], ['facebook']);
			$authUser->pic = Cloudder::getPublicId();

			unlink($path);
		}
		$authUser->save();
		Auth::login($authUser, true);
		return redirect('/');
	}

	/**
	 * If a user has registered before using social auth, return the user
	 * else, create a new user object.
	 * @param  $user Socialite user object
	 * @param $provider Social auth provider
	 * @return  User
	 */
	public function findOrCreateUser($user, $provider)
	{
		$authUser = User::where('email', $user->email)->first();
		if ($authUser) {
			return $authUser;
		}
	
		return User::create([
			'name'     => $user->name,
			'email'    => $user->email,
			'provider' => $provider,
			'provider_id' => $user->id
		]);
	}
}