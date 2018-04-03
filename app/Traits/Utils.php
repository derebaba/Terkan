<?php

namespace App\Traits;

use JD\Cloudder\Facades\Cloudder;
use App\Review;
use App\Models\Reviewable;
use App\User;

use File;

trait Utils {

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

		$pic;
		try {
			$fileContents = file_get_contents($user->avatar_original);
			$path = public_path('profilepics' . DIRECTORY_SEPARATOR . 'temp');
			File::put($path, $fileContents);
			Cloudder::upload($path, null, [], ['facebook']);
			$pic = Cloudder::getPublicId();

			unlink($path);
		} 
		catch (\Exception $e) {
			//	user does not have an avatar
		}

		return User::create([
			'name'     => $user->getName(),
			'email'    => $user->getEmail(),
			'provider' => $provider,
			'provider_id' => $user->getId(),
			'verified' => 1,
			'pic' => $pic
		]);
	}

	public function getReviewables($reviews) {
		$reviewables = $reviews->map(function ($review, $key) {
			return Reviewable::createReviewableFromReview($review);
		});

		return $reviewables;
	}

	public function getReviewablesFromResults($results) {
		$reviewables = collect(array_map(function ($result) {
			return new Reviewable([
				'id' => $result['id'],
				'name' => $result['media_type'] == 'movie' ? $result['original_title'] : $result['original_name'],
				'poster' => $result['poster_path'],
				'type' => $result['media_type'],
				'vote_average' => Review::where('reviewable_type', $result['media_type'])
				->where('reviewable_id', $result['id'])->count() == 0 ? 0 : Review::where('reviewable_type', $result['media_type'])
						->where('reviewable_id', $result['id'])
						->avg('stars'),
				'vote_count' => Review::where('reviewable_type', $result['media_type'])
						->where('reviewable_id', $result['id'])->count()
			]);
		}, $results));

		return $reviewables;
	}
}