<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use Laracasts\Utilities\JavaScript\JavaScriptFacade as JavaScript;
use Illuminate\Http\Request;
use App\Models\Review;
use Tmdb\Laravel\Facades\Tmdb;

class TvsController extends Controller
{
	public function show($id)
	{
		$tv = Tmdb::getTvApi()->getTvshow($id);
		$reviews = Review::tv()->where('reviewable_id', $id)->get();
		$userReview = null;

		$externalIds = Tmdb::getTvApi()->getExternalIds($id);
		$client = new Client();
		$res = $client->get('http://www.omdbapi.com/?apikey=' . env('OMDB_API_KEY') . '&i='. $externalIds['imdb_id']);
		$imdbData = json_decode($res->getBody());

		JavaScript::put([
			'averageRating' => $reviews->avg('stars'),
			'stars' => $reviews->pluck('stars')
		]);

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
		
		return view('tvs.show', [
			'imdbData' => $imdbData,
			'page_title' => $tv['original_name'],
			'reviews' => $reviews, 
			'tv' => $tv, 
			'userReview' => $userReview]);
	}

	public function getSeason($id, $season_number = 1) {
		$tv = Tmdb::getTvApi()->getTvshow($id);
		$season = Tmdb::getTvSeasonApi()->getSeason($id, $season_number);
		$episodes = $season['episodes'];

		/*
		JavaScript::put([
			'stars' => array_column($episodes, 'vote_average')
		]);
*/
		return view('tvs.season', [
			'episodes' => $episodes,
			'season' => $season,
			'tv' => $tv
		]);
	}
}
