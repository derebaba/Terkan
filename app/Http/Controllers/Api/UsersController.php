<?php

namespace App\Http\Controllers\API;

use JD\Cloudder\Facades\Cloudder;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
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
	public function followTv(Request $request, $tv_id) {
		DB::table('tv_user')->insert([
			'user_id' => request()->user()->id,
			'tv_id' => $tv_id
		]);

		return response()->json(null, 204);
	}

	public function unfollowTv(Request $request, $tv_id) {
		DB::table('tv_user')->where([
			'user_id' => request()->user()->id,
			'tv_id' => $tv_id
		])->delete();

		return response()->json(null, 204);
	}

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
    public function update(UpdateUser $request, $id)
    {
		if (request()->user()->id != $id)
			return $this->sendError('You cannot edit this user.', [], 403);
			
        request()->user()->update([
			'name' => $request->input('name')
		]);

		return response()->json(null, 204);
	}
	
	/*
	public function getWatchlist($id) {
		$watchlist = $user->getWatchlist();
		$movies = [];
		$tvs = [];
		
		foreach ($watchlist as $index => $item) {
			if ($item->reviewable_type === 'movie') {
				$movie = Tmdb::getMoviesApi()->getMovie($item->reviewable_id);
				array_push($movies, $movie);
			}
			else {
				$tv = Tmdb::getTvApi()->getTvshow($item->reviewable_id);
				array_push($tvs, $tv);
			}
		}

	}
	*/
}
