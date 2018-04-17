<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use DateTime;
use Illuminate\Support\Facades\DB;
use Laracasts\Utilities\JavaScript\JavaScriptFacade as JavaScript;
use Illuminate\Http\Request;
use App\Review;
use Tmdb\Laravel\Facades\Tmdb;
use App\Traits\Utils;


class HomeController extends Controller
{
	use Utils;
	/*
	public function __construct()
	{
		$this->middleware('auth');
	}
 */
	public function home() {
		if (!Auth::check())
			return $this->welcome();

		$reviews = Review::whereIn('user_id', Auth::user()->followings()->get()->pluck('id'))->get()->reverse()->values();
		$reviewables = $this->getReviewables($reviews);

		$genres = Tmdb::getGenresApi()->getMovieGenres();
		$genres = collect($genres['genres']);	//	19 ve sonrası tv
		
		$movies = Tmdb::getDiscoverApi()->discoverMovies()['results'];
		foreach ($movies as &$movie)
			$movie['media_type'] = 'movie';
		
		$recommendations = $this->getReviewablesFromResults($movies);

		$followingTvs = DB::table('tv_user')->where('user_id', Auth::user()->id)->get();
		$newEpisodes = [];
		$newTvs = [];
		foreach ($followingTvs as $followingTv) {
			$tv = Tmdb::getTvApi()->getTvshow($followingTv->tv_id);
			$season = Tmdb::getTvSeasonApi()->getSeason($followingTv->tv_id, $tv['number_of_seasons']);
			$now = new DateTime();
			foreach ($season['episodes'] as $episode) {
				$diff = date_diff(new DateTime($episode['air_date']), $now)->format('%r%a');
				if ($diff < 7 && $diff > 0) {
					$episode['days_ago'] = $diff;
					array_push($newEpisodes, $episode);
					array_push($newTvs, $tv);
				}
			}	
		}

		JavaScript::put([
			'recommendationStars' => $recommendations->pluck('vote_average'),
			'stars' => $reviews->pluck('stars')
		]);

		return view('home', [
			'reviews' => $reviews,
			'reviewables' => $reviewables,
			'genre_id' => -1,
			'genres' =>	$genres,
			'newEpisodes' => $newEpisodes,
			'newTvs' => $newTvs,
			'recommendations' => $recommendations
		]);
		
	}

	public function welcome() {
		//	get latest popular reviews
		$reviews = Review::all()->take(-10)->reverse()->values();
		$reviewables = $this->getReviewables($reviews);

		JavaScript::put([
			'stars' => $reviews->pluck('stars')
		]);

		$genres = Tmdb::getGenresApi()->getGenres();
		$genres = collect($genres['genres']);	//	19 ve sonrası tv

		return view('welcome', [
			'reviews' => $reviews,
			'reviewables' => $reviewables,
			'genre_id' => -1,
			'genres' =>	$genres,
		]);
	}
}
