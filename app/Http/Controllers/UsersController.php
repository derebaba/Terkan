<?php

namespace App\Http\Controllers;

use App\Review;
use App\User;
use App\Traits\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Laracasts\Utilities\JavaScript\JavaScriptFacade as JavaScript;
use Intervention\Image\Facades\Image;
use JD\Cloudder\Facades\Cloudder;
use Tmdb\Helper\ImageHelper;

class UsersController extends Controller
{
	use Utils;
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$users = User::all();
		return view('users.index', ['users' => $users]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\User  $user
	 * @return \Illuminate\Http\Response
	 */
	public function show(User $user)
	{
		$reviews = $user->reviews->take(-5)->reverse()->values();
		$reviewables = $this->getReviewables($reviews);
		$self = false;	//	kendi profiline mi bakıyor
		if (Auth::check()) {
			if (Auth::user()->id == $user->id)	
				$self = true;
		}

		JavaScript::put([
			'stars' => $reviews->pluck('stars')
		]);
		
		//	save image url in pic temporarily
		$user->pic = Cloudder::secureShow($user->pic);
		dd([file_exists(trim($user->pic, '"')), trim($user->pic, '"'), $user->pic]);
		return view('users.show', ['user' => $user, 
			'reviewables' => $reviewables,
			'reviews' => $reviews, 
			'self' => $self
		]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\User  $user
	 * @return \Illuminate\Http\Response
	 */
	public function edit(User $user)
	{
		$user = User::find($user->id);
		return view('users.edit', ['user' => $user]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\User  $user
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, User $user)
	{
		//	save data
		$userUpdate = User::where('id', $user->id)->update([
			'name' 	=> $request->input('name'),
			//'email'	=> $request->input('email')
		]);

		if ($request->hasFile('pic'))
		{
			Cloudder::destroy($user->pic);
			$image = $request->file('pic');

			$filename  = 'temp.' . $image->getClientOriginalExtension();

			$path = public_path('profilepics' . DIRECTORY_SEPARATOR . $filename);

			Image::make($image->getRealPath())->resize(200, 200)->save($path);

			Cloudder::upload($path, null, [], ['upload']);
			$user->pic = Cloudder::getPublicId();
			$user->save();

			unlink($path);
		}

		
		if ($userUpdate) {
			return redirect()->route('users.show', ['user' => $user->id])
			->with('success', 'Information successfully updated');
		}

		//	redirect
		return back()->withInput();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\User  $user
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(User $user)
	{
		if ($user->delete()) {
			return redirect()->route('users.index')->with('success', 'Account deleted successfully');
		}

		return back()->withInput()->with('errors', 'User cannot be deleted');
	}

	public function follow(Request $request, User $followee) {
		$follower = User::find($request->follower_id);

		if ($follower->toggleFollow($followee)) {
			return redirect()->route('users.show', ['user' => $followee])
			/*->with('success', 'You are now following ' . $followee->name)*/;
		}
		return redirect()->route('users.show', ['user' => $followee])->with('errors', 'Follow or unfollow failed');
	}

	public function followers(User $user) {
		return view('users.followers', ['followers' => $user->followers, 'user' => $user]);
	}
}
