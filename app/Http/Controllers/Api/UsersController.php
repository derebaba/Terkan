<?php

namespace App\Http\Controllers\API;

use JD\Cloudder\Facades\Cloudder;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Review;
use Tmdb\Laravel\Facades\Tmdb;
use App\Http\Requests\UpdateUser;
use App\User;

/**
 * @resource User
 *
 * Get, update users
 */
class UsersController extends BaseController
{
	public function reviews($id) {
		$user = User::find($id);
		$reviews = $user->reviews;

		foreach ($reviews as &$review) {
			if ($review['reviewable_type'] == 'movie') {
				$review['reviewable'] = Tmdb::getMoviesApi()->getMovie($review['reviewable_id']);
			}
			else if ($review['reviewable_type'] == 'tv') {
				$review['reviewable'] = Tmdb::getTvApi()->getTvshow($review['reviewable_id']);
			}
		}
		return $this->sendResponse($user->reviews);
	}

	public function self() 
	{
		$user = User::find(request()->user()->id);
		$user->pic = Cloudder::secureShow($user->pic);
        return $this->sendResponse($user);
	}
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
		$user = User::find($id);
		$user->pic = Cloudder::secureShow($user->pic);
        return $this->sendResponse($user->only([
			'id', 'name', 'pic'
		]));
    }

    /**
     * Update user.
     *
     * @param  \App\Http\Requests\UpdateUser  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUser $request)
    {
        $request->user()->update([
			'name' => $request->input('name')
		]);

		return response()->json(null, 204);
    }
}
