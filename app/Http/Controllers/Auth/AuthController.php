<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use JD\Cloudder\Facades\Cloudder;
use File;
use Socialite;
use App\Traits\Utils;

class AuthController extends Controller
{
	use Utils;
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
		
		if (empty($user)) return redirect("/")->withErrors(["You denied permissions."]);

		$authUser = $this->findOrCreateUser($user, $provider);

		$authUser->access_token = $user->token;
		if ($authUser->pic == null) {
			try {
				$fileContents = file_get_contents($user->avatar_original);
				$path = public_path('profilepics' . DIRECTORY_SEPARATOR . 'temp');
				File::put($path, $fileContents);
				Cloudder::upload($path, null, [], [$provider]);
				$authUser->pic = Cloudder::getPublicId();
				unlink($path);
			} 
			catch (\Exception $e) {
				//	user does not have an avatar
			}
		}
		
		$authUser->save();
		$authUser->update([
			'provider' => $provider,
			'provider_id' => $user->getId(),
			'provider_name' => $user->getName(),
			'verified' => 1,
		]);

		Auth::login($authUser, true);
		return redirect('/');
	}

}