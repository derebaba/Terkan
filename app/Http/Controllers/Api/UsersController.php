<?php

namespace App\Http\Controllers\API;

use JD\Cloudder\Facades\Cloudder;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateUser;
use App\User;

/**
 * @resource User
 *
 * Get, update users
 */
class UsersController extends BaseController
{
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
	 * @response {
	 * 	'success': true or false,
	 * 	'data': all fields of user,
	 * 	'message': description message (may be empty),
	 * }
     * @param  \App\Http\Requests\UpdateUser  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUser $request)
    {
        $request->user()->update([
			'name' => $request->input('name')
		]);

		return $this->sendResponse($request->user());
    }
}
