<?php

namespace App\Http\Controllers\API;

use JD\Cloudder\Facades\Cloudder;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Review;
use App\Http\Resources\Review as ReviewResource;
use Tmdb\Laravel\Facades\Tmdb;
use App\Http\Requests\UpdateUser;
use App\User;
use App\Http\Resources\User as UserResource;

/**
 * @resource User
 *
 * Get, update users
 */
class UsersController extends BaseController
{
	public function followers($id) {
		return UserResource::collection(User::find($id)->followers);
	}

	public function followings($id) {
		return UserResource::collection(User::find($id)->followings);
	}

	public function followTv(Request $request, $tv_id) {
		try {
			DB::table('tv_user')->insert([
				'user_id' => request()->user()->id,
				'tv_id' => $tv_id
			]);
		} 
		catch (\Illuminate\Database\QueryException $e) {
			if ($e->getCode() == 23505)
				return $this->sendError('User is already following this show.', null, 409);
			
			return $this->sendError('Unknown database error.');
		}
		catch (\Exception $e) {
			return $this->sendError('Unknown error.');
		}
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
		/*
		$reviews = $user->reviews;

		foreach ($reviews as &$review) {
			if ($review['reviewable_type'] == 'movie') {
				$review['reviewable'] = Tmdb::getMoviesApi()->getMovie($review['reviewable_id']);
			}
			else if ($review['reviewable_type'] == 'tv') {
				$review['reviewable'] = Tmdb::getTvApi()->getTvshow($review['reviewable_id']);
			}
		}
		*/
		return $this->sendResponse(ReviewResource::collection($user->reviews));
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
        return new UserResource(User::find($id));
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
			return $this->sendError('You cannot edit this user.', null, 403);
			
        request()->user()->update([
			'name' => $request->input('name')
		]);

		return response()->json(null, 204);
	}
	
	
	public function getWatchlist($id) {
		$watchlist = User::find($id)->getWatchlist();
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

		array_push($movies, $tvs);

		return $this->sendResponse($movies);
	}
	
}
