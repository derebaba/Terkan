<?php

namespace App\Http\Controllers;

use App\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laracasts\Utilities\JavaScript\JavaScriptFacade as JavaScript;
use Tmdb\Laravel\Facades\Tmdb;

class TvsController extends Controller
{
	public function show($id)
	{
		$tv = Tmdb::getTvApi()->getTvshow($id);
		$reviews = Review::tv()->where('reviewable_id', $id)->get();
		$userReview = null;

		JavaScript::put([
			'averageRating' => $reviews->avg('stars'),
			'stars' => $reviews->pluck('stars')
		]);

		if (Auth::check()) {
			$userReview = $reviews->where('user_id', Auth::user()->id)->first();
			$reviews = $reviews->reject(function ($value, $key) {
				return $value->user_id == Auth::user()->id;
			});

			if ($userReview)
				$reviews->push($userReview)->reverse();
		}
		
		return view('tvs.show', ['tv' => $tv, 'reviews' => $reviews, 'userReview' => $userReview]);
	}
}
