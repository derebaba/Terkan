<?php

namespace App\Http\Controllers\API;

use JD\Cloudder\Facades\Cloudder;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Review;
use App\Http\Requests\UpdateUser;
use App\User;
use App\Traits\Utils;

/**
 * @resource User
 *
 * Get, update users
 */
class UsersController extends BaseController
{
	use Utils;

	public function reviews($id) {
		$user = User::find($id);
		$reviews = $user->reviews;
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
