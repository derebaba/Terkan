<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Http\Resources\Review as ReviewResource;
use Tmdb\Laravel\Facades\Tmdb;
use App\Http\Requests\UpdateUser;
use App\Models\User;
use App\Http\Resources\User as UserResource;
use App\Contracts\Repositories\UserRepository;

/**
 * @resource User
 *
 * Get, update users
 */
class UsersController extends BaseController
{
	/**
     * @var UserRepository
     */
    protected $repository;

    public function __construct(UserRepository $repository){
        $this->repository = $repository;
	}
	
	public function followUser(Request $request, $id) {
		if (User::find($id)) {
			request()->user()->follow($id);

			return response()->json(null, 204);
		}
		else {
			return $this->sendError('The user with id=' . $id . ' does not exist.');
		}
	}

	public function unfollowUser(Request $request, $id) {
		if (User::find($id)) {
			request()->user()->unfollow($id);
			
			return response()->json(null, 204);
		}
		else {
			return $this->sendError('The user with id=' . $id . ' does not exist.');
		}
	}

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

	public function search(Request $request) {
		return response()->json($this->repository->all());
	}

	public function self() 
	{
        return $this->sendResponse(User::find(request()->user()->id));
	}
	
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json($this->repository->find($id));
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
