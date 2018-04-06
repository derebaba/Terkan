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
use Tmdb\Laravel\Facades\Tmdb;

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
			
		//dd([file_exists(trim($user->pic, '"')), trim($user->pic, '"'), $user->pic]);
		return view('users.show', [
			'user' => $user, 
			'page_title' => $user->name,
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

			Image::make($image->getRealPath())->save($path);

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
		if (Auth::user()->toggleFollow($followee)) {
			return redirect()->route('users.show', ['user' => $followee])
			/*->with('success', 'You are now following ' . $followee->name)*/;
		}
		return redirect()->route('users.show', ['user' => $followee])->with('errors', 'Follow or unfollow failed');
	}

	public function followers(User $user) {
		return view('users.followers', ['followers' => $user->followers, 'user' => $user]);
	}


	public function addToWatchlist(Request $request) {
		if (Auth::check()) {
			DB::table('watchlists')->insert([
				'user_id' => Auth::user()->id,
				'reviewable_id' => $request->reviewable_id,
				'reviewable_type' => $request->reviewable_type	
			]);
			return back()->withSuccess($request->name . ' is added to watchlist');
		}
		return back()->withErrors(['You must be logged in for this action']);
	}

	public function removeFromWatchlist(Request $request) {
		if (Auth::check()) {
			$delete = DB::table('watchlists')->where([
				'user_id' => Auth::user()->id,
				'reviewable_id' => $request->reviewable_id,
				'reviewable_type' => $request->reviewable_type
			])->delete();
			if ($delete) {
				return back()->withSuccess($request->name . ' is removed from watchlist');
			}
			else {
				return back()->withErrors([$request->name . ' cannot be removed from watchlist']);
			}
		}
		return back()->withErrors(['You must be logged in for this action']);
	}

	/**
	 * Shows the watchlist of $user
	 *
	 * @param User $user
	 * @return void
	 */
	public function watchlist(User $user) {
		//dd($user->getWatchlist());
		$watchlist = $user->getWatchlist();
		$movies = [];
		$tvs = [];
		
		$fb_movie_list = "Movies:\n";
		$fb_tv_list = "TV shows:\n";
		foreach ($watchlist as $index => $item) {
			if ($item->reviewable_type === 'movie') {
				$movie = Tmdb::getMoviesApi()->getMovie($item->reviewable_id);
				array_push($movies, $movie);
				$fb_movie_list .= ($index + 1) . ". " . $movie['original_title'] . "\n";
			}
			else {
				$tv = Tmdb::getTvApi()->getTvshow($item->reviewable_id);
				array_push($tvs, $tv);
				$fb_tv_list .= ($index + 1) . ". " . $tv['original_name'] . "\n";
			}
		}
		$fb_description = $fb_movie_list . $fb_tv_list;

		return view('users.watchlist', [
			'fb_description' => $fb_description,
			'movies' => $movies, 
			'tvs' => $tvs,
			'user' => $user
		]);
	}

	public function followTv(Request $request, $tv_id) {
		DB::table('tv_user')->insert([
			'user_id' => Auth::user()->id,
			'tv_id' => $tv_id
		]);
		return back()->withSuccess('You are now following ' . $request->name . '. Check your news feed for new episodes.');
	}

	public function unfollowTv(Request $request, $tv_id) {
		DB::table('tv_user')->where([
			'user_id' => Auth::user()->id,
			'tv_id' => $tv_id
		])->delete();
		return back()->withSuccess('You stopped following ' . $request->name);
	}
}
