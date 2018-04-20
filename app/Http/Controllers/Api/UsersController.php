<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends BaseController
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        return $this->sendResponse($request->user());
    }

    /**
     * Update user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
		return $this->sendResponse($request);
        /*$request->user()->update([
			'name' => $request->name
		]);

		return $this->sendResponse($request->user());*/
    }
}
